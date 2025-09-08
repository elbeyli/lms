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
                <div class="card hover:shadow-md transition-shadow">
                    <div class="card-body">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Course Header -->
                                <div class="flex items-center mb-3">
                                    <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $course->subject->color }}"></div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $course->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $course->subject->name }}</p>
                                    </div>
                                    <div class="ml-4 flex items-center space-x-2">
                                        <!-- Priority Badge -->
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($course->priority >= 8) bg-red-100 text-red-800
                                            @elseif($course->priority >= 6) bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif
                                        ">
                                            Priority {{ $course->priority }}
                                        </span>
                                        <!-- Status Badge -->
                                        <span class="px-2 py-1 rounded-full text-xs {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $course->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($course->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $course->description }}</p>
                                @endif

                                <!-- Course Stats -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div class="text-sm">
                                        <span class="text-gray-500">Topics:</span>
                                        <span class="ml-1 font-medium text-gray-900">{{ $course->topics->count() }}</span>
                                    </div>
                                    @if($course->deadline)
                                        <div class="text-sm">
                                            <span class="text-gray-500">Deadline:</span>
                                            <span class="ml-1 font-medium text-gray-900">{{ $course->deadline->format('M j, Y') }}</span>
                                            @if($course->deadline->isPast())
                                                <span class="ml-1 text-xs text-red-600">Overdue</span>
                                            @elseif($course->deadline->diffInDays() <= 7)
                                                <span class="ml-1 text-xs text-yellow-600">Due soon</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($course->estimated_hours)
                                        <div class="text-sm">
                                            <span class="text-gray-500">Est. Hours:</span>
                                            <span class="ml-1 font-medium text-gray-900">{{ $course->estimated_hours }}h</span>
                                        </div>
                                    @endif
                                    <div class="text-sm">
                                        <span class="text-gray-500">Progress:</span>
                                        <span class="ml-1 font-medium text-gray-900">{{ $course->progress_percentage }}%</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @if($course->topics->count() > 0)
                                    @php
                                        $completedTopics = $course->topics->where('is_completed', true)->count();
                                        $totalTopics = $course->topics->count();
                                        $progressPercent = round(($completedTopics / $totalTopics) * 100);
                                    @endphp
                                    <div class="mb-4">
                                        <div class="flex justify-between items-center text-xs text-gray-500 mb-1">
                                            <span>Topic Progress</span>
                                            <span>{{ $completedTopics }}/{{ $totalTopics }} completed</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Recent Topics -->
                                @if($course->topics->count() > 0)
                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="text-sm font-medium text-gray-900">Recent Topics</h4>
                                            <a href="{{ route('topics.create', ['course_id' => $course->id]) }}" class="text-xs text-blue-600 hover:text-blue-700">Add Topic</a>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($course->topics->sortByDesc('updated_at')->take(3) as $topic)
                                                <div class="flex items-center justify-between text-sm">
                                                    <div class="flex items-center">
                                                        @if($topic->is_completed)
                                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-2"></div>
                                                        @endif
                                                        <span class="text-gray-700">{{ $topic->name }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        @if(!$topic->is_completed)
                                                            <span class="text-xs text-gray-500">{{ $topic->progress_percentage }}%</span>
                                                        @endif
                                                        <span class="text-xs text-gray-400">{{ $topic->updated_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($course->topics->count() > 3)
                                                <div class="text-xs text-gray-500 text-center pt-2">
                                                    <a href="{{ route('topics.index', ['course_id' => $course->id]) }}" class="hover:text-gray-700">
                                                        View all {{ $course->topics->count() }} topics →
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-4">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                        <a href="{{ route('courses.show', $course) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                                        <a href="{{ route('courses.edit', $course) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Course</a>
                                        <a href="{{ route('topics.create', ['course_id' => $course->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Topic</a>
                                        <a href="{{ route('topics.index', ['course_id' => $course->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Topics</a>
                                        <hr class="my-1">
                                        <button onclick="deleteCourse({{ $course->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                    </div>
                                </div>
                                <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    View →
                                </a>
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