<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('topics.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full mr-4" style="background-color: {{ $topic->course->subject->color }}"></div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $topic->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">
                            <a href="{{ route('subjects.show', $topic->course->subject) }}" class="hover:text-blue-600">{{ $topic->course->subject->name }}</a>
                            →
                            <a href="{{ route('courses.show', $topic->course) }}" class="hover:text-blue-600">{{ $topic->course->name }}</a>
                            • Created {{ $topic->created_at->format('M j, Y') }}
                            @if($topic->updated_at != $topic->created_at)
                                • Updated {{ $topic->updated_at->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if(!$topic->is_completed)
                    <button onclick="markComplete({{ $topic->id }})" class="btn-secondary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mark Complete
                    </button>
                @endif
                <a href="{{ route('topics.edit', $topic) }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Topic
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Topic Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Description -->
            @if($topic->description)
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Description</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-700 whitespace-pre-line">{{ $topic->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Prerequisites -->
            @if($topic->prerequisites && count($topic->prerequisites) > 0)
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Prerequisites</h3>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-wrap gap-2">
                            @foreach($topic->prerequisites as $prerequisite)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                    {{ $prerequisite }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Study Notes Section -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Study Notes</h3>
                </div>
                <div class="card-body">
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Notes feature coming soon...</p>
                    </div>
                </div>
            </div>

            <!-- Progress Tracking -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Progress Tracking</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">Current Progress</span>
                            <span class="font-medium text-gray-900">{{ $topic->progress_percentage }}%</span>
                        </div>
                        <x-progress-bar :value="$topic->progress_percentage" size="lg" />
                    </div>

                    @if(!$topic->is_completed)
                        <div class="flex items-center space-x-2">
                            <button onclick="updateProgress({{ $topic->id }}, {{ max(0, $topic->progress_percentage - 10) }})" class="btn-secondary btn-sm">-10%</button>
                            <button onclick="updateProgress({{ $topic->id }}, {{ min(100, $topic->progress_percentage + 10) }})" class="btn-secondary btn-sm">+10%</button>
                            <button onclick="updateProgress({{ $topic->id }}, {{ min(100, $topic->progress_percentage + 25) }})" class="btn-secondary btn-sm">+25%</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Topic Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Topic Details</h3>
                </div>
                <div class="card-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course</dt>
                            <dd class="mt-1">
                                <a href="{{ route('courses.show', $topic->course) }}" class="flex items-center text-sm hover:text-blue-600">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $topic->course->subject->color }}"></div>
                                    {{ $topic->course->name }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($topic->is_completed)
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs {{ $topic->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $topic->is_active ? 'In Progress' : 'Inactive' }}
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Difficulty Level</dt>
                            <dd class="mt-1">
                                <x-difficulty-meter :value="$topic->difficulty" :max="10" size="sm" />
                                <span class="text-sm text-gray-600 ml-2">{{ $topic->difficulty }}/10</span>
                            </dd>
                        </div>

                        @if($topic->estimated_minutes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estimated Time</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">
                                    @if($topic->estimated_minutes >= 60)
                                        {{ number_format($topic->estimated_minutes / 60, 1) }} hours
                                    @else
                                        {{ $topic->estimated_minutes }} minutes
                                    @endif
                                </dd>
                            </div>
                        @endif

                        @if($topic->completed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Completed</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">
                                    {{ $topic->completed_at->format('M j, Y \a\t g:i A') }}
                                    <span class="block text-xs text-gray-500 mt-1">
                                        {{ $topic->completed_at->diffForHumans() }}
                                    </span>
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Progress</dt>
                            <dd class="mt-1">
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-900">{{ $topic->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $topic->progress_percentage }}%"></div>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @if(!$topic->is_completed)
                            <button onclick="markComplete({{ $topic->id }})" class="block w-full btn-primary text-center">
                                Mark as Complete
                            </button>
                        @endif
                        <a href="{{ route('topics.edit', $topic) }}" class="block w-full btn-secondary text-center">
                            Edit Topic
                        </a>
                        <a href="{{ route('courses.show', $topic->course) }}" class="block w-full btn-secondary text-center">
                            View Course
                        </a>
                        <a href="{{ route('topics.index', ['course_id' => $topic->course_id]) }}" class="block w-full btn-secondary text-center">
                            All Course Topics
                        </a>
                        <button onclick="deleteTopic({{ $topic->id }})" class="block w-full text-red-600 hover:text-red-700 text-center border border-red-200 hover:border-red-300 rounded-md px-3 py-2 text-sm">
                            Delete Topic
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function updateProgress(topicId, newProgress) {
            try {
                const response = await fetch(`/topics/${topicId}/progress`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ progress_percentage: newProgress })
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Failed to update progress');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update progress');
            }
        }

        async function markComplete(topicId) {
            try {
                const response = await fetch(`/topics/${topicId}/toggle-completion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Failed to mark as complete');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to mark as complete');
            }
        }

        function deleteTopic(topicId) {
            if (confirm('Are you sure you want to delete this topic? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/topics/${topicId}`;

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = document.querySelector('meta[name="csrf-token"]').content;

                form.appendChild(methodField);
                form.appendChild(tokenField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>