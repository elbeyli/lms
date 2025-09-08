<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('subjects.show', $subject) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Subject</h1>
                <p class="text-sm text-gray-600 mt-1">Update {{ $subject->name }} details</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form id="subjectForm" action="{{ route('subjects.update', $subject) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Subject Name -->
                    <div class="mb-6">
                        <label for="name" class="form-label">Subject Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $subject->name) }}" 
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
                                  placeholder="Brief description of what this subject covers...">{{ old('description', $subject->description) }}</textarea>
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
                                       value="{{ old('color', $subject->color) }}" 
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer"
                                       onchange="updateColorPreview(this.value)">
                                <div class="flex-1">
                                    <input type="text" 
                                           id="colorText" 
                                           value="{{ old('color', $subject->color) }}" 
                                           class="form-input text-sm"
                                           pattern="^#[0-9A-Fa-f]{6}$"
                                           placeholder="#3B82F6"
                                           onchange="updateColorPicker(this.value)">
                                </div>
                                <div id="colorPreview" class="w-10 h-10 rounded border border-gray-300" style="background-color: {{ old('color', $subject->color) }}"></div>
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
                                       value="{{ old('difficulty_base', $subject->difficulty_base) }}" 
                                       class="flex-1"
                                       oninput="updateDifficultyDisplay(this.value)">
                                <div class="flex items-center space-x-2">
                                    <span id="difficultyValue" class="text-sm font-medium text-gray-700">{{ old('difficulty_base', $subject->difficulty_base) }}</span>
                                    <span class="text-sm text-gray-500">/10</span>
                                </div>
                            </div>
                            <div id="difficultyIndicator" class="flex items-center mt-2">
                                @for($i = 1; $i <= 10; $i++)
                                    <div class="w-3 h-3 rounded-full mr-1 difficulty-dot {{ $i <= old('difficulty_base', $subject->difficulty_base) ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
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
                                   value="{{ old('total_hours_estimated', $subject->total_hours_estimated) }}" 
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
                                   {{ old('is_active', $subject->is_active) ? 'checked' : '' }}
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
                        <a href="{{ route('subjects.show', $subject) }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card mt-8 border-red-200">
            <div class="card-header border-red-200">
                <h3 class="text-lg font-medium text-red-900">Danger Zone</h3>
                <p class="text-sm text-red-600 mt-1">Irreversible and destructive actions</p>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Delete this subject</h4>
                        <p class="text-sm text-gray-600">This will permanently delete the subject and all associated courses and topics.</p>
                    </div>
                    <button onclick="deleteSubject({{ $subject->id }})" class="btn-danger">
                        Delete Subject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="relative bg-white rounded-lg max-w-lg w-full">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Delete "{{ $subject->name }}"</h3>
                    <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete this subject? This action will also delete all associated courses and topics. This cannot be undone.</p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="cancelDelete()" class="btn-secondary">Cancel</button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Delete Subject</button>
                        </form>
                    </div>
                </div>
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

        function deleteSubject(subjectId) {
            document.getElementById('deleteForm').action = `/subjects/${subjectId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function cancelDelete() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Form submission handling
        document.getElementById('subjectForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>Updating...';
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cancelDelete();
            }
        });
    </script>
</x-app-layout>