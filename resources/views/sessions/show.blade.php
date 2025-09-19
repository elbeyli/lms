<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Session Details</h1>
                <p class="text-sm text-gray-600 mt-1">Review your study session performance</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($session->status === 'active')
                    <a href="{{ route('sessions.active', $session) }}" class="btn-success">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5H9m0-5.5v5.5m0-5.5C9 8.672 9.672 8 10.5 8h.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5h-.5a1.5 1.5 0 01-1.5-1.5v-1z"></path>
                        </svg>
                        Go to Active Session
                    </a>
                @elseif($session->status === 'paused')
                    <form action="{{ route('sessions.resume', $session) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-success">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5H9m0-5.5v5.5m0-5.5C9 8.672 9.672 8 10.5 8h.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5h-.5a1.5 1.5 0 01-1.5-1.5v-1z"></path>
                            </svg>
                            Resume Session
                        </button>
                    </form>
                @elseif($session->status === 'planned')
                    <form action="{{ route('sessions.start', $session) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-success">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5H9m0-5.5v5.5m0-5.5C9 8.672 9.672 8 10.5 8h.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5h-.5a1.5 1.5 0 01-1.5-1.5v-1z"></path>
                            </svg>
                            Start Session
                        </button>
                    </form>
                @endif
                <a href="{{ route('sessions.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Sessions
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Session Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Session Overview -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            @if($session->subject)
                                <div class="w-6 h-6 rounded-full" style="background-color: {{ $session->subject->color }}"></div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $session->subject->name }}</h2>
                            @endif
                            @if($session->course)
                                <span class="text-gray-500">→</span>
                                <span class="text-lg text-gray-700">{{ $session->course->name }}</span>
                            @endif
                            @if($session->topic)
                                <span class="text-gray-500">→</span>
                                <span class="text-lg text-gray-700">{{ $session->topic->name }}</span>
                            @endif
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($session->status === 'completed')
                                bg-green-100 text-green-800
                            @elseif($session->status === 'active')
                                bg-blue-100 text-blue-800
                            @elseif($session->status === 'paused')
                                bg-yellow-100 text-yellow-800
                            @elseif($session->status === 'abandoned')
                                bg-red-100 text-red-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif
                        ">
                            {{ ucfirst($session->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Timing Information -->
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">
                                @if($session->actual_duration)
                                    {{ $session->actual_duration }}
                                @else
                                    {{ $session->planned_duration }}
                                @endif
                                <span class="text-sm text-blue-500">min</span>
                            </div>
                            <div class="text-sm text-blue-600 mt-1">
                                @if($session->actual_duration)
                                    Actual Duration
                                    <div class="text-xs text-gray-600">Planned: {{ $session->planned_duration }} min</div>
                                @else
                                    Planned Duration
                                @endif
                            </div>
                        </div>

                        <!-- Effectiveness Rating -->
                        @if($session->effectiveness_rating)
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $session->effectiveness_rating }}<span class="text-sm text-green-500">/10</span>
                                </div>
                                <div class="text-sm text-green-600 mt-1">Effectiveness</div>
                            </div>
                        @endif

                        <!-- Focus Score -->
                        @if($session->focus_score)
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $session->focus_score }}<span class="text-sm text-purple-500">/10</span>
                                </div>
                                <div class="text-sm text-purple-600 mt-1">Focus Level</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Session Timeline -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">📅 Session Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Session Created</div>
                                <div class="text-sm text-gray-600">{{ $session->created_at->format('M j, Y g:i A') }}</div>
                            </div>
                        </div>

                        @if($session->started_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5H9m0-5.5v5.5m0-5.5C9 8.672 9.672 8 10.5 8h.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5h-.5a1.5 1.5 0 01-1.5-1.5v-1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Session Started</div>
                                    <div class="text-sm text-gray-600">{{ $session->started_at->format('M j, Y g:i A') }}</div>
                                </div>
                            </div>
                        @endif

                        @if($session->ended_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Session Ended</div>
                                    <div class="text-sm text-gray-600">{{ $session->ended_at->format('M j, Y g:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Session Notes -->
            @if($session->notes)
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">📝 Session Notes</h3>
                    </div>
                    <div class="card-body">
                        <div class="prose max-w-none">
                            <p class="text-gray-700">{{ $session->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Performance Analysis -->
            @if($session->status === 'completed')
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">📊 Performance Analysis</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Time Accuracy -->
                            @if($session->duration_difference !== null)
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">Time Accuracy</span>
                                        @if($session->duration_difference > 0)
                                            <span class="text-sm text-red-600">+{{ $session->duration_difference }} min</span>
                                        @elseif($session->duration_difference < 0)
                                            <span class="text-sm text-green-600">{{ $session->duration_difference }} min</span>
                                        @else
                                            <span class="text-sm text-green-600">Perfect!</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        @if($session->duration_difference > 0)
                                            Session took {{ $session->duration_difference }} minutes longer than planned
                                        @elseif($session->duration_difference < 0)
                                            Session finished {{ abs($session->duration_difference) }} minutes early
                                        @else
                                            Session completed exactly as planned
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Efficiency Rate -->
                            @if($session->efficiency_rate)
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">Efficiency Rate</span>
                                        <span class="text-sm font-bold
                                            @if($session->efficiency_rate >= 90) text-green-600
                                            @elseif($session->efficiency_rate >= 75) text-yellow-600
                                            @else text-red-600 @endif
                                        ">{{ $session->efficiency_rate }}%</span>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        Based on planned vs actual time ratio
                                    </div>
                                </div>
                            @endif

                            <!-- Concepts Completed -->
                            @if($session->concepts_completed > 0)
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">Concepts Mastered</span>
                                        <span class="text-sm font-bold text-blue-600">{{ $session->concepts_completed }}</span>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        @if($session->actual_duration > 0)
                                            {{ round($session->concepts_completed / ($session->actual_duration / 60), 1) }} concepts per hour
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Break Analysis -->
                            @if($session->break_count > 0)
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">Breaks Taken</span>
                                        <span class="text-sm font-bold text-purple-600">{{ $session->break_count }}</span>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        @if($session->actual_duration > 0)
                                            {{ round($session->actual_duration / ($session->break_count + 1), 1) }} min per focus block
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">📈 Quick Stats</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            <span class="text-sm font-medium">{{ ucfirst($session->status) }}</span>
                        </div>
                        @if($session->started_at && $session->ended_at)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Duration:</span>
                                <span class="text-sm font-medium">{{ $session->started_at->diffInMinutes($session->ended_at) }} min</span>
                            </div>
                        @endif
                        @if($session->concepts_completed > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Concepts:</span>
                                <span class="text-sm font-medium">{{ $session->concepts_completed }}</span>
                            </div>
                        @endif
                        @if($session->break_count > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Breaks:</span>
                                <span class="text-sm font-medium">{{ $session->break_count }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Links -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">🔗 Related</h3>
                </div>
                <div class="card-body space-y-2">
                    @if($session->topic)
                        <a href="{{ route('topics.show', $session->topic) }}" class="btn-secondary btn-sm w-full">
                            View Topic: {{ Str::limit($session->topic->name, 20) }}
                        </a>
                    @endif
                    @if($session->course)
                        <a href="{{ route('courses.show', $session->course) }}" class="btn-secondary btn-sm w-full">
                            View Course: {{ Str::limit($session->course->name, 20) }}
                        </a>
                    @endif
                    @if($session->subject)
                        <a href="{{ route('subjects.show', $session->subject) }}" class="btn-secondary btn-sm w-full">
                            View Subject: {{ Str::limit($session->subject->name, 20) }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Session Actions -->
            @if(in_array($session->status, ['completed', 'abandoned']))
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">⚡ Actions</h3>
                    </div>
                    <div class="card-body space-y-2">
                        <a href="{{ route('sessions.create', [
                            'subject_id' => $session->subject_id,
                            'course_id' => $session->course_id,
                            'topic_id' => $session->topic_id,
                            'duration' => $session->planned_duration
                        ]) }}" class="btn-primary btn-sm w-full">
                            Create Similar Session
                        </a>
                        @if($session->status === 'completed')
                            <a href="{{ route('sessions.create') }}" class="btn-secondary btn-sm w-full">
                                Start New Session
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Study Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">💡 Insights</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3 text-sm text-gray-600">
                        @if($session->status === 'completed')
                            @if($session->efficiency_rate && $session->efficiency_rate > 100)
                                <div class="flex items-start space-x-2">
                                    <span class="text-blue-500">💡</span>
                                    <span>This session took longer than planned. Consider breaking down complex topics into smaller chunks.</span>
                                </div>
                            @elseif($session->efficiency_rate && $session->efficiency_rate > 90)
                                <div class="flex items-start space-x-2">
                                    <span class="text-green-500">🎯</span>
                                    <span>Excellent time management! Your planning is very accurate.</span>
                                </div>
                            @endif

                            @if($session->effectiveness_rating && $session->effectiveness_rating >= 8)
                                <div class="flex items-start space-x-2">
                                    <span class="text-green-500">⭐</span>
                                    <span>High effectiveness score! This subject or topic seems to work well for you.</span>
                                </div>
                            @elseif($session->effectiveness_rating && $session->effectiveness_rating <= 5)
                                <div class="flex items-start space-x-2">
                                    <span class="text-yellow-500">💭</span>
                                    <span>Lower effectiveness. Consider changing study environment or taking more breaks.</span>
                                </div>
                            @endif

                            @if($session->break_count === 0 && $session->actual_duration > 45)
                                <div class="flex items-start space-x-2">
                                    <span class="text-yellow-500">⏱️</span>
                                    <span>No breaks in a {{ $session->actual_duration }}-minute session. Regular breaks can improve focus.</span>
                                </div>
                            @endif
                        @elseif($session->status === 'abandoned')
                            <div class="flex items-start space-x-2">
                                <span class="text-blue-500">🔄</span>
                                <span>Don't worry about abandoned sessions. Use them as learning opportunities to improve planning.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>