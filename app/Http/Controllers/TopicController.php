<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Course;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $query = Topic::whereHas('course.subject', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['course:id,subject_id,name', 'course.subject:id,name,color'])
            ->orderBy('difficulty')
            ->orderBy('name');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('active_only')) {
            $query->where('is_active', true);
        }

        if ($request->filled('completed_only')) {
            $query->where('is_completed', true);
        }

        $topics = $query->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $topics,
            ]);
        }

        return view('topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $courses = Course::whereHas('subject', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('subject:id,name,color')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'subject_id', 'name']);

        $selectedCourseId = $request->course_id;

        $availableTopics = Topic::whereHas('course.subject', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'course_id', 'name']);

        return view('topics.create', compact('courses', 'selectedCourseId', 'availableTopics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TopicRequest $request): JsonResponse
    {
        $course = Course::whereHas('subject', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('id', $request->course_id)->firstOrFail();

        $topic = Topic::create($request->validated());

        $topic->load(['course:id,subject_id,name', 'course.subject:id,name,color']);

        return response()->json([
            'success' => true,
            'message' => 'Topic created successfully.',
            'data' => $topic,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic): JsonResponse|View
    {
        $this->authorize('view', $topic);

        $topic->load(['course:id,subject_id,name', 'course.subject:id,name,color']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $topic,
            ]);
        }

        return view('topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic): View
    {
        $this->authorize('update', $topic);

        $courses = Course::whereHas('subject', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('subject:id,name,color')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'subject_id', 'name']);

        $availableTopics = Topic::whereHas('course.subject', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('is_active', true)
            ->where('id', '!=', $topic->id)
            ->orderBy('name')
            ->get(['id', 'course_id', 'name']);

        return view('topics.edit', compact('topic', 'courses', 'availableTopics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TopicRequest $request, Topic $topic): JsonResponse
    {
        $this->authorize('update', $topic);

        $course = Course::whereHas('subject', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('id', $request->course_id)->firstOrFail();

        $topic->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Topic updated successfully.',
            'data' => $topic,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic): JsonResponse
    {
        $this->authorize('delete', $topic);

        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topic deleted successfully.',
        ]);
    }

    /**
     * Mark topic as completed.
     */
    public function complete(Topic $topic): JsonResponse
    {
        $this->authorize('update', $topic);

        $topic->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Topic marked as completed.',
            'data' => $topic,
        ]);
    }

    /**
     * Update topic progress.
     */
    public function updateProgress(Request $request, Topic $topic): JsonResponse
    {
        $this->authorize('update', $topic);

        $request->validate([
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $topic->updateProgress($request->progress);

        return response()->json([
            'success' => true,
            'message' => 'Topic progress updated.',
            'data' => $topic,
        ]);
    }
}
