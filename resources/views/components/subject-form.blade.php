@props(['action', 'subject'])

<div class="card">
    <div class="card-body">
        <form id="subjectForm" action="{{ $action }}" method="POST">
            @csrf
            @if($subject->exists)
                @method('PUT')
            @endif
            
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
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color and Difficulty Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Color -->
                <div>
                    <x-color-picker 
                        name="color" 
                        id="color" 
                        :value="old('color', $subject->color ?? '#3b82f6')"
                        label="Color"
                    />
                </div>

                <!-- Difficulty Base -->
                <div x-data="{ difficulty: {{ old('difficulty_base', $subject->difficulty_base ?? 5) }} }">
                    <label for="difficulty_base" class="form-label">Base Difficulty (1-10)</label>
                    <div class="flex items-center gap-4">
                        <input type="range" 
                               id="difficulty_base" 
                               name="difficulty_base" 
                               value="{{ old('difficulty_base', $subject->difficulty_base ?? 5) }}" 
                               min="1" 
                               max="10"
                               class="flex-1 form-range @error('difficulty_base') border-red-500 @enderror"
                               x-model="difficulty">
                        <span class="text-sm font-medium text-gray-700" x-text="difficulty"></span>
                    </div>
                    @error('difficulty_base')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Total Hours and Final Exam Date Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Total Hours Estimated -->
                <div>
                    <label for="total_hours_estimated" class="form-label">Total Hours Estimated</label>
                    <input type="number"
                           id="total_hours_estimated"
                           name="total_hours_estimated"
                           value="{{ old('total_hours_estimated', $subject->total_hours_estimated ?? 0) }}"
                           min="0"
                           step="0.5"
                           class="form-input @error('total_hours_estimated') border-red-500 @enderror">
                    @error('total_hours_estimated')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Final Exam Date -->
                <div>
                    <label for="final_exam_date" class="form-label">Final Exam Date</label>
                    <input type="datetime-local"
                           id="final_exam_date"
                           name="final_exam_date"
                           value="{{ old('final_exam_date', $subject->final_exam_date?->format('Y-m-d\TH:i')) }}"
                           class="form-input @error('final_exam_date') border-red-500 @enderror">
                    @error('final_exam_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('subjects.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">{{ $subject->exists ? 'Update' : 'Create' }} Subject</button>
            </div>
        </form>
    </div>
</div>