<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $query = Subject::where('user_id', auth()->id())
            ->with('courses:id,subject_id,name,is_active')
            ->orderBy('name');

        if ($request->filled('active_only')) {
            $query->where('is_active', true);
        }

        $subjects = $query->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $subjects,
            ]);
        }

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request): JsonResponse
    {
        $subject = Subject::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        $subject->load('courses:id,subject_id,name,is_active');

        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully.',
            'data' => $subject,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject): JsonResponse|View
    {
        $this->authorize('view', $subject);

        $subject->load(['courses' => function ($query) {
            $query->with('topics:id,course_id,name,is_completed,progress_percentage')
                ->orderBy('priority', 'desc')
                ->orderBy('name');
        }]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $subject,
            ]);
        }

        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject): View
    {
        $this->authorize('update', $subject);

        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, Subject $subject): JsonResponse
    {
        $this->authorize('update', $subject);

        $subject->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Subject updated successfully.',
            'data' => $subject,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): JsonResponse
    {
        $this->authorize('delete', $subject);

        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject deleted successfully.',
        ]);
    }
}
