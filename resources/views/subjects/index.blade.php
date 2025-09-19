<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Subjects</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your study subjects</p>
            </div>
            <a href="{{ route('subjects.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Subject
            </a>
        </div>
    </x-slot>

    @if($subjects->count() > 0)
        <div class="space-y-6">
            @foreach($subjects as $subject)
                <x-subject-card :subject="$subject" />
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="card max-w-lg mx-auto">
                <div class="card-body">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects yet</h3>
                    <p class="text-gray-600 mb-6">Create your first subject to start organizing your studies. Subjects help you categorize your courses and track your learning progress.</p>
                    <a href="{{ route('subjects.create') }}" class="btn-primary">Create Your First Subject</a>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="relative bg-white rounded-lg max-w-lg w-full">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Delete Subject</h3>
                    <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete this subject? This action will also delete all associated courses and topics. This cannot be undone.</p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="cancelDelete()" class="btn-secondary">Cancel</button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Delete Subject</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteSubject(subjectId) {
            document.getElementById('deleteForm').action = `/subjects/${subjectId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function cancelDelete() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cancelDelete();
            }
        });
    </script>
</x-app-layout>