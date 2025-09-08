<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Subjects</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your study subjects</p>
            </div>
            <a href="{{ route('subjects.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Subject
            </a>
        </div>
    </x-slot>

    @if($subjects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($subjects as $subject)
                <div class="card hover:shadow-md transition-shadow">
                    <div class="card-body">
                        <!-- Subject Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $subject->color }}"></div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $subject->name }}</h3>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                        <a href="{{ route('subjects.show', $subject) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                                        <a href="{{ route('subjects.edit', $subject) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                        <a href="{{ route('courses.create', ['subject_id' => $subject->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Course</a>
                                        <hr class="my-1">
                                        <button onclick="deleteSubject({{ $subject->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subject Description -->
                        @if($subject->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $subject->description }}</p>
                        @endif

                        <!-- Subject Stats -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Difficulty Level</span>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 10; $i++)
                                        <div class="w-2 h-2 rounded-full mr-1 {{ $i <= $subject->difficulty_base ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                    @endfor
                                    <span class="ml-2 text-gray-700">{{ $subject->difficulty_base }}/10</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Courses</span>
                                <span class="text-gray-900 font-medium">{{ $subject->courses->count() }}</span>
                            </div>

                            @if($subject->total_hours_estimated)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Estimated Hours</span>
                                    <span class="text-gray-900 font-medium">{{ $subject->total_hours_estimated }}h</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Status</span>
                                <span class="px-2 py-1 rounded-full text-xs {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <a href="{{ route('subjects.show', $subject) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    View Details →
                                </a>
                                @if($subject->courses->count() > 0)
                                    <a href="{{ route('courses.index', ['subject_id' => $subject->id]) }}" class="text-gray-600 hover:text-gray-700 text-sm">
                                        {{ $subject->courses->count() }} {{ Str::plural('course', $subject->courses->count()) }}
                                    </a>
                                @else
                                    <a href="{{ route('courses.create', ['subject_id' => $subject->id]) }}" class="text-gray-500 hover:text-gray-600 text-sm">
                                        Add first course
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="card max-w-lg mx-auto">
                <div class="card-body">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects yet</h3>
                    <p class="text-gray-600 mb-6">Create your first subject to start organizing your studies. Subjects help you categorize your courses and track your learning progress.</p>
                    <a href="{{ route('subjects.create') }}" class="btn-primary">Create Your First Subject</a>
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Delete Subject</h3>
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
        function deleteSubject(subjectId) {
            document.getElementById('deleteForm').action = `/subjects/${subjectId}`;
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