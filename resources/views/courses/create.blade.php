<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Create New Course</h1>
                <p class="text-sm text-gray-600 mt-1">Add a new course to track your studies</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form id="courseForm" action="{{ route('courses.store') }}" method="POST">
                    @csrf
                    
                    <!-- Subject Selection -->
                    <div class="mb-6">
                        <label for="subject_id" class="form-label">Subject *</label>
                        <select id="subject_id" 
                                name="subject_id" 
                                class="form-input @error('subject_id') border-red-500 @enderror" 
                                required
                                onchange="updateSubjectPreview(this)">
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" 
                                        data-color="{{ $subject->color }}"
                                        {{ old('subject_id', $selectedSubjectId) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <div id="subjectPreview" class="mt-2 flex items-center text-sm text-gray-600" style="display: none;">
                            <div id="subjectColor" class="w-3 h-3 rounded-full mr-2"></div>
                            <span id="subjectName"></span>
                        </div>
                    </div>

                    <!-- Course Name -->
                    <div class="mb-6">
                        <label for="name" class="form-label">Course Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               class="form-input @error('name') border-red-500 @enderror"
                               placeholder="e.g., Calculus I, Cell Biology, American History"
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
                                  placeholder="Brief description of what this course covers...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority and Hours Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Priority Level -->
                        <div>
                            <label for="priority" class="form-label">Priority Level</label>
                            <div class="flex items-center space-x-3">
                                <input type="range" 
                                       id="priority" 
                                       name="priority" 
                                       min="1" 
                                       max="10" 
                                       value="{{ old('priority', 5) }}" 
                                       class="flex-1"
                                       oninput="updatePriorityDisplay(this.value)">
                                <div class="flex items-center space-x-2">
                                    <span id="priorityValue" class="text-sm font-medium text-gray-700">{{ old('priority', 5) }}</span>
                                    <span class="text-sm text-gray-500">/10</span>
                                </div>
                            </div>
                            <div id="priorityLabel" class="text-xs mt-1">
                                @php
                                    $priorityLabels = [
                                        1 => 'Very Low', 2 => 'Low', 3 => 'Low', 4 => 'Below Normal',
                                        5 => 'Normal', 6 => 'Above Normal', 7 => 'High', 8 => 'High',
                                        9 => 'Very High', 10 => 'Critical'
                                    ];
                                @endphp
                                <span class="priority-label text-gray-500">{{ $priorityLabels[old('priority', 5)] }}</span>
                            </div>
                            @error('priority')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estimated Hours -->
                        <div>
                            <label for="estimated_hours" class="form-label">Estimated Hours</label>
                            <div class="flex items-center">
                                <input type="number" 
                                       id="estimated_hours" 
                                       name="estimated_hours" 
                                       value="{{ old('estimated_hours') }}" 
                                       class="form-input @error('estimated_hours') border-red-500 @enderror w-32"
                                       min="1" 
                                       placeholder="20">
                                <span class="ml-2 text-sm text-gray-500">hours (optional)</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Rough estimate of study time needed</p>
                            @error('estimated_hours')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deadline -->
                    <div class="mb-6">
                        <label for="deadline" class="form-label">Deadline</label>
                        <div class="flex items-center">
                            <input type="datetime-local" 
                                   id="deadline" 
                                   name="deadline" 
                                   value="{{ old('deadline') }}" 
                                   class="form-input @error('deadline') border-red-500 @enderror"
                                   min="{{ date('Y-m-d\TH:i') }}">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Optional deadline for completion (must be in the future)</p>
                        @error('deadline')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Progress Percentage -->
                    <div class="mb-6">
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
                        <p class="text-xs text-gray-500 mt-1">Set if you've already made progress on this course</p>
                        @error('progress_percentage')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="mb-8">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                Active Course
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Active courses appear in your dashboard and can have topics added</p>
                        @error('is_active')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('courses.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const priorityLabels = {
            1: 'Very Low', 2: 'Low', 3: 'Low', 4: 'Below Normal',
            5: 'Normal', 6: 'Above Normal', 7: 'High', 8: 'High',
            9: 'Very High', 10: 'Critical'
        };

        function updateSubjectPreview(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const preview = document.getElementById('subjectPreview');
            const colorDiv = document.getElementById('subjectColor');
            const nameSpan = document.getElementById('subjectName');
            
            if (selectedOption.value) {
                const color = selectedOption.getAttribute('data-color');
                colorDiv.style.backgroundColor = color;
                nameSpan.textContent = selectedOption.textContent;
                preview.style.display = 'flex';
            } else {
                preview.style.display = 'none';
            }
        }

        function updatePriorityDisplay(value) {
            document.getElementById('priorityValue').textContent = value;
            document.querySelector('.priority-label').textContent = priorityLabels[parseInt(value)];
        }

        function updateProgressDisplay(value) {
            document.getElementById('progressValue').textContent = value;
            document.getElementById('progressBar').style.width = value + '%';
        }

        // Initialize subject preview on page load
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelect = document.getElementById('subject_id');
            updateSubjectPreview(subjectSelect);
        });

        // Form submission handling
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>Creating...';
        });
    </script>
</x-app-layout>