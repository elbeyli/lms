<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors" title="Back to Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Settings</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your application preferences</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Settings Navigation -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Settings</h3>
                    </div>
                    <div class="card-body p-0">
                        <nav class="space-y-1">
                            <a href="{{ route('user.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile Information
                            </a>
                            <div class="flex items-center px-4 py-3 text-sm text-blue-600 bg-blue-50 border-r-2 border-blue-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Application Settings
                            </div>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Study Preferences -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Study Preferences</h3>
                        <p class="text-sm text-gray-600">Customize your study experience</p>
                    </div>
                    <div class="card-body space-y-6">
                        <!-- Default Study Session Duration -->
                        <div>
                            <label class="form-label">Default Study Session Duration</label>
                            <select class="form-input">
                                <option>25 minutes (Pomodoro)</option>
                                <option>30 minutes</option>
                                <option>45 minutes</option>
                                <option>60 minutes</option>
                                <option>90 minutes</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">This will be used as the default when creating new study sessions</p>
                        </div>

                        <!-- Study Reminders -->
                        <div>
                            <label class="form-label">Study Reminders</label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Daily study reminders</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Deadline reminders</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Weekly progress reports</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Preferences -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Display Preferences</h3>
                        <p class="text-sm text-gray-600">Customize how information is displayed</p>
                    </div>
                    <div class="card-body space-y-6">
                        <!-- Theme -->
                        <div>
                            <label class="form-label">Theme</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="flex items-center justify-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 bg-blue-50 border-blue-500">
                                    <input type="radio" name="theme" value="light" class="sr-only" checked>
                                    <div class="text-center">
                                        <div class="w-8 h-8 bg-white border border-gray-300 rounded mx-auto mb-2"></div>
                                        <span class="text-sm font-medium">Light</span>
                                    </div>
                                </label>
                                <label class="flex items-center justify-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="theme" value="dark" class="sr-only">
                                    <div class="text-center">
                                        <div class="w-8 h-8 bg-gray-800 border border-gray-300 rounded mx-auto mb-2"></div>
                                        <span class="text-sm font-medium">Dark</span>
                                    </div>
                                </label>
                                <label class="flex items-center justify-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="theme" value="auto" class="sr-only">
                                    <div class="text-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-white to-gray-800 border border-gray-300 rounded mx-auto mb-2"></div>
                                        <span class="text-sm font-medium">Auto</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Progress Display -->
                        <div>
                            <label class="form-label">Progress Display Format</label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="progress_format" value="percentage" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Percentage (75%)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="progress_format" value="fraction" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Fraction (3/4 completed)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="progress_format" value="both" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Both (3/4 - 75%)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-medium text-gray-900">Account Actions</h3>
                        <p class="text-sm text-gray-600">Manage your account data</p>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Export Data</h4>
                                <p class="text-sm text-gray-600">Download all your study data</p>
                            </div>
                            <button class="btn-secondary">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div>
                                <h4 class="text-sm font-medium text-yellow-900">Reset Progress</h4>
                                <p class="text-sm text-yellow-700">Clear all progress but keep subjects and courses</p>
                            </div>
                            <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                                Reset
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                            <div>
                                <h4 class="text-sm font-medium text-red-900">Delete Account</h4>
                                <p class="text-sm text-red-700">Permanently delete your account and all data</p>
                            </div>
                            <button class="btn-danger">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Save Settings -->
                <div class="flex items-center justify-end">
                    <button class="btn-primary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>