<?php

namespace App\Services;

use App\Models\StudySession;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Collection;

class SessionTrackingService
{
    /**
     * Create a new study session
     */
    public function createSession(User $user, array $data): StudySession
    {
        // Set default planned duration based on user preferences or system defaults
        if (! isset($data['planned_duration'])) {
            $data['planned_duration'] = $this->getDefaultSessionDuration($user);
        }

        $data['user_id'] = $user->id;
        $data['status'] = 'planned';

        return StudySession::create($data);
    }

    /**
     * Start a study session
     */
    public function startSession(StudySession $session): StudySession
    {
        // Ensure no other sessions are active for this user
        $this->pauseActiveUserSessions($session->user);

        $session->update([
            'status' => 'active',
            'started_at' => now(),
            'ended_at' => null,
        ]);

        return $session->fresh();
    }

    /**
     * Pause a study session
     */
    public function pauseSession(StudySession $session): StudySession
    {
        if (! $session->isActive()) {
            throw new \InvalidArgumentException('Session is not active');
        }

        // Calculate current duration if started
        $currentDuration = $session->started_at
            ? $session->started_at->diffInMinutes(now())
            : 0;

        $session->update([
            'status' => 'paused',
            'actual_duration' => $currentDuration,
        ]);

        return $session->fresh();
    }

    /**
     * Resume a paused study session
     */
    public function resumeSession(StudySession $session): StudySession
    {
        if (! $session->isPaused()) {
            throw new \InvalidArgumentException('Session is not paused');
        }

        // Ensure no other sessions are active for this user
        $this->pauseActiveUserSessions($session->user);

        $session->update([
            'status' => 'active',
            // Keep the original started_at time
        ]);

        return $session->fresh();
    }

    /**
     * Complete a study session
     */
    public function completeSession(StudySession $session, array $completionData = []): StudySession
    {
        if (! in_array($session->status, ['active', 'paused'])) {
            throw new \InvalidArgumentException('Session cannot be completed from current status: '.$session->status);
        }

        // Calculate final duration
        $actualDuration = $session->started_at
            ? $session->started_at->diffInMinutes(now())
            : ($session->actual_duration ?? 0);

        $updateData = array_merge($completionData, [
            'status' => 'completed',
            'ended_at' => now(),
            'completed_at' => now(),
            'actual_duration' => $actualDuration,
        ]);

        $session->update($updateData);

        // Update related topic progress if applicable
        $this->updateTopicProgress($session);

        return $session->fresh();
    }

    /**
     * Abandon a study session
     */
    public function abandonSession(StudySession $session, ?string $reason = null): StudySession
    {
        $sessionData = $session->session_data ?? [];
        if ($reason) {
            $sessionData['abandonment_reason'] = $reason;
        }

        $session->update([
            'status' => 'abandoned',
            'ended_at' => now(),
            'session_data' => $sessionData,
        ]);

        return $session->fresh();
    }

    /**
     * Get user's active study session
     */
    public function getActiveSession(User $user): ?StudySession
    {
        return $user->studySessions()
            ->whereIn('status', ['active', 'paused'])
            ->with(['subject', 'course', 'topic'])
            ->first();
    }

    /**
     * Get recommended session duration for user
     */
    public function getRecommendedDuration(User $user, ?Subject $subject = null): int
    {
        // Start with user's default preference
        $baseDuration = $this->getDefaultSessionDuration($user);

        if (! $subject) {
            return $baseDuration;
        }

        // Analyze user's historical performance for this subject
        $recentSessions = $user->studySessions()
            ->where('subject_id', $subject->id)
            ->where('status', 'completed')
            ->where('created_at', '>', now()->subWeeks(4))
            ->get();

        if ($recentSessions->isEmpty()) {
            return $baseDuration;
        }

        // Calculate average effective session length
        $avgEffectiveDuration = $recentSessions
            ->where('effectiveness_rating', '>=', 6) // Only consider effective sessions
            ->avg('actual_duration');

        return $avgEffectiveDuration ? round($avgEffectiveDuration) : $baseDuration;
    }

    /**
     * Calculate learning velocity for user
     */
    public function calculateLearningVelocity(User $user, ?Subject $subject = null): array
    {
        $query = $user->studySessions()->where('status', 'completed');

        if ($subject) {
            $query->where('subject_id', $subject->id);
        }

        $sessions = $query->where('created_at', '>', now()->subWeeks(4))->get();

        if ($sessions->isEmpty()) {
            return [
                'concepts_per_hour' => 0,
                'average_effectiveness' => 0,
                'session_count' => 0,
                'total_hours' => 0,
            ];
        }

        $totalHours = $sessions->sum('actual_duration') / 60;
        $totalConcepts = $sessions->sum('concepts_completed');
        $avgEffectiveness = $sessions->avg('effectiveness_rating');

        return [
            'concepts_per_hour' => $totalHours > 0 ? round($totalConcepts / $totalHours, 2) : 0,
            'average_effectiveness' => round($avgEffectiveness, 1),
            'session_count' => $sessions->count(),
            'total_hours' => round($totalHours, 1),
        ];
    }

