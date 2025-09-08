<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'difficulty' => ['nullable', 'integer', 'min:1', 'max:10'],
            'estimated_minutes' => ['nullable', 'integer', 'min:5', 'max:480'],
            'prerequisites' => ['nullable', 'array'],
            'prerequisites.*' => ['integer', 'exists:topics,id'],
            'progress_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_completed' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'Course is required.',
            'course_id.exists' => 'Selected course does not exist.',
            'name.required' => 'Topic name is required.',
            'name.max' => 'Topic name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 2000 characters.',
            'difficulty.integer' => 'Difficulty must be a number.',
            'difficulty.min' => 'Difficulty must be at least 1.',
            'difficulty.max' => 'Difficulty cannot exceed 10.',
            'estimated_minutes.integer' => 'Estimated minutes must be a number.',
            'estimated_minutes.min' => 'Estimated time must be at least 5 minutes.',
            'estimated_minutes.max' => 'Estimated time cannot exceed 8 hours (480 minutes).',
            'prerequisites.array' => 'Prerequisites must be a list.',
            'prerequisites.*.integer' => 'Each prerequisite must be a valid topic ID.',
            'prerequisites.*.exists' => 'One or more prerequisite topics do not exist.',
            'progress_percentage.integer' => 'Progress must be a number.',
            'progress_percentage.min' => 'Progress cannot be less than 0%.',
            'progress_percentage.max' => 'Progress cannot exceed 100%.',
        ];
    }
}
