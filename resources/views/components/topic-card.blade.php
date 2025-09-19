<div x-data="{ 
    isCompleted: {{ $topic->is_completed ? 'true' : 'false' }},
    progress: {{ $topic->progress_percentage }},
    async toggleCompletion() {
        try {
            const response = await fetch(`/topics/${this.$root.dataset.topicId}/toggle-completion`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            if (!response.ok) throw new Error('Network response was not ok');
            const result = await response.json();
            this.isCompleted = result.is_completed;
            this.progress = result.progress_percentage;
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to update completion status');
        }
    }
}" 
class="card hover:shadow-md transition-shadow" 
:class="{ 'bg-gray-50': isCompleted }"
data-topic-id="{{ $topic->id }}">
    <div class="card-body">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <!-- Topic Header -->
                <div class="flex items-center gap-3 mb-3">
                    <button
                        type="button"
                        class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors duration-200"
                        :class="isCompleted ? 'bg-green-500 border-green-500' : 'border-gray-300 hover:border-blue-500'"
                        @click="toggleCompletion"
                    >
                        <svg
                            class="w-3 h-3 text-white" 
                            :class="{ 'opacity-0': !isCompleted }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>

                    <h3 class="text-lg font-semibold text-gray-900" :class="{ 'line-through text-gray-500': isCompleted }">
                        {{ $topic->name }}
                    </h3>

                    @if($topic->completed_at)
                        <span class="text-sm text-gray-500">
                            Completed {{ $topic->completed_at->diffForHumans() }}
                        </span>
                    @endif
                </div>

                <!-- Topic Description -->
                @if($topic->description)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2" :class="{ 'text-gray-400': isCompleted }">
                        {{ $topic->description }}
                    </p>
                @endif

                <!-- Topic Stats -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <span class="text-sm text-gray-600 block mb-1">Time Required</span>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">{{ $topic->estimated_minutes }} min</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm text-gray-600 block mb-1">Difficulty</span>
                        <x-difficulty-meter :value="$topic->difficulty" :max="10" size="sm" />
                    </div>

                    @if($topic->prerequisites)
                        <div>
                            <span class="text-sm text-gray-600 block mb-1">Prerequisites</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($topic->prerequisites as $prereq)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $prereq }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Progress Bar -->
                <div>
                    <x-progress-bar :value="$topic->progress_percentage" size="md" showLabel="true" />
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
                        <a href="{{ route('topics.show', $topic) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                        <a href="{{ route('topics.edit', $topic) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                        <hr class="my-1">
                        <button onclick="deleteTopic({{ $topic->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>