    /**
     * Get session analytics for user
     */
    public function getSessionAnalytics(User $user, int $days = 30): array
    {
        $sessions = $user->studySessions()
            ->where('created_at', '>', now()->subDays($days))
            ->with(['subject', 'course', 'topic'])
            ->get();

        $completedSessions = $sessions->where('status', 'completed');
        $totalPlannedMinutes = $sessions->sum('planned_duration');
        $totalActualMinutes = $completedSessions->sum('actual_duration');

        return [
            'total_sessions' => $sessions->count(),
            'completed_sessions' => $completedSessions->count(),
            'completion_rate' => $sessions->count() > 0 ? round(($completedSessions->count() / $sessions->count()) * 100, 1) : 0,
            'total_study_hours' => round($totalActualMinutes / 60, 1),
            'average_session_length' => $completedSessions->count() > 0 ? round($completedSessions->avg('actual_duration'), 1) : 0,
            'planning_accuracy' => $totalPlannedMinutes > 0 ? round(($totalActualMinutes / $totalPlannedMinutes) * 100, 1) : 0,
            'average_effectiveness' => round($completedSessions->avg('effectiveness_rating'), 1),
            'average_focus' => round($completedSessions->avg('focus_score'), 1),
            'subjects_studied' => $sessions->pluck('subject_id')->filter()->unique()->count(),
        ];
    }

    /**
     * Get deadline-prioritized session recommendations
     */
    public function getDeadlinePrioritizedRecommendations(User $user): Collection
    {
        $subjects = $user->subjects()
            ->where('is_active', true)
            ->whereNotNull('final_exam_date')
            ->where('final_exam_date', '>', now())
            ->with(['courses.topics'])
            ->get();

        return $subjects->map(function ($subject) use ($user) {
            $daysUntilExam = now()->diffInDays($subject->final_exam_date, false);
            $urgencyScore = $this->calculateUrgencyScore($daysUntilExam);

            $velocity = $this->calculateLearningVelocity($user, $subject);
            $recommendedDuration = $this->getRecommendedDuration($user, $subject);

            return [
                'subject' => $subject,
                'days_until_exam' => $daysUntilExam,
                'urgency_score' => $urgencyScore,
                'recommended_duration' => $recommendedDuration,
                'learning_velocity' => $velocity,
                'priority_level' => $this->getPriorityLevel($urgencyScore),
            ];
        })->sortByDesc('urgency_score');
    }

    /**
     * Private helper methods
     */
    private function getDefaultSessionDuration(User $user): int
    {
        // Default to 45 minutes - this could come from user settings in the future
        return 45;
    }

    private function pauseActiveUserSessions(User $user): void
    {
        $user->studySessions()
            ->where('status', 'active')
            ->update(['status' => 'paused']);
    }

    private function updateTopicProgress(StudySession $session): void
    {
        if (! $session->topic) {
            return;
        }

        $topic = $session->topic;

        // If concepts were completed, update topic progress
        if ($session->concepts_completed > 0) {
            $newProgress = min(100, $topic->progress_percentage + ($session->concepts_completed * 10));
            $topic->updateProgress($newProgress);
        }
    }

    private function calculateUrgencyScore(int $daysUntilDeadline): float
    {
        if ($daysUntilDeadline <= 0) {
            return 10.0; // Overdue - maximum urgency
        }

        if ($daysUntilDeadline <= 3) {
            return 9.0; // Critical
        }

        if ($daysUntilDeadline <= 7) {
            return 7.0; // High
        }

        if ($daysUntilDeadline <= 14) {
            return 5.0; // Medium
        }

        if ($daysUntilDeadline <= 30) {
            return 3.0; // Low
        }

        return 1.0; // Very low
    }

    private function getPriorityLevel(float $urgencyScore): string
    {
        if ($urgencyScore >= 9.0) {
            return 'critical';
        }
        if ($urgencyScore >= 7.0) {
            return 'high';
        }
        if ($urgencyScore >= 5.0) {
            return 'medium';
        }
        if ($urgencyScore >= 3.0) {
            return 'low';
        }

        return 'very_low';
    }
}
