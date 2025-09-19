<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Courses</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your study courses</p>
            </div>
            <a href="{{ route('courses.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Course
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    @if(request('subject_id') || auth()->user()->subjects()->where('is_active', true)->count() > 1)
        <div class="mb-6">
            <form method="GET" class="flex items-center space-x-4">
                <div class="flex-1 max-w-sm">
                    <select name="subject_id" onchange="this.form.submit()" class="form-input">
                        <option value="">All Subjects</option>
                        @foreach(auth()->user()->subjects()->where('is_active', true)->orderBy('name')->get() as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="active_only" value="1" {{ request('active_only') ? 'checked' : '' }} 
                               onchange="this.form.submit()" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Active only</span>
                    </label>
                </div>
            </form>
        </div>
    @endif

    @if($courses->count() > 0)
        <div class="space-y-6">
            @foreach($courses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="card max-w-lg mx-auto">
                <div class="card-body">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No courses yet</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('subject_id'))
                            No courses found for the selected subject. Create your first course to start adding topics.
                        @else
                            Create your first course to start organizing topics and study materials. Courses help you structure your learning within subjects.
                        @endif
                    </p>
                    <a href="{{ route('courses.create', request()->only('subject_id')) }}" class="btn-primary">Create Your First Course</a>
                </div>
            </div>
        </div>
    @endif

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
                    <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Delete Course</h3>
                    <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete this course? This action will also delete all associated topics. This cannot be undone.</p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="cancelDelete()" class="btn-secondary">Cancel</button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Delete Course</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteCourse(courseId) {
            document.getElementById('deleteForm').action = `/courses/${courseId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function cancelDelete() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cancelDelete();
            }
        });
    </script>
</x-app-layout>