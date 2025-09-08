<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Topics</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your study topics</p>
            </div>
            <a href="{{ route('topics.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Topic
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="mb-6">
        <form method="GET" class="flex items-center space-x-4 flex-wrap gap-y-2">
            <div class="flex-1 max-w-sm">
                <select name="course_id" onchange="this.form.submit()" class="form-input">
                    <option value="">All Courses</option>
                    @foreach(auth()->user()->subjects()->with(['courses' => function($q) { $q->where('is_active', true)->orderBy('name'); }])->where('is_active', true)->orderBy('name')->get() as $subject)
                        <optgroup label="{{ $subject->name }}">
                            @foreach($subject->courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="active_only" value="1" {{ request('active_only') ? 'checked' : '' }} 
                           onchange="this.form.submit()" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Active only</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="completed_only" value="1" {{ request('completed_only') ? 'checked' : '' }} 
                           onchange="this.form.submit()" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Completed only</span>
                </label>
            </div>
        </form>
    </div>

    @if($topics->count() > 0)
        <div class="space-y-4">
            @foreach($topics as $topic)
                <div class="card hover:shadow-md transition-shadow">
                    <div class="card-body">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Topic Header -->
                                <div class="flex items-center mb-3">
                                    @if($topic->is_completed)
                                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-3"></div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $topic->name }}</h3>
                                        <div class="flex items-center text-sm text-gray-600 mt-1">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $topic->course->subject->color }}"></div>
                                            <a href="{{ route('subjects.show', $topic->course->subject) }}" class="hover:text-blue-600">{{ $topic->course->subject->name }}</a>
                                            <span class="mx-2">→</span>
                                            <a href="{{ route('courses.show', $topic->course) }}" class="hover:text-blue-600">{{ $topic->course->name }}</a>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Difficulty Badge -->
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($topic->difficulty >= 8) bg-red-100 text-red-800
                                            @elseif($topic->difficulty >= 6) bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif
                                        ">
                                            Level {{ $topic->difficulty }}
                                        </span>
                                        <!-- Status Badge -->
                                        <span class="px-2 py-1 rounded-full text-xs {{ $topic->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $topic->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($topic->is_completed)
                                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($topic->description)
                                    <p class="text-sm text-gray-600 mb-4 ml-9 line-clamp-2">{{ $topic->description }}</p>
                                @endif

                                <!-- Topic Stats -->
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4 ml-9">
                                    <div class="text-sm">
                                        <span class="text-gray-500">Est. Time:</span>
                                        <span class="ml-1 font-medium">{{ $topic->estimated_minutes }}min</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-500">Progress:</span>
                                        <span class="ml-1 font-medium">{{ $topic->progress_percentage }}%</span>
                                    </div>
                                    @if($topic->completed_at)
                                        <div class="text-sm">
                                            <span class="text-gray-500">Completed:</span>
                                            <span class="ml-1 font-medium">{{ $topic->completed_at->format('M j, Y') }}</span>
                                        </div>
                                    @endif
                                    @if($topic->prerequisites && count($topic->prerequisites) > 0)
                                        <div class="text-sm">
                                            <span class="text-gray-500">Prerequisites:</span>
                                            <span class="ml-1 font-medium">{{ count($topic->prerequisites) }}</span>
                                        </div>
                                    @endif
                                    <div class="text-sm">
                                        <span class="text-gray-500">Updated:</span>
                                        <span class="ml-1 font-medium">{{ $topic->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @if(!$topic->is_completed)
                                    <div class="mb-4 ml-9">
                                        <div class="flex justify-between items-center text-xs text-gray-500 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $topic->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $topic->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Quick Progress Update -->
                                @if(!$topic->is_completed)
                                    <div class="border-t border-gray-200 pt-4 ml-9">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-600">Quick update:</span>
                                                <div class="flex space-x-1">
                                                    @foreach([25, 50, 75] as $progress)
                                                        @if($topic->progress_percentage < $progress)
                                                            <form action="{{ route('topics.updateProgress', $topic) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="progress" value="{{ $progress }}">
                                                                <button type="submit" class="text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded">
                                                                    {{ $progress }}%
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endforeach
                                                    <form action="{{ route('topics.complete', $topic) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-xs px-2 py-1 bg-green-100 hover:bg-green-200 text-green-800 rounded">
                                                            Complete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
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
                                        <a href="{{ route('topics.show', $topic) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                                        <a href="{{ route('topics.edit', $topic) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Topic</a>
                                        @if(!$topic->is_completed)
                                            <form action="{{ route('topics.complete', $topic) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">Mark as Complete</button>
                                            </form>
                                        @endif
                                        <hr class="my-1">
                                        <button onclick="deleteTopic({{ $topic->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                    </div>
                                </div>
                                <a href="{{ route('topics.show', $topic) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No topics yet</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('course_id'))
                            No topics found for the selected course. Create your first topic to start studying.
                        @elseif(request('completed_only'))
                            No completed topics found. Keep studying to complete your first topic!
                        @else
                            Create your first topic to start organizing your study materials and track your learning progress.
                        @endif
                    </p>
                    <a href="{{ route('topics.create', request()->only('course_id')) }}" class="btn-primary">Create Your First Topic</a>
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Delete Topic</h3>
                    <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete this topic? This action cannot be undone.</p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="cancelDelete()" class="btn-secondary">Cancel</button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Delete Topic</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteTopic(topicId) {
            document.getElementById('deleteForm').action = `/topics/${topicId}`;
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