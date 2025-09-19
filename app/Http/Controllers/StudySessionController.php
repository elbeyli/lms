<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudySessionRequest;
use App\Models\Course;
use App\Models\StudySession;
use App\Models\Subject;
use App\Models\Topic;
use App\Services\SessionTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudySessionController extends Controller
{
    public function __construct(
        private SessionTrackingService $sessionService
    ) {}

    /**
     * Display a listing of study sessions
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $sessions = $user->studySessions()
            ->with(['subject', 'course', 'topic'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $analytics = $this->sessionService->getSessionAnalytics($user);
        $activeSession = $this->sessionService->getActiveSession($user);

        return view('sessions.index', compact('sessions', 'analytics', 'activeSession'));
    }

    /**
     * Show the form for creating a new session
     */
    public function create(Request $request): View
    {
        $user = auth()->user();

        // Get subjects, courses, and topics for selection
        $subjects = $user->subjects()->where('is_active', true)->get();
        $courses = collect();
        $topics = collect();

        // If a specific subject/course/topic is pre-selected
        if ($request->has('subject_id')) {
            $courses = Subject::find($request->subject_id)?->courses()->where('is_active', true)->get() ?? collect();
        }
        if ($request->has('course_id')) {
            $topics = Course::find($request->course_id)?->topics()->where('is_active', true)->get() ?? collect();
        }

        // Get deadline-prioritized recommendations
        $recommendations = $this->sessionService->getDeadlinePrioritizedRecommendations($user);

        return view('sessions.create', compact('subjects', 'courses', 'topics', 'recommendations'));
    }

    /**
     * Store a newly created session
     */
    public function store(StudySessionRequest $request): RedirectResponse
    {
        $user = auth()->user();

        try {
            $session = $this->sessionService->createSession($user, $request->validated());

            return $this->handleResponse(
                route('sessions.show', $session),
                'Study session created successfully!',
                ['session' => $session->load(['subject', 'course', 'topic'])]
            );
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create session: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified session
     */
    public function show(StudySession $session): View
    {
        $this->authorize('view', $session);

        $session->load(['subject', 'course', 'topic']);

        return view('sessions.show', compact('session'));
    }

    /**
     * Start a study session
     */
    public function start(StudySession $session): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $session);

        try {
            $updatedSession = $this->sessionService->startSession($session);

            return $this->handleResponse(
                route('sessions.active', $updatedSession),
                'Session started successfully!',
                [
                    'session' => $updatedSession->load(['subject', 'course', 'topic']),
                    'started_at' => $updatedSession->started_at->toISOString(),
                ]
            );
        } catch (\Exception $e) {
            return $this->handleError('Failed to start session: '.$e->getMessage());
        }
    }

    /**
     * Pause a study session
     */
    public function pause(StudySession $session): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $session);

        try {
            $updatedSession = $this->sessionService->pauseSession($session);

            return $this->handleResponse(
                route('sessions.show', $updatedSession),
                'Session paused successfully!',
                ['session' => $updatedSession->load(['subject', 'course', 'topic'])]
            );
        } catch (\Exception $e) {
            return $this->handleError('Failed to pause session: '.$e->getMessage());
        }
    }

    /**
     * Resume a study session
     */
    public function resume(StudySession $session): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $session);

        try {
            $updatedSession = $this->sessionService->resumeSession($session);

            return $this->handleResponse(
                route('sessions.active', $updatedSession),
                'Session resumed successfully!',
                ['session' => $updatedSession->load(['subject', 'course', 'topic'])]
            );
        } catch (\Exception $e) {
            return $this->handleError('Failed to resume session: '.$e->getMessage());
        }
    }

    /**
     * Complete a study session
     */
    public function complete(StudySession $session, Request $request): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $session);

        $request->validate([
            'focus_score' => ['nullable', 'integer', 'min:1', 'max:10'],
            'effectiveness_rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'concepts_completed' => ['nullable', 'integer', 'min:0'],
            'break_count' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $completionData = $request->only([
                'focus_score', 'effectiveness_rating', 'concepts_completed', 'break_count', 'notes',
            ]);

            $completedSession = $this->sessionService->completeSession($session, $completionData);

            return $this->handleResponse(
                route('sessions.show', $completedSession),
                'Session completed successfully!',
                [
                    'session' => $completedSession->load(['subject', 'course', 'topic']),
                    'analytics' => [
                        'duration_difference' => $completedSession->duration_difference,
                        'efficiency_rate' => $completedSession->efficiency_rate,
                    ],
                ]
            );
        } catch (\Exception $e) {
            return $this->handleError('Failed to complete session: '.$e->getMessage());
        }
    }

    /**
     * Abandon a study session
     */
    public function abandon(StudySession $session, Request $request): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $session);

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $abandonedSession = $this->sessionService->abandonSession($session, $request->reason);

            return $this->handleResponse(
                route('sessions.index'),
                'Session abandoned.',
                ['session' => $abandonedSession]
            );
        } catch (\Exception $e) {
            return $this->handleError('Failed to abandon session: '.$e->getMessage());
        }
    }

    /**
     * Show active session interface
     */
    public function active(StudySession $session): View
    {
        $this->authorize('view', $session);

        if (! $session->isActive()) {
            return redirect()->route('sessions.show', $session)
                ->with('error', 'Session is not currently active.');
        }

        $session->load(['subject', 'course', 'topic']);
        $elapsedMinutes = $session->started_at ? $session->started_at->diffInMinutes(now()) : 0;

        return view('sessions.active', compact('session', 'elapsedMinutes'));
    }

    /**
     * Get session analytics
     */
    public function analytics(Request $request): JsonResponse
    {
        $user = auth()->user();
        $days = $request->get('days', 30);

        $analytics = $this->sessionService->getSessionAnalytics($user, $days);
        $velocity = $this->sessionService->calculateLearningVelocity($user);

        return response()->json([
            'analytics' => $analytics,
            'learning_velocity' => $velocity,
        ]);
    }

    /**
     * Get current active session
     */
    public function current(): JsonResponse
    {
        $user = auth()->user();
        $activeSession = $this->sessionService->getActiveSession($user);

        if (! $activeSession) {
            return response()->json(['session' => null]);
        }

        $elapsedMinutes = $activeSession->started_at
            ? $activeSession->started_at->diffInMinutes(now())
            : 0;

        return response()->json([
            'session' => $activeSession->load(['subject', 'course', 'topic']),
            'elapsed_minutes' => $elapsedMinutes,
        ]);
    }

    /**
     * Update the specified session
     */
    public function update(StudySessionRequest $request, StudySession $session): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $session);

        $session->update($request->validated());

        return $this->handleResponse(
            route('sessions.show', $session),
            'Session updated successfully!',
            ['session' => $session->load(['subject', 'course', 'topic'])]
        );
    }

    /**
     * Remove the specified session
     */
    public function destroy(StudySession $session): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $session);

        $session->delete();

        return $this->handleResponse(
            route('sessions.index'),
            'Session deleted successfully!'
        );
    }

    /**
     * Handle response based on request type
     */
    private function handleResponse(string $redirectUrl, string $message, array $data = []): JsonResponse|RedirectResponse
    {
        if (request()->expectsJson()) {
            return response()->json(array_merge(['message' => $message, 'redirect_url' => $redirectUrl], $data));
        }

        return redirect($redirectUrl)->with('success', $message);
    }

    /**
     * Handle error response based on request type
     */
    private function handleError(string $message): JsonResponse|RedirectResponse
    {
        if (request()->expectsJson()) {
            return response()->json(['error' => $message], 422);
        }

        return back()->withErrors(['error' => $message]);
    }
}
