<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full mr-4" style="background-color: {{ $course->subject->color }}"></div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $course->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">
                            <a href="{{ route('subjects.show', $course->subject) }}" class="hover:text-blue-600">{{ $course->subject->name }}</a>
                            • Created {{ $course->created_at->format('M j, Y') }}
                            @if($course->updated_at != $course->created_at)
                                • Updated {{ $course->updated_at->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('topics.create', ['course_id' => $course->id]) }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Topic
                </a>
                <a href="{{ route('courses.edit', $course) }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Course
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Course Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Description -->
            @if($course->description)
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Description</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-700 whitespace-pre-line">{{ $course->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Topics -->
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Topics ({{ $course->topics->count() }})</h3>
                        <a href="{{ route('topics.create', ['course_id' => $course->id]) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Add Topic
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($course->topics->count() > 0)
                        <div class="space-y-4">
                            @foreach($course->topics as $topic)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                @if($topic->is_completed)
                                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full mr-3"></div>
                                                @endif
                                                <h4 class="font-medium text-gray-900">{{ $topic->name }}</h4>
                                                <div class="ml-auto flex items-center space-x-2">
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
                                                </div>
                                            </div>

                                            @if($topic->description)
                                                <p class="text-sm text-gray-600 mb-3 ml-8 line-clamp-2">{{ $topic->description }}</p>
                                            @endif

                                            <!-- Topic Stats -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm ml-8">
                                                <div>
                                                    <span class="text-gray-500">Est. Time:</span>
                                                    <span class="ml-1 font-medium">{{ $topic->estimated_minutes }}min</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Progress:</span>
                                                    <span class="ml-1 font-medium">{{ $topic->progress_percentage }}%</span>
                                                </div>
                                                @if($topic->completed_at)
                                                    <div>
                                                        <span class="text-gray-500">Completed:</span>
                                                        <span class="ml-1 font-medium">{{ $topic->completed_at->format('M j') }}</span>
                                                    </div>
                                                @endif
                                                @if($topic->prerequisites && count($topic->prerequisites) > 0)
                                                    <div>
                                                        <span class="text-gray-500">Prerequisites:</span>
                                                        <span class="ml-1 font-medium">{{ count($topic->prerequisites) }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Progress Bar -->
                                            @if(!$topic->is_completed)
                                                <div class="mt-3 ml-8">
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $topic->progress_percentage }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Topic Actions -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(!$topic->is_completed && $topic->progress_percentage < 100)
                                                <form action="{{ route('topics.complete', $topic) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-700 text-sm">
                                                        Mark Complete
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('topics.show', $topic) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                                View →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No topics yet</h4>
                            <p class="text-gray-600 mb-4">Add your first topic to start organizing your study materials and track progress.</p>
                            <a href="{{ route('topics.create', ['course_id' => $course->id]) }}" class="btn-primary">Add First Topic</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Course Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Course Details</h3>
                </div>
                <div class="card-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Subject</dt>
                            <dd class="mt-1">
                                <a href="{{ route('subjects.show', $course->subject) }}" class="flex items-center text-sm hover:text-blue-600">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $course->subject->color }}"></div>
                                    {{ $course->subject->name }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 rounded-full text-xs {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Priority Level</dt>
                            <dd class="mt-1 flex items-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($course->priority >= 8) bg-red-100 text-red-800
                                    @elseif($course->priority >= 6) bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif
                                ">
                                    {{ $course->priority }}/10
                                </span>
                                <span class="ml-2 text-sm text-gray-600">
                                    @if($course->priority >= 9) Critical
                                    @elseif($course->priority >= 7) High
                                    @elseif($course->priority >= 4) Normal
                                    @else Low
                                    @endif
                                </span>
                            </dd>
                        </div>

                        @if($course->deadline)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Deadline</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">
                                    {{ $course->deadline->format('M j, Y \a\t g:i A') }}
                                    <span class="block text-xs text-gray-500 mt-1">
                                        @if($course->deadline->isPast())
                                            <span class="text-red-600">Overdue by {{ $course->deadline->diffForHumans() }}</span>
                                        @else
                                            {{ $course->deadline->diffForHumans() }}
                                        @endif
                                    </span>
                                </dd>
                            </div>
                        @endif

                        @if($course->estimated_hours)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estimated Hours</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">{{ $course->estimated_hours }} hours</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Overall Progress</dt>
                            <dd class="mt-1">
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-900">{{ $course->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $course->progress_percentage }}%"></div>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Statistics</h3>
                </div>
                <div class="card-body">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Total Topics</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $course->topics->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Active Topics</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $course->topics->where('is_active', true)->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Completed Topics</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $course->topics->where('is_completed', true)->count() }}</dd>
                        </div>
                        @php
                            $totalMinutes = $course->topics->sum('estimated_minutes');
                            $completedMinutes = $course->topics->where('is_completed', true)->sum('estimated_minutes');
                        @endphp
                        @if($totalMinutes > 0)
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Est. Total Time</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    @if($totalMinutes >= 60)
                                        {{ number_format($totalMinutes / 60, 1) }}h
                                    @else
                                        {{ $totalMinutes }}min
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Time Completed</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    @if($completedMinutes >= 60)
                                        {{ number_format($completedMinutes / 60, 1) }}h
                                    @else
                                        {{ $completedMinutes }}min
                                    @endif
                                </dd>
                            </div>
                        @endif
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
                        <a href="{{ route('topics.create', ['course_id' => $course->id]) }}" class="block w-full btn-primary text-center">
                            Add New Topic
                        </a>
                        <a href="{{ route('topics.index', ['course_id' => $course->id]) }}" class="block w-full btn-secondary text-center">
                            View All Topics
                        </a>
                        <a href="{{ route('courses.edit', $course) }}" class="block w-full btn-secondary text-center">
                            Edit Course
                        </a>
                        <a href="{{ route('subjects.show', $course->subject) }}" class="block w-full btn-secondary text-center">
                            View Subject
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>