<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-600 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('subjects.create') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Subject
                </a>
                <a href="{{ route('courses.create') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    New Course
                </a>
                <a href="{{ route('topics.create') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    New Topic
                </a>
                <a href="{{ route('sessions.create') }}" class="btn-success">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Start Session
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Subjects -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Subjects</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['subjects'] }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['active_subjects'] }} active</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Courses</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['courses'] }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['active_courses'] }} active</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Topics -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Topics</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['topics'] }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['active_topics'] }} active</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Overall Progress</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['overall_progress'] }}%</p>
                        <div class="flex items-center space-x-2">
                            <p class="text-xs text-gray-500">{{ $stats['completed_topics'] }} of {{ $stats['active_topics'] }} completed</p>
                            @if($stats['overall_progress'] > 0)
                                <div class="w-12">
                                    <x-progress-bar :value="$stats['overall_progress']" size="sm" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Study Sessions -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Study Sessions</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['sessions'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['active_sessions'] ?? 0 }} active</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Subjects -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Subjects</h3>
                        <a href="{{ route('subjects.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recent_subjects->count() > 0)
                        <div class="space-y-4">
                            @foreach($recent_subjects as $subject)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $subject->color }}"></div>
                                            <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                                            <x-difficulty-meter :value="$subject->difficulty_base" :max="10" size="sm" :showLabel="false" />
                                        </div>
                                        <a href="{{ route('subjects.show', $subject) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                            View →
                                        </a>
                                    </div>
                                    @if($subject->courses->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($subject->courses as $course)
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-600">{{ $course->name }}</span>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs text-gray-500">
                                                            {{ $course->completed_topics_count }}/{{ $course->topics_count }} topics
                                                        </span>
                                                        @if($course->topics_count > 0)
                                                            @php $courseProgress = round(($course->completed_topics_count / $course->topics_count) * 100); @endphp
                                                            <x-progress-bar :value="$courseProgress" size="sm" class="w-16" />
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">No courses added yet</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No subjects yet</h4>
                            <p class="text-gray-600 mb-4">Create your first subject to start organizing your studies.</p>
                            <a href="{{ route('subjects.create') }}" class="btn-primary">Create Subject</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Upcoming Deadlines -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Upcoming Deadlines</h3>
                </div>
                <div class="card-body">
                    @if($upcoming_deadlines->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcoming_deadlines as $deadline)
                                <div class="flex items-center justify-between p-3 rounded-lg border
                                    @if($deadline['type'] === 'final_exam')
                                        border-purple-200 bg-purple-50
                                    @else
                                        border-gray-200 bg-gray-50
                                    @endif
                                ">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $deadline['subject_color'] }}"></div>
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-medium text-gray-900">{{ $deadline['name'] }}</p>
                                                @if($deadline['type'] === 'final_exam')
                                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-800 rounded-full font-medium">
                                                        FINAL
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $deadline['subject_name'] }} • {{ $deadline['deadline']->format('M j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $daysLeft = $deadline['deadline']->diffInDays();
                                            $isUrgent = $daysLeft <= 3;
                                            $isWarning = $daysLeft <= 7;
                                            $isFinalExam = $deadline['type'] === 'final_exam';
                                        @endphp
                                        <span class="text-xs px-2 py-1 rounded-full font-medium
                                            @if($isUrgent)
                                                bg-red-100 text-red-800
                                            @elseif($isWarning || $isFinalExam)
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif
                                        ">
                                            {{ $deadline['deadline']->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">No upcoming deadlines</p>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                </div>
                <div class="card-body">
                    @if($recent_activity->count() > 0)
                        <div class="space-y-3">
                            @foreach($recent_activity as $topic)
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 rounded-full mt-2" style="background-color: {{ $topic->course->subject->color }}"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">{{ $topic->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $topic->course->subject->name }} → {{ $topic->course->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $topic->updated_at?->diffForHumans() ?? 'Never updated' }}</p>
                                    </div>
                                    @if($topic->is_completed)
                                        <div class="flex-shrink-0">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>