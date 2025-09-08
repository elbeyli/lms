<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('subjects.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Create New Subject</h1>
                <p class="text-sm text-gray-600 mt-1">Add a new subject to organize your studies</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form id="subjectForm" action="{{ route('subjects.store') }}" method="POST">
                    @csrf
                    
                    <!-- Subject Name -->
                    <div class="mb-6">
                        <label for="name" class="form-label">Subject Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               class="form-input @error('name') border-red-500 @enderror"
                               placeholder="e.g., Mathematics, Biology, History"
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
                                  placeholder="Brief description of what this subject covers...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color and Difficulty Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Color Picker -->
                        <div>
                            <label for="color" class="form-label">Color Theme</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" 
                                       id="color" 
                                       name="color" 
                                       value="{{ old('color', '#3B82F6') }}" 
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer"
                                       onchange="updateColorPreview(this.value)">
                                <div class="flex-1">
                                    <input type="text" 
                                           id="colorText" 
                                           value="{{ old('color', '#3B82F6') }}" 
                                           class="form-input text-sm"
                                           pattern="^#[0-9A-Fa-f]{6}$"
                                           placeholder="#3B82F6"
                                           onchange="updateColorPicker(this.value)">
                                </div>
                                <div id="colorPreview" class="w-10 h-10 rounded border border-gray-300" style="background-color: {{ old('color', '#3B82F6') }}"></div>
                            </div>
                            @error('color')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Difficulty Level -->
                        <div>
                            <label for="difficulty_base" class="form-label">Base Difficulty Level</label>
                            <div class="flex items-center space-x-3">
                                <input type="range" 
                                       id="difficulty_base" 
                                       name="difficulty_base" 
                                       min="1" 
                                       max="10" 
                                       value="{{ old('difficulty_base', 5) }}" 
                                       class="flex-1"
                                       oninput="updateDifficultyDisplay(this.value)">
                                <div class="flex items-center space-x-2">
                                    <span id="difficultyValue" class="text-sm font-medium text-gray-700">{{ old('difficulty_base', 5) }}</span>
                                    <span class="text-sm text-gray-500">/10</span>
                                </div>
                            </div>
                            <div id="difficultyIndicator" class="flex items-center mt-2">
                                @for($i = 1; $i <= 10; $i++)
                                    <div class="w-3 h-3 rounded-full mr-1 difficulty-dot {{ $i <= old('difficulty_base', 5) ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                @endfor
                            </div>
                            @error('difficulty_base')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Estimated Hours -->
                    <div class="mb-6">
                        <label for="total_hours_estimated" class="form-label">Estimated Total Hours</label>
                        <div class="flex items-center">
                            <input type="number" 
                                   id="total_hours_estimated" 
                                   name="total_hours_estimated" 
                                   value="{{ old('total_hours_estimated') }}" 
                                   class="form-input @error('total_hours_estimated') border-red-500 @enderror w-32"
                                   min="1" 
                                   placeholder="40">
                            <span class="ml-2 text-sm text-gray-500">hours (optional)</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Rough estimate of total study time needed for this subject</p>
                        @error('total_hours_estimated')
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
                                Active Subject
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Active subjects appear in your dashboard and can have courses added</p>
                        @error('is_active')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('subjects.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateColorPreview(colorValue) {
            document.getElementById('colorPreview').style.backgroundColor = colorValue;
            document.getElementById('colorText').value = colorValue;
            document.getElementById('color').value = colorValue;
        }

        function updateColorPicker(textValue) {
            if (/^#[0-9A-Fa-f]{6}$/.test(textValue)) {
                document.getElementById('color').value = textValue;
                document.getElementById('colorPreview').style.backgroundColor = textValue;
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

        // Form submission handling
        document.getElementById('subjectForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>Creating...';
        });
    </script>
</x-app-layout>