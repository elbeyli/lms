<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'difficulty_base' => ['nullable', 'integer', 'min:1', 'max:10'],
            'total_hours_estimated' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'final_exam_date' => ['nullable', 'date', 'after:today'],
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
            'name.required' => 'Subject name is required.',
            'name.max' => 'Subject name cannot exceed 255 characters.',
            'color.regex' => 'Color must be a valid hex color code (e.g., #3B82F6).',
            'difficulty_base.integer' => 'Difficulty must be a number.',
            'difficulty_base.min' => 'Difficulty must be at least 1.',
            'difficulty_base.max' => 'Difficulty cannot exceed 10.',
            'total_hours_estimated.integer' => 'Estimated hours must be a number.',
            'total_hours_estimated.min' => 'Estimated hours must be at least 1.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'final_exam_date.date' => 'Final exam date must be a valid date.',
            'final_exam_date.after' => 'Final exam date must be in the future.',
        ];
    }
}
