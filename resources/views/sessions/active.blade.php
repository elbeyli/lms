<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Active Study Session</h1>
                <p class="text-sm text-gray-600 mt-1">Stay focused and track your progress</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">Session ID: #{{ $session->id }}</span>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="sessionTimer({{ $session->id }}, {{ $elapsedMinutes }}, {{ $session->planned_duration }})">
        <!-- Main Timer Interface -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Session Header -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if($session->subject)
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $session->subject->color }}"></div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $session->subject->name }}</h3>
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
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            Active
                        </span>
                    </div>

                    @if($session->notes)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-sm text-blue-800"><strong>Session Goal:</strong> {{ $session->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timer Display -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-6">
                        <div class="text-6xl font-mono font-bold text-gray-900 mb-2" x-text="formatTime(elapsedSeconds)"></div>
                        <div class="text-lg text-gray-600">
                            <span x-text="Math.floor(elapsedSeconds / 60)"></span> of <span>{{ $session->planned_duration }}</span> minutes
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress</span>
                            <span x-text="Math.round((elapsedSeconds / 60 / {{ $session->planned_duration }}) * 100) + '%'"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-500 h-3 rounded-full transition-all duration-1000"
                                 :style="`width: ${Math.min(100, (elapsedSeconds / 60 / {{ $session->planned_duration }}) * 100)}%`">
                            </div>
                        </div>
                    </div>

                    <!-- Timer Controls -->
                    <div class="flex justify-center space-x-4">
                        <button onclick="pauseSessionGlobal()" class="btn-secondary">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pause
                        </button>
                        <button onclick="showCompletionModal()" class="btn-success">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Complete Session
                        </button>
                        <button onclick="abandonSessionGlobal()" class="btn-danger">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Abandon
                        </button>
                    </div>
                </div>
            </div>

            <!-- Real-time Tracking -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">📊 Session Tracking</h3>
                    <p class="text-sm text-gray-600 mt-1">Track your progress and effectiveness</p>
                </div>
                <div class="card-body">
                    <form id="trackingForm" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Focus Score -->
                            <div>
                                <label class="form-label">Current Focus Level (1-10)</label>
                                <div class="flex items-center space-x-2">
                                    <input type="range" x-model="focusScore" min="1" max="10" class="flex-1 form-range">
                                    <span class="text-sm font-medium min-w-8" x-text="focusScore"></span>
                                </div>
                            </div>

                            <!-- Concepts Completed -->
                            <div>
                                <label class="form-label">Concepts Completed</label>
                                <input type="number" x-model="conceptsCompleted" min="0" class="form-input">
                            </div>
                        </div>

                        <!-- Break Tracking -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <span class="text-sm font-medium text-gray-900">Break Count: </span>
                                <span class="text-lg font-bold text-gray-900" x-text="breakCount"></span>
                            </div>
                            <button type="button" onclick="takeBreakGlobal()" class="btn-secondary btn-sm">
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Take Break
                            </button>
                        </div>

                        <!-- Session Notes -->
                        <div>
                            <label class="form-label">Session Notes</label>
                            <textarea x-model="sessionNotes" rows="3" class="form-input"
                                      placeholder="What are you learning? Any insights or difficulties?"></textarea>
                        </div>

                        <!-- Auto-save indicator -->
                        <div class="text-xs text-gray-500 text-center" x-show="saving">
                            💾 Auto-saving...
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Session Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">📈 Session Stats</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Started:</span>
                            <span class="text-sm font-medium">{{ $session->started_at->format('g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Planned Duration:</span>
                            <span class="text-sm font-medium">{{ $session->planned_duration }} min</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Current Focus:</span>
                            <span class="text-sm font-medium" x-text="focusScore + '/10'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Concepts Done:</span>
                            <span class="text-sm font-medium" x-text="conceptsCompleted"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Breaks Taken:</span>
                            <span class="text-sm font-medium" x-text="breakCount"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motivational Messages -->
            <div class="card">
                <div class="card-body text-center" x-show="elapsedSeconds > 0">
                    <div x-show="elapsedSeconds / 60 < {{ $session->planned_duration }}">
                        <div class="text-2xl mb-2">🎯</div>
                        <p class="text-sm text-gray-600">Stay focused! You're making great progress.</p>
                    </div>
                    <div x-show="elapsedSeconds / 60 >= {{ $session->planned_duration }}">
                        <div class="text-2xl mb-2">🎉</div>
                        <p class="text-sm text-gray-600">Amazing! You've reached your planned duration. Consider wrapping up or extending if you're in flow.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">⚡ Quick Actions</h3>
                </div>
                <div class="card-body space-y-2">
                    @if($session->topic)
                        <a href="{{ route('topics.show', $session->topic) }}" class="btn-secondary btn-sm w-full">
                            View Topic Details
                        </a>
                    @endif
                    @if($session->course)
                        <a href="{{ route('courses.show', $session->course) }}" class="btn-secondary btn-sm w-full">
                            View Course Details
                        </a>
                    @endif
                    @if($session->subject)
                        <a href="{{ route('subjects.show', $session->subject) }}" class="btn-secondary btn-sm w-full">
                            View Subject Details
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Session Completion Modal -->
    <div id="completion-modal"
         class="fixed inset-0 z-50 overflow-y-auto hidden"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeCompletionModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Complete Session</h3>
                <form onsubmit="return submitCompletion(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">How effective was this session? (1-10)</label>
                            <div class="flex items-center space-x-2">
                                <input type="range" id="effectiveness-rating" min="1" max="10" value="7" class="flex-1 form-range" oninput="document.getElementById('effectiveness-display').textContent = this.value">
                                <span id="effectiveness-display" class="text-sm font-medium min-w-8">7</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Final Notes</label>
                            <textarea id="completion-notes" rows="3" class="form-input"
                                      placeholder="How did the session go? What did you accomplish?"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeCompletionModal()" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-success">Complete Session</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function sessionTimer(sessionId, initialElapsed, plannedDuration) {
            return {
                sessionId: sessionId,
                elapsedSeconds: initialElapsed * 60,
                plannedDuration: plannedDuration,
                focusScore: 7,
                conceptsCompleted: 0,
                breakCount: 0,
                sessionNotes: '',
                effectivenessRating: 7,
                completionNotes: '',
                showCompletionModal: false,
                saving: false,
                timer: null,

                init() {
                    this.startTimer();
                    this.loadSessionData();

                    // Auto-save every 30 seconds
                    setInterval(() => this.autoSave(), 30000);

                    // Save on page unload
                    window.addEventListener('beforeunload', () => this.saveSessionData());
                },

                startTimer() {
                    this.timer = setInterval(() => {
                        this.elapsedSeconds++;
                        this.saveToLocalStorage();
                    }, 1000);
                },

                formatTime(seconds) {
                    const hours = Math.floor(seconds / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    const secs = seconds % 60;

                    if (hours > 0) {
                        return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    }
                    return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                },

                async pauseSession() {
                    console.log('Pause session button clicked');
                    if (confirm('Are you sure you want to pause this session?')) {
                        try {
                            const response = await fetch(`/sessions/${this.sessionId}/pause`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok) {
                                clearInterval(this.timer);
                                window.location.href = `/sessions/${this.sessionId}`;
                            }
                        } catch (error) {
                            alert('Failed to pause session. Please try again.');
                        }
                    }
                },



                async abandonSession() {
                    console.log('Abandon session button clicked');
                    const reason = prompt('Why are you abandoning this session? (optional)');
                    if (reason !== null) {
                        try {
                            const response = await fetch(`/sessions/${this.sessionId}/abandon`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ reason })
                            });

                            if (response.ok) {
                                clearInterval(this.timer);
                                this.clearLocalStorage();
                                window.location.href = '/sessions';
                            }
                        } catch (error) {
                            alert('Failed to abandon session. Please try again.');
                        }
                    }
                },

                takeBreak() {
                    this.breakCount++;
                    this.autoSave();

                    // Optional: Show break timer or notification
                    if (confirm('Break time! Recommended break: 5-10 minutes. Click OK when you\'re ready to continue.')) {
                        // User is back from break
                    }
                },

                async autoSave() {
                    this.saving = true;
                    await this.saveSessionData();
                    setTimeout(() => this.saving = false, 1000);
                },

                async saveSessionData() {
                    try {
                        await fetch(`/sessions/${this.sessionId}`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                focus_score: this.focusScore,
                                concepts_completed: this.conceptsCompleted,
                                break_count: this.breakCount,
                                notes: this.sessionNotes,
                                session_data: {
                                    real_time_tracking: {
                                        focus_score: this.focusScore,
                                        concepts_completed: this.conceptsCompleted,
                                        break_count: this.breakCount,
                                        elapsed_seconds: this.elapsedSeconds
                                    }
                                }
                            })
                        });
                    } catch (error) {
                        console.error('Auto-save failed:', error);
                    }
                },

                saveToLocalStorage() {
                    localStorage.setItem(`session_${this.sessionId}`, JSON.stringify({
                        elapsedSeconds: this.elapsedSeconds,
                        focusScore: this.focusScore,
                        conceptsCompleted: this.conceptsCompleted,
                        breakCount: this.breakCount,
                        sessionNotes: this.sessionNotes
                    }));
                },

                loadSessionData() {
                    const saved = localStorage.getItem(`session_${this.sessionId}`);
                    if (saved) {
                        const data = JSON.parse(saved);
                        this.elapsedSeconds = data.elapsedSeconds || this.elapsedSeconds;
                        this.focusScore = data.focusScore || this.focusScore;
                        this.conceptsCompleted = data.conceptsCompleted || this.conceptsCompleted;
                        this.breakCount = data.breakCount || this.breakCount;
                        this.sessionNotes = data.sessionNotes || this.sessionNotes;
                    }
                },

                clearLocalStorage() {
                    localStorage.removeItem(`session_${this.sessionId}`);
                }
            };
        }

        // Global functions for modal and form handling
        function showCompletionModal() {
            const modal = document.getElementById('completion-modal');
            if (modal) {
                modal.style.display = 'block';
                modal.classList.remove('hidden');
            }
        }

        function closeCompletionModal() {
            const modal = document.getElementById('completion-modal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.add('hidden');
            }
        }

        async function submitCompletion(event) {
            event.preventDefault();

            const sessionId = {{ $session->id }};
            const effectivenessRating = document.getElementById('effectiveness-rating').value;
            const completionNotes = document.getElementById('completion-notes').value;

            try {
                const response = await fetch(`/sessions/${sessionId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        effectiveness_rating: parseInt(effectivenessRating),
                        notes: completionNotes
                    })
                });

                if (response.ok) {
                    window.location.href = '/dashboard';
                } else {
                    alert('Error completing session. Please try again.');
                }
            } catch (error) {
                console.error('Error completing session:', error);
                alert('Error completing session. Please try again.');
            }

            return false;
        }

        async function pauseSessionGlobal() {
            const sessionId = {{ $session->id }};

            if (confirm('Are you sure you want to pause this session?')) {
                try {
                    const response = await fetch(`/sessions/${sessionId}/pause`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        window.location.href = `/sessions/${sessionId}`;
                    } else {
                        alert('Failed to pause session. Please try again.');
                    }
                } catch (error) {
                    alert('Failed to pause session. Please try again.');
                }
            }
        }

        async function abandonSessionGlobal() {
            const sessionId = {{ $session->id }};
            const reason = prompt('Why are you abandoning this session? (optional)');

            if (reason !== null) {
                try {
                    const response = await fetch(`/sessions/${sessionId}/abandon`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ reason })
                    });

                    if (response.ok) {
                        window.location.href = '/sessions';
                    } else {
                        alert('Failed to abandon session. Please try again.');
                    }
                } catch (error) {
                    alert('Failed to abandon session. Please try again.');
                }
            }
        }

        function takeBreakGlobal() {
            // Get the Alpine.js component instance to increment break count
            const component = document.querySelector('[x-data]').__x;
            if (component && component.$data) {
                component.$data.breakCount++;
            }

            // Show break confirmation
            if (confirm('Break time! Recommended break: 5-10 minutes. Click OK when you\'re ready to continue.')) {
                // User is back from break - could save data here if needed
            }
        }
    </script>
</x-app-layout>