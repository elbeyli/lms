<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Study Sessions</h1>
                <p class="text-sm text-gray-600 mt-1">Track your study progress and analytics</p>
            </div>
            <div class="flex space-x-3">
                @if($activeSession)
                    <a href="{{ route('sessions.active', $activeSession) }}" class="btn-success">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5H9m0-5.5v5.5m0-5.5C9 8.672 9.672 8 10.5 8h.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5h-.5a1.5 1.5 0 01-1.5-1.5v-1z"></path>
                        </svg>
                        Resume Active Session
                    </a>
                @else
                    <a href="{{ route('sessions.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Start New Session
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Study Hours</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($analytics['total_study_hours'] ?? 0, 1) }}</p>
                        <p class="text-xs text-gray-500">Last 30 days</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($analytics['completion_rate'] ?? 0, 1) }}%</p>
                        <p class="text-xs text-gray-500">{{ $analytics['completed_sessions'] }}/{{ $analytics['total_sessions'] }} sessions</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Avg. Effectiveness</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $analytics['average_effectiveness'] ? number_format($analytics['average_effectiveness'], 1) : 'N/A' }}<span class="text-sm text-gray-500">/10</span></p>
                        <p class="text-xs text-gray-500">Focus: {{ $analytics['average_focus'] ? number_format($analytics['average_focus'], 1) : 'N/A' }}/10</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Planning Accuracy</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($analytics['planning_accuracy'] ?? 0, 1) }}%</p>
                        <p class="text-xs text-gray-500">Actual vs planned time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions List -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-3">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Sessions</h3>
                        <div class="flex items-center space-x-2">
                            <select class="form-input text-sm" onchange="filterSessions(this.value)">
                                <option value="all">All Sessions</option>
                                <option value="completed">Completed</option>
                                <option value="abandoned">Abandoned</option>
                                <option value="planned">Planned</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($sessions->count() > 0)
                        <div class="space-y-4">
                            @foreach($sessions as $session)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow session-item" data-status="{{ $session->status }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <!-- Session Header -->
                                            <div class="flex items-center space-x-3 mb-2">
                                                @if($session->subject)
                                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $session->subject->color }}"></div>
                                                    <span class="font-medium text-gray-900">{{ $session->subject->name }}</span>
                                                @endif
                                                @if($session->course)
                                                    <span class="text-gray-500">→</span>
                                                    <span class="text-gray-700">{{ $session->course->name }}</span>
                                                @endif
                                                @if($session->topic)
                                                    <span class="text-gray-500">→</span>
                                                    <span class="text-gray-700">{{ $session->topic->name }}</span>
                                                @endif
                                            </div>

                                            <!-- Session Details -->
                                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>
                                                        @if($session->actual_duration)
                                                            {{ $session->actual_duration }} min (planned: {{ $session->planned_duration }} min)
                                                        @else
                                                            {{ $session->planned_duration }} min planned
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span>{{ $session->created_at->format('M j, Y g:i A') }}</span>
                                                </div>
                                                @if($session->effectiveness_rating)
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976-2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                        </svg>
                                                        <span>{{ $session->effectiveness_rating }}/10</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Session Notes -->
                                            @if($session->notes)
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($session->notes, 100) }}</p>
                                            @endif

                                            <!-- Session Metrics -->
                                            @if($session->status === 'completed')
                                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                    @if($session->concepts_completed > 0)
                                                        <span>📚 {{ $session->concepts_completed }} concepts</span>
                                                    @endif
                                                    @if($session->focus_score)
                                                        <span>🎯 Focus: {{ $session->focus_score }}/10</span>
                                                    @endif
                                                    @if($session->break_count > 0)
                                                        <span>☕ {{ $session->break_count }} breaks</span>
                                                    @endif
                                                    @if($session->efficiency_rate)
                                                        <span>⚡ {{ $session->efficiency_rate }}% efficiency</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Status and Actions -->
                                        <div class="flex items-center space-x-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
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

                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                                    <a href="{{ route('sessions.show', $session) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                                                    @if($session->status === 'planned')
                                                        <form action="{{ route('sessions.start', $session) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">Start Session</button>
                                                        </form>
                                                    @elseif($session->status === 'active')
                                                        <a href="{{ route('sessions.active', $session) }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100">Go to Active Session</a>
                                                    @elseif($session->status === 'paused')
                                                        <form action="{{ route('sessions.resume', $session) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-gray-100">Resume Session</button>
                                                        </form>
                                                    @endif
                                                    @if(in_array($session->status, ['completed', 'abandoned', 'planned']))
                                                        <hr class="my-1">
                                                        <form action="{{ route('sessions.destroy', $session) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Are you sure?')" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $sessions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No study sessions yet</h3>
                            <p class="text-gray-600 mb-6">Start your first study session to begin tracking your progress.</p>
                            <a href="{{ route('sessions.create') }}" class="btn-primary">Start Your First Session</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Study Streak -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">🔥 Study Insights</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Average Session:</span>
                            <span class="text-sm font-medium">{{ number_format($analytics['average_session_length'] ?? 0, 0) }} min</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Subjects Studied:</span>
                            <span class="text-sm font-medium">{{ $analytics['subjects_studied'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Sessions:</span>
                            <span class="text-sm font-medium">{{ $analytics['total_sessions'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">⚡ Quick Start</h3>
                </div>
                <div class="card-body space-y-2">
                    <a href="{{ route('sessions.create') }}?duration=25" class="btn-secondary btn-sm w-full">25 min Pomodoro</a>
                    <a href="{{ route('sessions.create') }}?duration=45" class="btn-secondary btn-sm w-full">45 min Focus</a>
                    <a href="{{ route('sessions.create') }}?duration=90" class="btn-secondary btn-sm w-full">90 min Deep Work</a>
                </div>
            </div>

            <!-- Study Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">💡 Analytics Tips</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3 text-sm text-gray-600">
                        @if(($analytics['planning_accuracy'] ?? 0) < 80)
                            <div class="flex items-start space-x-2">
                                <span class="text-yellow-500">⚠️</span>
                                <span>Your planning accuracy is {{ number_format($analytics['planning_accuracy'] ?? 0, 1) }}%. Try adjusting your time estimates.</span>
                            </div>
                        @endif
                        @if(($analytics['completion_rate'] ?? 0) < 70)
                            <div class="flex items-start space-x-2">
                                <span class="text-red-500">📉</span>
                                <span>{{ number_format($analytics['completion_rate'] ?? 0, 1) }}% completion rate. Consider shorter sessions or better planning.</span>
                            </div>
                        @endif
                        @if(($analytics['average_effectiveness'] ?? 0) > 8)
                            <div class="flex items-start space-x-2">
                                <span class="text-green-500">🌟</span>
                                <span>Excellent effectiveness rating! Keep up the great work.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterSessions(status) {
            const items = document.querySelectorAll('.session-item');
            items.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>