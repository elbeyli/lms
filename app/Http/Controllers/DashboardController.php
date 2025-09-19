<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudySession;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Get overview statistics
        $stats = [
            'subjects' => Subject::where('user_id', $user->id)->count(),
            'active_subjects' => Subject::where('user_id', $user->id)->where('is_active', true)->count(),
            'courses' => Course::whereHas('subject', fn ($query) => $query->where('user_id', $user->id))->count(),
            'active_courses' => Course::whereHas('subject', fn ($query) => $query->where('user_id', $user->id))->where('is_active', true)->count(),
            'topics' => Topic::whereHas('course.subject', fn ($query) => $query->where('user_id', $user->id))->count(),
            'active_topics' => Topic::whereHas('course.subject', fn ($query) => $query->where('user_id', $user->id))->where('is_active', true)->count(),
            'completed_topics' => Topic::whereHas('course.subject', fn ($query) => $query->where('user_id', $user->id))->where('is_completed', true)->count(),
            'sessions' => StudySession::where('user_id', $user->id)->count(),
            'active_sessions' => StudySession::where('user_id', $user->id)->where('status', 'active')->count(),
        ];

        // Calculate progress percentage
        $stats['overall_progress'] = $stats['active_topics'] > 0
            ? round(($stats['completed_topics'] / $stats['active_topics']) * 100, 1)
            : 0;

        // Get recent subjects with their courses
        $recent_subjects = Subject::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['courses' => function ($query) {
                $query->where('is_active', true)
                    ->withCount(['topics', 'topics as completed_topics_count' => function ($q) {
                        $q->where('is_completed', true);
                    }])
                    ->orderBy('priority', 'desc')
                    ->take(3);
            }])
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        // Get upcoming deadlines (both course and subject final exam deadlines)
        $course_deadlines = Course::whereHas('subject', fn ($query) => $query->where('user_id', $user->id))
            ->where('is_active', true)
            ->whereNotNull('deadline')
            ->where('deadline', '>', now())
            ->with('subject:id,name,color')
            ->get(['id', 'name', 'deadline', 'subject_id'])
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'deadline' => $course->deadline,
                    'type' => 'course',
                    'subject_name' => $course->subject->name,
                    'subject_color' => $course->subject->color,
                ];
            });

        $final_exam_deadlines = Subject::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereNotNull('final_exam_date')
            ->where('final_exam_date', '>', now())
            ->get(['id', 'name', 'final_exam_date', 'color'])
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name.' Final',
                    'deadline' => $subject->final_exam_date,
                    'type' => 'final_exam',
                    'subject_name' => $subject->name,
                    'subject_color' => $subject->color,
                ];
            });

        $upcoming_deadlines = $course_deadlines->concat($final_exam_deadlines)
            ->sortBy('deadline')
            ->take(5);

        // Get recent activity (topics updated/completed)
        $recent_activity = Topic::whereHas('course.subject', fn ($query) => $query->where('user_id', $user->id))
            ->where('updated_at', '>', now()->subDays(7))
            ->with(['course:id,name,subject_id', 'course.subject:id,name,color'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_subjects', 'upcoming_deadlines', 'recent_activity'));
    }
}
