<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('topics.show', $topic) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Topic</h1>
                <p class="text-sm text-gray-600 mt-1">Update topic details and settings</p>
            </div>
        </div>
    </x-slot>

    <x-topic-form
        :topic="$topic"
        :courses="$courses"
        :action="route('topics.update', $topic)"
    />
</x-app-layout>