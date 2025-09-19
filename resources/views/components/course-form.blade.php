<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-body">
<form id="courseForm" action="{{ $action }}" method="POST" x-data="{ 
                priority: {{ old('priority', $course->priority ?? 5) }},
                deadline: '{{ old('deadline', optional($course->deadline)->format('Y-m-d')) }}' 
            }">
                @csrf
                @if($course->exists)
                    @method('PUT')
                @endif

                <!-- Subject Selection -->
                <div class="mb-6">
                    <label for="subject_id" class="form-label">Subject *</label>
                    <select
                        id="subject_id" 
                        name="subject_id" 
                        class="form-input @error('subject_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a subject...</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ old('subject_id', $course->subject_id) == $subject->id ? 'selected' : '' }}
                                data-color="{{ $subject->color }}"
                            >
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Name -->
                <div class="mb-6">
                    <label for="name" class="form-label">Course Name *</label>
                    <input type="text"
                           id="name" 
                           name="name" 
                           value="{{ old('name', $course->name) }}" 
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="e.g., Calculus 101, World History"
                           required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description"
                              name="description" 
                              rows="3" 
                              class="form-input @error('description') border-red-500 @enderror"
                              placeholder="Brief description of what this course covers...">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority and Estimated Hours -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="priority" class="form-label">Priority Level (1-10)</label>
                        <div class="flex items-center gap-4">
                            <input type="range"
                                   id="priority" 
                                   name="priority" 
                                   min="1" 
                                   max="10"
                                   class="flex-1 form-range @error('priority') border-red-500 @enderror"
                                   x-model="priority">
                            <x-difficulty-meter :value="0" x-bind:value="priority" />
                        </div>
                        @error('priority')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estimated_hours" class="form-label">Estimated Hours</label>
                        <input type="number"
                               id="estimated_hours" 
                               name="estimated_hours" 
                               value="{{ old('estimated_hours', $course->estimated_hours) }}" 
                               min="0"
                               step="0.5"
                               class="form-input @error('estimated_hours') border-red-500 @enderror">
                        @error('estimated_hours')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deadline -->
                <div class="mb-6">
                    <label for="deadline" class="form-label">Deadline</label>
                    <input type="date"
                           id="deadline" 
                           name="deadline" 
                           x-model="deadline"
                           class="form-input @error('deadline') border-red-500 @enderror"
                           min="{{ date('Y-m-d') }}">
                    @error('deadline')
                        <p class="form-error">{{ $message }}</p>
                    @enderror

                    <div class="mt-2" x-show="deadline">
                        <x-deadline-badge x-bind:deadline="deadline" />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('courses.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        {{ $course->exists ? 'Update' : 'Create' }} Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>