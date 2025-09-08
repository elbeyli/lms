<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'subject_id' => ['required', 'exists:subjects,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'priority' => ['nullable', 'integer', 'min:1', 'max:10'],
            'deadline' => ['nullable', 'date', 'after:today'],
            'estimated_hours' => ['nullable', 'integer', 'min:1'],
            'progress_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
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
            'subject_id.required' => 'Subject is required.',
            'subject_id.exists' => 'Selected subject does not exist.',
            'name.required' => 'Course name is required.',
            'name.max' => 'Course name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 2000 characters.',
            'priority.integer' => 'Priority must be a number.',
            'priority.min' => 'Priority must be at least 1.',
            'priority.max' => 'Priority cannot exceed 10.',
            'deadline.date' => 'Deadline must be a valid date.',
            'deadline.after' => 'Deadline must be in the future.',
            'estimated_hours.integer' => 'Estimated hours must be a number.',
            'estimated_hours.min' => 'Estimated hours must be at least 1.',
            'progress_percentage.integer' => 'Progress must be a number.',
            'progress_percentage.min' => 'Progress cannot be less than 0%.',
            'progress_percentage.max' => 'Progress cannot exceed 100%.',
        ];
    }
}
