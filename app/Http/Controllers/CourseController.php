<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $query = Course::whereHas('subject', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['subject:id,name,color', 'topics:id,course_id,name,is_completed,progress_percentage'])
            ->orderBy('priority', 'desc')
            ->orderBy('name');

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('active_only')) {
            $query->where('is_active', true);
        }

        $courses = $query->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $courses,
            ]);
        }

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $subjects = Subject::where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'color']);

        $selectedSubjectId = $request->subject_id;

        return view('courses.create', compact('subjects', 'selectedSubjectId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $subject = Subject::where('id', $request->subject_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $course = Course::create($request->validated());

        if ($request->expectsJson()) {
            $course->load(['subject:id,name,color', 'topics:id,course_id,name,is_completed,progress_percentage']);
            return response()->json([
                'success' => true,
                'message' => 'Course created successfully.',
                'data' => $course,
            ], 201);
        }

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): JsonResponse|View
    {
        $this->authorize('view', $course);

        $course->load([
            'subject:id,name,color',
            'topics' => function ($query) {
                $query->orderBy('difficulty')
                    ->orderBy('name');
            }
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $course,
            ]);
        }

        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        $subjects = Subject::where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'color']);

        return view('courses.edit', compact('course', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course): JsonResponse
    {
        $this->authorize('update', $course);

        $subject = Subject::where('id', $request->subject_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $course->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully.',
            'data' => $course,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('delete', $course);

        $course->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully.',
            ]);
        }

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
