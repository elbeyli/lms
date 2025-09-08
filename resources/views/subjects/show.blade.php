<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('subjects.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full mr-4" style="background-color: {{ $subject->color }}"></div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $subject->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">
                            Created {{ $subject->created_at->format('M j, Y') }}
                            @if($subject->updated_at != $subject->created_at)
                                • Updated {{ $subject->updated_at->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('courses.create', ['subject_id' => $subject->id]) }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Course
                </a>
                <a href="{{ route('subjects.edit', $subject) }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Subject
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Subject Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Description -->
            @if($subject->description)
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Description</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-700 whitespace-pre-line">{{ $subject->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Courses -->
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Courses ({{ $subject->courses->count() }})</h3>
                        <a href="{{ route('courses.create', ['subject_id' => $subject->id]) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Add Course
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($subject->courses->count() > 0)
                        <div class="space-y-4">
                            @foreach($subject->courses as $course)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $course->name }}</h4>
                                            @if($course->description)
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $course->description }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                                View →
                                            </a>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Priority:</span>
                                            <span class="ml-1 font-medium">{{ $course->priority }}/10</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Topics:</span>
                                            <span class="ml-1 font-medium">{{ $course->topics->count() }}</span>
                                        </div>
                                        @if($course->deadline)
                                            <div>
                                                <span class="text-gray-500">Deadline:</span>
                                                <span class="ml-1 font-medium">{{ $course->deadline->format('M j') }}</span>
                                            </div>
                                        @endif
                                        @if($course->estimated_hours)
                                            <div>
                                                <span class="text-gray-500">Est. Hours:</span>
                                                <span class="ml-1 font-medium">{{ $course->estimated_hours }}h</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($course->topics->count() > 0)
                                        <div class="mt-4 pt-3 border-t border-gray-100">
                                            @php
                                                $completedTopics = $course->topics->where('is_completed', true)->count();
                                                $totalTopics = $course->topics->count();
                                                $progressPercent = $totalTopics > 0 ? round(($completedTopics / $totalTopics) * 100) : 0;
                                            @endphp
                                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                                <span>Progress</span>
                                                <span>{{ $completedTopics }}/{{ $totalTopics }} topics completed</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No courses yet</h4>
                            <p class="text-gray-600 mb-4">Add your first course to start organizing topics and study materials.</p>
                            <a href="{{ route('courses.create', ['subject_id' => $subject->id]) }}" class="btn-primary">Add First Course</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Subject Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Subject Details</h3>
                </div>
                <div class="card-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 rounded-full text-xs {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Difficulty Level</dt>
                            <dd class="mt-1 flex items-center">
                                @for($i = 1; $i <= 10; $i++)
                                    <div class="w-2 h-2 rounded-full mr-1 {{ $i <= $subject->difficulty_base ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                @endfor
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $subject->difficulty_base }}/10</span>
                            </dd>
                        </div>

                        @if($subject->total_hours_estimated)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estimated Hours</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">{{ $subject->total_hours_estimated }} hours</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Color Theme</dt>
                            <dd class="mt-1 flex items-center">
                                <div class="w-6 h-6 rounded border border-gray-300 mr-2" style="background-color: {{ $subject->color }}"></div>
                                <span class="text-sm font-mono text-gray-700">{{ $subject->color }}</span>
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
                            <dt class="text-sm text-gray-500">Total Courses</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $subject->courses->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Active Courses</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $subject->courses->where('is_active', true)->count() }}</dd>
                        </div>
                        @php
                            $totalTopics = $subject->courses->sum(function($course) { return $course->topics->count(); });
                            $completedTopics = $subject->courses->sum(function($course) { return $course->topics->where('is_completed', true)->count(); });
                        @endphp
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Total Topics</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $totalTopics }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Completed Topics</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $completedTopics }}</dd>
                        </div>
                        @if($totalTopics > 0)
                            <div class="pt-2 border-t border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <dt class="text-sm text-gray-500">Overall Progress</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ round(($completedTopics / $totalTopics) * 100) }}%</dd>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ round(($completedTopics / $totalTopics) * 100) }}%"></div>
                                </div>
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
                        <a href="{{ route('courses.create', ['subject_id' => $subject->id]) }}" class="block w-full btn-primary text-center">
                            Add New Course
                        </a>
                        <a href="{{ route('courses.index', ['subject_id' => $subject->id]) }}" class="block w-full btn-secondary text-center">
                            View All Courses
                        </a>
                        <a href="{{ route('subjects.edit', $subject) }}" class="block w-full btn-secondary text-center">
                            Edit Subject
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>