<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
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
            'courses' => Course::whereHas('subject', fn($query) => $query->where('user_id', $user->id))->count(),
            'active_courses' => Course::whereHas('subject', fn($query) => $query->where('user_id', $user->id))->where('is_active', true)->count(),
            'topics' => Topic::whereHas('course.subject', fn($query) => $query->where('user_id', $user->id))->count(),
            'active_topics' => Topic::whereHas('course.subject', fn($query) => $query->where('user_id', $user->id))->where('is_active', true)->count(),
            'completed_topics' => Topic::whereHas('course.subject', fn($query) => $query->where('user_id', $user->id))->where('is_completed', true)->count(),
        ];

        // Calculate progress percentage
        $stats['overall_progress'] = $stats['active_topics'] > 0 
            ? round(($stats['completed_topics'] / $stats['active_topics']) * 100, 1)
            : 0;

        // Get recent subjects with their courses
        $recent_subjects = Subject::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['courses' => function($query) {
                $query->where('is_active', true)
                    ->withCount(['topics', 'topics as completed_topics_count' => function($q) {
                        $q->where('is_completed', true);
                    }])
                    ->orderBy('priority', 'desc')
                    ->take(3);
            }])
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        // Get upcoming deadlines
        $upcoming_deadlines = Course::whereHas('subject', fn($query) => $query->where('user_id', $user->id))
            ->where('is_active', true)
            ->whereNotNull('deadline')
            ->where('deadline', '>', now())
            ->orderBy('deadline')
            ->take(5)
            ->get(['id', 'name', 'deadline', 'subject_id']);

        // Get recent activity (topics updated/completed)
        $recent_activity = Topic::whereHas('course.subject', fn($query) => $query->where('user_id', $user->id))
            ->where('updated_at', '>', now()->subDays(7))
            ->with(['course:id,name,subject_id', 'course.subject:id,name,color'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_subjects', 'upcoming_deadlines', 'recent_activity'));
    }
}
