<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Start Study Session</h1>
                <p class="text-sm text-gray-600 mt-1">Plan and start a focused study session</p>
            </div>
            <a href="{{ route('sessions.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Sessions
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Session Creation Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Session Details</h3>
                </div>
                <div class="card-body">
                    <form id="sessionForm" action="{{ route('sessions.store') }}" method="POST">
                        @csrf

                        <!-- Smart Recommendations -->
                        @if($recommendations->isNotEmpty())
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-3">🎯 Recommended Study Sessions</h4>
                            <div class="space-y-2">
                                @foreach($recommendations->take(3) as $rec)
                                <button type="button"
                                        onclick="selectRecommendation({{ $rec['subject']->id }}, '{{ $rec['subject']->name }}', {{ $rec['recommended_duration'] }})"
                                        class="w-full text-left p-3 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $rec['subject']->color }}"></div>
                                            <div>
                                                <span class="font-medium text-gray-900">{{ $rec['subject']->name }}</span>
                                                @if($rec['priority_level'] === 'critical')
                                                    <span class="ml-2 text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full">🔥 URGENT</span>
                                                @elseif($rec['priority_level'] === 'high')
                                                    <span class="ml-2 text-xs px-2 py-1 bg-orange-100 text-orange-800 rounded-full">⚡ HIGH</span>
                                                @endif
                                                <div class="text-xs text-gray-600">
                                                    @if($rec['days_until_exam'] <= 3)
                                                        Final exam in {{ $rec['days_until_exam'] }} day{{ $rec['days_until_exam'] === 1 ? '' : 's' }}
                                                    @elseif($rec['days_until_exam'] <= 7)
                                                        Final exam in {{ $rec['days_until_exam'] }} days
                                                    @else
                                                        Final exam: {{ $rec['subject']->final_exam_date->format('M j') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">{{ $rec['recommended_duration'] }} min</div>
                                            <div class="text-xs text-gray-500">Recommended</div>
                                        </div>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <button type="button" onclick="toggleCustomSelection()" class="text-sm text-blue-600 hover:text-blue-700">
                                    Or choose a different study focus →
                                </button>
                            </div>
                        </div>
                        @else
                        <!-- No Recommendations Available -->
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="text-lg">💡</span>
                                <h4 class="text-md font-medium text-gray-900">Ready to Study?</h4>
                            </div>
                            <p class="text-sm text-gray-600">
                                Start by choosing what you'd like to focus on. You can study at any level - pick a subject for broad review,
                                a specific course, or dive deep into a particular topic.
                            </p>
                        </div>
                        @endif

                        <!-- Learning Target Selection -->
                        <div class="mb-6" x-data="sessionCreator()"
                             @if($recommendations->isNotEmpty()) id="customSelection" style="display: none;" @endif>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Choose your study focus</h4>
                            <p class="text-sm text-gray-600 mb-4">Select at any level - subject, course, or specific topic. All are valid ways to focus your study session.</p>

                            <!-- Subject Selection -->
                            <div class="mb-4">
                                <label for="subject_id" class="form-label">
                                    📚 Subject
                                    <span class="text-xs text-gray-500 font-normal">(choose one to start)</span>
                                </label>
                                <select id="subject_id" name="subject_id"
                                        class="form-input @error('subject_id') border-red-500 @enderror"
                                        x-model="selectedSubject"
                                        @change="loadCourses()">
                                    <option value="">Choose a subject to study...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                                data-final-exam="{{ $subject->final_exam_date?->toISOString() }}"
                                                data-color="{{ $subject->color }}">
                                            {{ $subject->name }}
                                            @if($subject->final_exam_date)
                                                (Final: {{ $subject->final_exam_date->format('M j') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Selection -->
                            <div class="mb-4" x-show="courses.length > 0">
                                <label for="course_id" class="form-label">
                                    📖 Course
                                    <span class="text-xs text-gray-500 font-normal">(optional - get more specific)</span>
                                </label>
                                <select id="course_id" name="course_id"
                                        class="form-input @error('course_id') border-red-500 @enderror"
                                        x-model="selectedCourse"
                                        @change="loadTopics()">
                                    <option value="">Study the whole subject, or pick a course...</option>
                                    <template x-for="course in courses" :key="course.id">
                                        <option :value="course.id" x-text="course.name + (course.deadline ? ' (Due: ' + new Date(course.deadline).toLocaleDateString() + ')' : '')"></option>
                                    </template>
                                </select>
                                @error('course_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Topic Selection -->
                            <div class="mb-4" x-show="topics.length > 0">
                                <label for="topic_id" class="form-label">
                                    🎯 Topic
                                    <span class="text-xs text-gray-500 font-normal">(optional - laser focus)</span>
                                </label>
                                <select id="topic_id" name="topic_id"
                                        class="form-input @error('topic_id') border-red-500 @enderror"
                                        x-model="selectedTopic">
                                    <option value="">Study the whole course, or pick a specific topic...</option>
                                    <template x-for="topic in topics" :key="topic.id">
                                        <option :value="topic.id" x-text="topic.name + ' (' + topic.estimated_minutes + ' min est.)'"></option>
                                    </template>
                                </select>
                                @error('topic_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('learning_target')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Duration -->
                        <div class="mb-6">
                            <label for="planned_duration" class="form-label">Planned Duration (minutes)</label>
                            <div class="flex items-center space-x-4">
                                <input type="range"
                                       id="planned_duration"
                                       name="planned_duration"
                                       value="{{ old('planned_duration', 45) }}"
                                       min="15"
                                       max="180"
                                       step="15"
                                       class="flex-1 form-range @error('planned_duration') border-red-500 @enderror"
                                       x-data="{ duration: {{ old('planned_duration', 45) }} }"
                                       x-model="duration">
                                <div class="text-center min-w-20">
                                    <span class="text-lg font-medium text-gray-900" x-text="duration"></span>
                                    <div class="text-xs text-gray-500">minutes</div>
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>15 min</span>
                                <span>3 hours</span>
                            </div>
                            @error('planned_duration')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="form-label">Session Notes (Optional)</label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="form-input @error('notes') border-red-500 @enderror"
                                      placeholder="What do you plan to accomplish in this session?">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('sessions.index') }}" class="btn-secondary">Cancel</a>
                            <button type="button" onclick="submitForm('create')" class="btn-primary">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Session
                            </button>
                            <button type="button" onclick="submitForm('start')" class="btn-success">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5H9m0-5.5v5.5m0-5.5C9 8.672 9.672 8 10.5 8h.5a1.5 1.5 0 011.5 1.5v1a1.5 1.5 0 01-1.5 1.5h-.5a1.5 1.5 0 01-1.5-1.5v-1z"></path>
                                </svg>
                                Create & Start
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deadline Recommendations Sidebar -->
        <div class="space-y-6">
            @if($recommendations->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">📅 Deadline Recommendations</h3>
                        <p class="text-sm text-gray-600 mt-1">Subjects prioritized by final exam dates</p>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            @foreach($recommendations->take(5) as $rec)
                                <div class="p-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors"
                                     onclick="selectSubject({{ $rec['subject']->id }})"
                                     style="border-left: 4px solid {{ $rec['subject']->color }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $rec['subject']->name }}</h4>
                                        <span class="text-xs px-2 py-1 rounded-full font-medium
                                            @if($rec['priority_level'] === 'critical')
                                                bg-red-100 text-red-800
                                            @elseif($rec['priority_level'] === 'high')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($rec['priority_level'] === 'medium')
                                                bg-blue-100 text-blue-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $rec['priority_level'])) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <div>📅 {{ $rec['days_until_exam'] }} days until final</div>
                                        <div>⏱️ {{ $rec['recommended_duration'] }} min recommended</div>
                                        @if($rec['learning_velocity']['session_count'] > 0)
                                            <div>📈 {{ $rec['learning_velocity']['concepts_per_hour'] }} concepts/hour</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Duration Presets -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">⏱️ Quick Duration</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="setDuration(25)" class="btn-secondary text-sm">25 min</button>
                        <button type="button" onclick="setDuration(45)" class="btn-secondary text-sm">45 min</button>
                        <button type="button" onclick="setDuration(60)" class="btn-secondary text-sm">1 hour</button>
                        <button type="button" onclick="setDuration(90)" class="btn-secondary text-sm">1.5 hours</button>
                    </div>
                </div>
            </div>

            <!-- Session Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">💡 Study Tips</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start space-x-2">
                            <span class="text-blue-500">•</span>
                            <span>Focus on subjects with approaching deadlines first</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-blue-500">•</span>
                            <span>Take 5-10 minute breaks every 45-60 minutes</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-blue-500">•</span>
                            <span>Rate your focus and effectiveness after each session</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-blue-500">•</span>
                            <span>Set specific goals for what you want to accomplish</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sessionCreator() {
            return {
                selectedSubject: '',
                selectedCourse: '',
                selectedTopic: '',
                courses: [],
                topics: [],

                async loadCourses() {
                    this.selectedCourse = '';
                    this.selectedTopic = '';
                    this.topics = [];

                    if (!this.selectedSubject) {
                        this.courses = [];
                        return;
                    }

                    try {
                        const response = await fetch(`/api/subjects/${this.selectedSubject}/courses`);
                        if (response.ok) {
                            this.courses = await response.json();
                        }
                    } catch (error) {
                        console.error('Failed to load courses:', error);
                        this.courses = [];
                    }
                },

                async loadTopics() {
                    this.selectedTopic = '';

                    if (!this.selectedCourse) {
                        this.topics = [];
                        return;
                    }

                    try {
                        const response = await fetch(`/api/courses/${this.selectedCourse}/topics`);
                        if (response.ok) {
                            this.topics = await response.json();
                        }
                    } catch (error) {
                        console.error('Failed to load topics:', error);
                        this.topics = [];
                    }
                }
            };
        }

        function selectSubject(subjectId) {
            document.getElementById('subject_id').value = subjectId;
            document.getElementById('subject_id').dispatchEvent(new Event('change'));
        }

        function setDuration(minutes) {
            const slider = document.getElementById('planned_duration');
            slider.value = minutes;
            slider.dispatchEvent(new Event('input'));
        }

        function selectRecommendation(subjectId, subjectName, duration) {
            // Set the subject
            document.getElementById('subject_id').value = subjectId;
            document.getElementById('subject_id').dispatchEvent(new Event('change'));

            // Set the duration
            setDuration(duration);

            // Show a brief confirmation
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = `<div class="text-center"><span class="text-green-600">✓ Selected: ${subjectName}</span></div>`;
            button.classList.add('bg-green-50', 'border-green-200');

            setTimeout(() => {
                button.innerHTML = originalContent;
                button.classList.remove('bg-green-50', 'border-green-200');
            }, 2000);

            // Auto-scroll to duration section
            document.getElementById('planned_duration').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function toggleCustomSelection() {
            const customSection = document.getElementById('customSelection');
            if (customSection) {
                customSection.style.display = customSection.style.display === 'none' ? 'block' : 'none';

                // Scroll to the custom selection if showing
                if (customSection.style.display === 'block') {
                    customSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        }

        // Handle form submission for create vs start actions
        document.getElementById('sessionForm').addEventListener('submit', function(e) {
            console.log('Form submission started');
            console.log('Submitter:', e.submitter);
            console.log('Submitter value:', e.submitter?.value);

            const action = e.submitter.value;
            console.log('Action:', action);

            if (action === 'start') {
                this.action = this.action + '?start=1';
                console.log('Modified action URL:', this.action);
            }

            console.log('Form will submit to:', this.action);
        });

        function submitForm(action) {
            console.log('submitForm called with action:', action);

            const form = document.getElementById('sessionForm');
            const formData = new FormData(form);

            // Get values directly from form elements to bypass Alpine.js
            const subjectId = document.getElementById('subject_id').value;
            const courseId = document.getElementById('course_id').value;
            const topicId = document.getElementById('topic_id').value;
            const plannedDuration = document.getElementById('planned_duration').value;
            const notes = document.getElementById('notes').value;

            console.log('Form data:', {
                subject_id: subjectId,
                course_id: courseId,
                topic_id: topicId,
                planned_duration: plannedDuration,
                notes: notes
            });

            // Check minimum validation
            if (!subjectId && !courseId && !topicId) {
                alert('Please select at least one learning target (subject, course, or topic).');
                return;
            }

            if (!plannedDuration || plannedDuration < 1) {
                alert('Please set a valid planned duration.');
                return;
            }

            // Set the form action based on the button clicked
            if (action === 'start') {
                form.action = form.action.includes('?') ? form.action + '&start=1' : form.action + '?start=1';
            }

            // Create hidden input for action
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);

            console.log('Submitting form to:', form.action);
            form.submit();
        }
    </script>
</x-app-layout>