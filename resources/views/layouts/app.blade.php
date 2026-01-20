<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CareerApp') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
  integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">

        <!-- SIDEBAR -->
        <aside class="w-72 bg-gradient-to-b from-[#306060] to-[#254848] text-white flex flex-col shadow-2xl relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>

            <!-- Logo -->
           <div class="h-20 flex items-center px-6 border-b border-white/10 relative z-10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                        <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">
                        {{ config('app.name', 'Admin Panel') }}
                    </span>
                </div>
            </div>
        

            <!-- User Profile Card -->
            <div class="px-6 py-5 border-b border-white/10 relative z-10">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-white ring-2 ring-white/30">
                        <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Career App Logo"
                            class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-white/70 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>

            </div>

            <!-- Nav Links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto relative z-10">
                <div class="mb-4">
                    <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-2">Main Menu</p>

                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/15 shadow-lg' : '' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/70' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                        @if(request()->routeIs('dashboard'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></div>
                        @endif
                    </a>

                    <a href="#"
                        class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="font-medium">Users</span>
                    </a>

                    <a href="#"
                        class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span class="font-medium">Analytics</span>
                    </a>

                    <a href="#"
                        class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="font-medium">Schedule</span>
                    </a>
                </div>

                <div class="pt-4 border-t border-white/10">
                    <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-2">Settings</p>

                    <a href="#"
                        class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Settings</span>
                    </a>

                    <a href="#"
                        class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span class="font-medium">Help & Support</span>
                    </a>
                </div>
            </nav>

            <!-- Logout -->
            <div class="px-4 py-4 border-t border-white/10 relative z-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="group w-full flex items-center px-4 py-3 rounded-xl hover:bg-red-500/20 transition-all duration-200 text-white/90 hover:text-white">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- TOP BAR -->
            <header class="bg-white shadow-sm h-20 flex items-center px-8 sticky top-0 z-40">
                <div class="flex items-center justify-between w-full">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            @isset($header)
                                {{ $header }}
                            @else
                                Dashboard
                            @endisset
                        </h1>
                        <p class="text-sm text-gray-500 mt-0.5">Welcome back, {{ Auth::user()->name }}!</p>
                    </div>

                    <!-- Top Bar Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Search..."
                                class="w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#306060] focus:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="flex items-center space-x-3 pl-4 border-l border-gray-200">
    <div
        class="w-10 h-10 bg-gradient-to-br from-[#306060] to-[#254848] rounded-full flex items-center justify-center text-white shadow-md">
        <i class="fa-solid fa-graduation-cap text-lg"></i>
    </div>
</div>

                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 p-8 overflow-y-auto bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-8">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="hover:text-[#306060] transition-colors">Privacy Policy</a>
                        <span class="text-gray-300">|</span>
                        <a href="#" class="hover:text-[#306060] transition-colors">Terms of Service</a>
                        <span class="text-gray-300">|</span>
                        <a href="#" class="hover:text-[#306060] transition-colors">Contact</a>
                    </div>
                </div>
            </footer>
        </div>

    </div>
</body>

</html>