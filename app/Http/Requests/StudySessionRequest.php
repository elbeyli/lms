<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudySessionRequest extends FormRequest
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
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'planned_duration' => ['required', 'integer', 'min:1', 'max:600'],
            'actual_duration' => ['nullable', 'integer', 'min:1', 'max:600'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date', 'after:started_at'],
            'completed_at' => ['nullable', 'date'],
            'focus_score' => ['nullable', 'integer', 'min:1', 'max:10'],
            'concepts_completed' => ['nullable', 'integer', 'min:0'],
            'effectiveness_rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'break_count' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'in:planned,active,paused,completed,abandoned'],
            'session_data' => ['nullable', 'array'],
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
            'subject_id.exists' => 'The selected subject does not exist.',
            'course_id.exists' => 'The selected course does not exist.',
            'topic_id.exists' => 'The selected topic does not exist.',
            'planned_duration.required' => 'Planned duration is required.',
            'planned_duration.integer' => 'Planned duration must be a number.',
            'planned_duration.min' => 'Planned duration must be at least 1 minute.',
            'planned_duration.max' => 'Planned duration cannot exceed 600 minutes (10 hours).',
            'actual_duration.integer' => 'Actual duration must be a number.',
            'actual_duration.min' => 'Actual duration must be at least 1 minute.',
            'actual_duration.max' => 'Actual duration cannot exceed 600 minutes (10 hours).',
            'ended_at.after' => 'End time must be after start time.',
            'focus_score.integer' => 'Focus score must be a number.',
            'focus_score.min' => 'Focus score must be at least 1.',
            'focus_score.max' => 'Focus score cannot exceed 10.',
            'concepts_completed.integer' => 'Concepts completed must be a number.',
            'concepts_completed.min' => 'Concepts completed cannot be negative.',
            'effectiveness_rating.integer' => 'Effectiveness rating must be a number.',
            'effectiveness_rating.min' => 'Effectiveness rating must be at least 1.',
            'effectiveness_rating.max' => 'Effectiveness rating cannot exceed 10.',
            'break_count.integer' => 'Break count must be a number.',
            'break_count.min' => 'Break count cannot be negative.',
            'notes.max' => 'Notes cannot exceed 2000 characters.',
            'status.in' => 'Status must be one of: planned, active, paused, completed, abandoned.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Ensure at least one learning target is specified
            if (! $this->subject_id && ! $this->course_id && ! $this->topic_id) {
                $validator->errors()->add('learning_target', 'Please specify at least one learning target (subject, course, or topic).');
            }

            // Validate that course belongs to subject if both are specified
            if ($this->subject_id && $this->course_id) {
                $course = \App\Models\Course::find($this->course_id);
                if ($course && $course->subject_id != $this->subject_id) {
                    $validator->errors()->add('course_id', 'The selected course does not belong to the specified subject.');
                }
            }

            // Validate that topic belongs to course if both are specified
            if ($this->course_id && $this->topic_id) {
                $topic = \App\Models\Topic::find($this->topic_id);
                if ($topic && $topic->course_id != $this->course_id) {
                    $validator->errors()->add('topic_id', 'The selected topic does not belong to the specified course.');
                }
            }
        });
    }
}
