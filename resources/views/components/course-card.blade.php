<div {{ $attributes->merge(['class' => 'card hover:shadow-md transition-shadow']) }}>
    <div class="card-body">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <!-- Course Header -->
                <div class="flex items-center mb-3">
                    <div class="w-2 h-2 rounded-full mr-3" style="background-color: {{ $course->subject->color }}"></div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $course->name }}</h3>
                    @if($course->deadline)
                        <div class="ml-auto">
                            <x-deadline-badge :deadline="$course->deadline" />
                        </div>
                    @endif
                </div>

                <!-- Course Description -->
                @if($course->description)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $course->description }}</p>
                @endif

                <!-- Course Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-sm text-gray-600 block mb-1">Estimated Time</span>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">{{ $course->estimated_hours }} hours</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 block mb-1">Priority Level</span>
                        <x-difficulty-meter :value="$course->priority" :max="10" size="sm" />
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <x-progress-bar
                        :value="$course->progress_percentage" 
                        size="md"
                        showLabel="true"
                    />
                </div>
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
                        <a href="{{ route('courses.show', $course) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                        <a href="{{ route('courses.edit', $course) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                        <a href="{{ route('topics.create', ['course_id' => $course->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Topic</a>
                        <hr class="my-1">
                        <button onclick="deleteCourse({{ $course->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>