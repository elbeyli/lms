<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors" title="Back to Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">User Profile</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your account information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Profile Information -->
        <div class="card mb-8">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                <p class="text-sm text-gray-600">Update your account's profile information and email address.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-input @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-input @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Information -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Account Information</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Member since:</strong> {{ $user->created_at->format('F j, Y') }}</p>
                            <p><strong>Last updated:</strong> {{ $user->updated_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center justify-end">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                <p class="text-sm text-gray-600">Update your password to keep your account secure.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Current info hidden fields -->
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <!-- New Password -->
                    <div class="mb-6">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-input @error('password') border-red-500 @enderror"
                               placeholder="Enter new password">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-input @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Confirm new password">
                        @error('password_confirmation')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Requirements -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Password Requirements</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• At least 8 characters long</li>
                            <li>• Include a mix of letters, numbers, and symbols</li>
                            <li>• Avoid using personal information</li>
                        </ul>
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center justify-end">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>