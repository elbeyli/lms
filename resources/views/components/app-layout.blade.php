@props(['breadcrumbs' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-xl font-semibold text-gray-900">{{ config('app.name', 'LMS') }}</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('subjects.index') }}"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('subjects.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Subjects
                            </a>
                            <a href="{{ route('courses.index') }}"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('courses.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Courses
                            </a>
                            <a href="{{ route('topics.index') }}"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('topics.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Topics
                            </a>
                            <a href="{{ route('sessions.index') }}"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('sessions.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                Sessions
                            </a>
                        @endauth
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- Notifications -->
                            <button class="text-gray-500 hover:text-gray-700 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-5-5-5 5h5zM15 17v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2"></path>
                                </svg>
                            </button>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center text-sm text-gray-700 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-sm font-medium text-blue-600">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('user.profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <a href="{{ route('user.settings') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Register
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div x-show="mobileMenuOpen" x-transition class="md:hidden">
                    <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                Dashboard
                            </a>
                            <a href="{{ route('subjects.index') }}"
                                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                Subjects
                            </a>
                            <a href="{{ route('courses.index') }}"
                                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                Courses
                            </a>
                            <a href="{{ route('topics.index') }}"
                                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                Topics
                            </a>
                            <a href="{{ route('sessions.index') }}"
                                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                Sessions
                            </a>
                        @endauth
                        @guest
                            <a href="{{ route('login') }}"
                                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                Register
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <!-- Breadcrumbs -->
            @if (isset($breadcrumbs))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-4">
                            @foreach ($breadcrumbs as $breadcrumb)
                                @if ($loop->last)
                                    <li class="text-sm font-medium text-gray-500">{{ $breadcrumb['name'] }}</li>
                                @else
                                    <li>
                                        <div class="flex items-center">
                                            <a href="{{ $breadcrumb['url'] }}"
                                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                                {{ $breadcrumb['name'] }}
                                            </a>
                                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400 ml-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            @endif

            <!-- Page Header -->
            @if (isset($header))
                <div class="bg-white shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                        {{ $header }}
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-green-800">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-sm text-red-800">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Alpine.js for interactive components -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Initialize mobile menu state
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                mobileMenuOpen: false
            }))
        })
    </script>
</body>

</html>
