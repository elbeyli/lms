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
        <div class="space-y-4" x-data="{ draggedId: null }">
            <!-- Sort by course if no specific course is selected -->
            @if(!request('course_id'))
                @foreach($topics->groupBy('course_id') as $courseTopics)
                    @php $course = $courseTopics->first()->course; @endphp
                    
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-2 rounded-full" style="background-color: {{ $course->subject->color }}"></div>
                            <h2 class="text-xl font-semibold">{{ $course->name }}</h2>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($courseTopics as $topic)
                                <x-topic-card :topic="$topic" />
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                @foreach($topics as $topic)
                    <x-topic-card :topic="$topic" />
                @endforeach
            @endif
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