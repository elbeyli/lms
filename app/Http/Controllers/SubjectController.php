<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $query = Subject::where('user_id', Auth::id())
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
    public function store(SubjectRequest $request)
    {
        $subject = Subject::create([
            'user_id' => Auth::id(),
            ...$request->validated(),
        ]);

        if ($request->expectsJson()) {
            $subject->load('courses:id,subject_id,name,is_active');

            return $this->successResponse('Subject created successfully.', $subject, 201);
        }

        return redirect()->route('subjects.show', $subject)->with('success', 'Subject created successfully.');
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
    public function update(SubjectRequest $request, Subject $subject): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $subject);

        $subject->update($request->validated());

        if ($request->expectsJson()) {
            return $this->successResponse('Subject updated successfully.', $subject);
        }

        return $this->redirectWithSuccess('subjects.index', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('delete', $subject);

        $subject->delete();

        if (request()->expectsJson()) {
            return $this->successResponse('Subject deleted successfully.');
        }

        return $this->redirectWithSuccess('subjects.index', 'Subject deleted successfully.');
    }
}
