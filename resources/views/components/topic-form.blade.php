<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-body">
<form id="topicForm" action="{{ $action }}" method="POST" x-data="{ 
                difficulty: {{ old('difficulty', $topic->difficulty ?? 5) }},
                prerequisites: {{ json_encode(old('prerequisites', $topic->prerequisites ?? [])) }},
                progress: {{ old('progress_percentage', $topic->progress_percentage ?? 0) }}
            }">
                @csrf
                @if($topic->exists)
                    @method('PUT')
                @endif

                <!-- Course Selection -->
                <div class="mb-6">
                    <label for="course_id" class="form-label">Course *</label>
                    <select
                        id="course_id" 
                        name="course_id" 
                        class="form-input @error('course_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a course...</option>
                        @foreach($courses->groupBy('subject.name') as $subjectName => $subjectCourses)
                            <optgroup label="{{ $subjectName }}">
                                @foreach($subjectCourses as $course)
                                    <option value="{{ $course->id }}" 
                                        {{ old('course_id', $topic->course_id) == $course->id ? 'selected' : '' }}
                                    >
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Topic Name -->
                <div class="mb-6">
                    <label for="name" class="form-label">Topic Name *</label>
                    <input type="text"
                           id="name" 
                           name="name" 
                           value="{{ old('name', $topic->name) }}" 
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="e.g., Introduction to Derivatives, World War II"
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
                              placeholder="Brief description of what this topic covers...">{{ old('description', $topic->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Difficulty and Estimated Minutes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="difficulty" class="form-label">Difficulty Level (1-10)</label>
                        <div class="flex items-center gap-4">
                            <input type="range"
                                   id="difficulty" 
                                   name="difficulty" 
                                   min="1" 
                                   max="10"
                                   class="flex-1 form-range @error('difficulty') border-red-500 @enderror"
                                   x-model="difficulty">
                            <x-difficulty-meter :value="0" x-bind:value="difficulty" />
                        </div>
                        @error('difficulty')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estimated_minutes" class="form-label">Estimated Minutes</label>
                        <input type="number"
                               id="estimated_minutes" 
                               name="estimated_minutes" 
                               value="{{ old('estimated_minutes', $topic->estimated_minutes) }}" 
                               min="0"
                               step="5"
                               class="form-input @error('estimated_minutes') border-red-500 @enderror">
                        @error('estimated_minutes')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Prerequisites -->
                <div class="mb-6">
                    <label for="prerequisites" class="form-label">Prerequisites</label>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <template x-for="(prereq, index) in prerequisites" :key="index">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100">
                                <span x-text="prereq"></span>
                                <button type="button" @click="prerequisites.splice(index, 1)" class="ml-2 text-gray-500 hover:text-gray-700">
                                    &times;
                                </button>
                            </div>
                        </template>
                    </div>
                    
                    <div class="flex gap-2">
                        <input type="text"
                               id="prerequisite-input"
                               class="form-input flex-1"
                               placeholder="Add a prerequisite..."
                               @keydown.enter.prevent="
                                   const value = $event.target.value.trim();
                                   if (value && !prerequisites.includes(value)) {
                                       prerequisites.push(value);
                                       $event.target.value = '';
                                   }
                               ">
                        <input type="hidden" name="prerequisites" :value="JSON.stringify(prerequisites)">
                    </div>
                    @error('prerequisites')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Progress -->
                <div class="mb-6">
                    <label for="progress_percentage" class="form-label">Progress</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-4">
                            <input type="range"
                                   id="progress_percentage" 
                                   name="progress_percentage" 
                                   class="flex-1 form-range @error('progress_percentage') border-red-500 @enderror"
                                   min="0"
                                   max="100"
                                   step="5"
                                   x-model="progress">
                            <span class="text-sm font-medium" x-text="progress + '%'"></span>
                        </div>
                        <x-progress-bar :value="0" x-bind:value="progress" size="md" />
                    </div>
                    @error('progress_percentage')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Completion Status -->
                @if($topic->exists)
                    <div class="mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox"
                                   name="is_completed" 
                                   value="1" 
                                   class="form-checkbox" 
                                   {{ $topic->is_completed ? 'checked' : '' }}>
                            <span class="ml-2">Mark as completed</span>
                        </label>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('topics.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        {{ $topic->exists ? 'Update' : 'Create' }} Topic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>