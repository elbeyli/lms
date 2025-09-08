<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('topics.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Create New Topic</h1>
                <p class="text-sm text-gray-600 mt-1">Add a new topic to track your learning progress</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form id="topicForm" action="{{ route('topics.store') }}" method="POST">
                    @csrf
                    
                    <!-- Course Selection -->
                    <div class="mb-6">
                        <label for="course_id" class="form-label">Course *</label>
                        <select id="course_id" 
                                name="course_id" 
                                class="form-input @error('course_id') border-red-500 @enderror" 
                                required
                                onchange="updateCoursePreview(this)">
                            <option value="">Select a course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                        data-subject="{{ $course->subject->name }}"
                                        data-color="{{ $course->subject->color }}"
                                        {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>
                                    {{ $course->subject->name }} → {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <div id="coursePreview" class="mt-2 flex items-center text-sm text-gray-600" style="display: none;">
                            <div id="courseColor" class="w-3 h-3 rounded-full mr-2"></div>
                            <span id="coursePath"></span>
                        </div>
                    </div>

                    <!-- Topic Name -->
                    <div class="mb-6">
                        <label for="name" class="form-label">Topic Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               class="form-input @error('name') border-red-500 @enderror"
                               placeholder="e.g., Introduction to Derivatives, Cell Division, The Civil War"
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
                                  placeholder="Brief description of what you'll learn in this topic...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Difficulty and Time Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Difficulty Level -->
                        <div>
                            <label for="difficulty" class="form-label">Difficulty Level</label>
                            <div class="flex items-center space-x-3">
                                <input type="range" 
                                       id="difficulty" 
                                       name="difficulty" 
                                       min="1" 
                                       max="10" 
                                       value="{{ old('difficulty', 5) }}" 
                                       class="flex-1"
                                       oninput="updateDifficultyDisplay(this.value)">
                                <div class="flex items-center space-x-2">
                                    <span id="difficultyValue" class="text-sm font-medium text-gray-700">{{ old('difficulty', 5) }}</span>
                                    <span class="text-sm text-gray-500">/10</span>
                                </div>
                            </div>
                            <div id="difficultyIndicator" class="flex items-center mt-2">
                                @for($i = 1; $i <= 10; $i++)
                                    <div class="w-2 h-2 rounded-full mr-1 difficulty-dot {{ $i <= old('difficulty', 5) ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                @endfor
                            </div>
                            @error('difficulty')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estimated Minutes -->
                        <div>
                            <label for="estimated_minutes" class="form-label">Estimated Study Time</label>
                            <div class="flex items-center">
                                <input type="number" 
                                       id="estimated_minutes" 
                                       name="estimated_minutes" 
                                       value="{{ old('estimated_minutes', 30) }}" 
                                       class="form-input @error('estimated_minutes') border-red-500 @enderror w-32"
                                       min="5" 
                                       max="480"
                                       step="5"
                                       placeholder="30">
                                <span class="ml-2 text-sm text-gray-500">minutes</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Estimated time needed to learn this topic (5-480 min)</p>
                            @error('estimated_minutes')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Prerequisites -->
                    <div class="mb-6">
                        <label for="prerequisites" class="form-label">Prerequisites</label>
                        <div class="space-y-2">
                            @if($availableTopics->count() > 0)
                                <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach($availableTopics->groupBy('course_id') as $courseId => $courseTopics)
                                            @php $course = $courseTopics->first()->course @endphp
                                            <div class="border-b border-gray-100 pb-2 mb-2">
                                                <div class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                                    <div class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $course->subject->color }}"></div>
                                                    {{ $course->subject->name }} → {{ $course->name }}
                                                </div>
                                                <div class="grid grid-cols-1 gap-1 ml-4">
                                                    @foreach($courseTopics as $topic)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                   name="prerequisites[]" 
                                                                   value="{{ $topic->id }}"
                                                                   {{ in_array($topic->id, old('prerequisites', [])) ? 'checked' : '' }}
                                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                                            <span class="ml-2 text-sm text-gray-600">{{ $topic->name }}</span>
                                                            @if($topic->is_completed)
                                                                <svg class="w-4 h-4 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @endif
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Select topics that should be completed before studying this topic</p>
                            @else
                                <div class="text-sm text-gray-500 p-4 border border-gray-200 rounded-md">
                                    No existing topics available as prerequisites. Create some topics first to set up dependencies.
                                </div>
                            @endif
                        </div>
                        @error('prerequisites')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Progress and Status Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Initial Progress -->
                        <div>
                            <label for="progress_percentage" class="form-label">Initial Progress</label>
                            <div class="flex items-center space-x-3">
                                <input type="range" 
                                       id="progress_percentage" 
                                       name="progress_percentage" 
                                       min="0" 
                                       max="100" 
                                       value="{{ old('progress_percentage', 0) }}" 
                                       class="flex-1"
                                       oninput="updateProgressDisplay(this.value)">
                                <div class="flex items-center space-x-2">
                                    <span id="progressValue" class="text-sm font-medium text-gray-700">{{ old('progress_percentage', 0) }}</span>
                                    <span class="text-sm text-gray-500">%</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ old('progress_percentage', 0) }}%"></div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Set if you've already made progress on this topic</p>
                            @error('progress_percentage')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Completion Status -->
                        <div>
                            <label class="form-label">Status</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_completed" 
                                           name="is_completed" 
                                           value="1" 
                                           {{ old('is_completed') ? 'checked' : '' }}
                                           onchange="toggleCompleted(this)"
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_completed" class="ml-2 text-sm font-medium text-gray-700">
                                        Mark as completed
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                        Active topic
                                    </label>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Active topics appear in your dashboard and progress tracking</p>
                            @error('is_completed')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @error('is_active')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('topics.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Topic
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateCoursePreview(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const preview = document.getElementById('coursePreview');
            const colorDiv = document.getElementById('courseColor');
            const pathSpan = document.getElementById('coursePath');
            
            if (selectedOption.value) {
                const color = selectedOption.getAttribute('data-color');
                const subject = selectedOption.getAttribute('data-subject');
                colorDiv.style.backgroundColor = color;
                pathSpan.textContent = selectedOption.textContent;
                preview.style.display = 'flex';
            } else {
                preview.style.display = 'none';
            }
        }

        function updateDifficultyDisplay(value) {
            document.getElementById('difficultyValue').textContent = value;
            
            // Update difficulty dots
            const dots = document.querySelectorAll('.difficulty-dot');
            dots.forEach((dot, index) => {
                if (index < value) {
                    dot.classList.remove('bg-gray-200');
                    dot.classList.add('bg-blue-500');
                } else {
                    dot.classList.remove('bg-blue-500');
                    dot.classList.add('bg-gray-200');
                }
            });
        }

        function updateProgressDisplay(value) {
            document.getElementById('progressValue').textContent = value;
            document.getElementById('progressBar').style.width = value + '%';
            
            // Auto-check completed if 100%
            if (parseInt(value) === 100) {
                document.getElementById('is_completed').checked = true;
            }
        }

        function toggleCompleted(checkbox) {
            if (checkbox.checked) {
                document.getElementById('progress_percentage').value = 100;
                updateProgressDisplay(100);
            }
        }

        // Initialize course preview on page load
        document.addEventListener('DOMContentLoaded', function() {
            const courseSelect = document.getElementById('course_id');
            updateCoursePreview(courseSelect);
        });

        // Form submission handling
        document.getElementById('topicForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>Creating...';
        });
    </script>
</x-app-layout>