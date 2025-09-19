<div {{ $attributes->merge(['class' => 'card hover:shadow-md transition-shadow']) }}>
    <div class="card-body">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <!-- Subject Header -->
                <div class="flex items-center mb-3">
                    <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $subject->color ?? '#3b82f6' }}"></div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $subject->name }}</h3>
                    <span class="ml-auto px-2 py-1 rounded-full text-xs {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Subject Description -->
                @if($subject->description)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $subject->description }}</p>
                @endif

                <!-- Subject Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-sm text-gray-600 block mb-1">Difficulty Level</span>
                        <x-difficulty-meter :value="$subject->difficulty_base" :max="10" size="sm" />
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 block mb-1">Courses</span>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <span class="font-medium">{{ $subject->courses->count() }} {{ Str::plural('course', $subject->courses->count()) }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    @if($subject->total_hours_estimated)
                        <div>
                            <span class="text-sm text-gray-600 block mb-1">Estimated Study Time</span>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">{{ $subject->total_hours_estimated }} hours</span>
                            </div>
                        </div>
                    @endif

                    @if($subject->final_exam_date)
                        <div>
                            <span class="text-sm text-gray-600 block mb-1">Final Exam</span>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium text-sm">{{ $subject->final_exam_date->format('M j, Y') }}</span>
                                @php
                                    $daysUntilExam = now()->diffInDays($subject->final_exam_date, false);
                                    $isOverdue = $daysUntilExam < 0;
                                    $isUrgent = $daysUntilExam <= 7 && $daysUntilExam >= 0;
                                @endphp
                                @if($isOverdue)
                                    <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full font-medium">
                                        OVERDUE
                                    </span>
                                @elseif($isUrgent)
                                    <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full font-medium">
                                        {{ abs($daysUntilExam) }}d left
                                    </span>
                                @elseif($daysUntilExam <= 30)
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full font-medium">
                                        {{ $daysUntilExam }}d left
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Progress Tracking -->
                @php
                    $totalTopics = $subject->courses->sum(function($course) { return $course->topics->count(); });
                    $completedTopics = $subject->courses->sum(function($course) { return $course->topics->where('is_completed', true)->count(); });
                    $progressPercent = $totalTopics > 0 ? round(($completedTopics / $totalTopics) * 100) : 0;
                @endphp

                @if($totalTopics > 0)
                    <div>
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-gray-600">Overall Progress</span>
                            <span class="font-medium">{{ $completedTopics }}/{{ $totalTopics }} topics completed</span>
                        </div>
                        <x-progress-bar :value="$progressPercent" size="md" />
                    </div>
                @endif
            </div>

            <!-- Actions Menu -->
            <div class="ml-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="{{ route('subjects.show', $subject) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                        <a href="{{ route('subjects.edit', $subject) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                        <a href="{{ route('courses.create', ['subject_id' => $subject->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Course</a>
                        <hr class="my-1">
                        <button onclick="deleteSubject({{ $subject->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>