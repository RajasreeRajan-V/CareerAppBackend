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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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

        {{-- SIDEBAR --}}
        @include('layouts.navigation')

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOP BAR -->
            <header class="bg-white shadow-sm h-16 lg:h-20 flex items-center px-4 lg:px-8 sticky top-0 z-40">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-3">
                        <!-- Mobile Menu Button -->
                        <button id="openSidebar" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-bars text-xl text-gray-600"></i>
                        </button>

                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-800">
                                @isset($header)
                                    {{ $header }}
                                @else
                                    Dashboard
                                @endisset
                            </h1>
                            <p class="text-xs lg:text-sm text-gray-500 mt-0.5 hidden sm:block">
                                Welcome back, {{ Auth::user()->name }}!
                            </p>
                        </div>
                    </div>

                    <!-- Top Bar Actions -->
                    <div class="flex items-center space-x-2 lg:space-x-4">

                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Search..." class="w-48 lg:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-200
                                       focus:outline-none focus:ring-2 focus:ring-[#306060]">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400"></i>
                        </div>

                        <!-- Mobile Search Button -->
                        <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-magnifying-glass text-lg text-gray-600"></i>
                        </button>

                        <!-- Notifications -->
                        <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fa-regular fa-bell text-lg lg:text-xl text-gray-600"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Profile Icon -->
                        <div class="flex items-center space-x-3 pl-2 lg:pl-4 border-l border-gray-200">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-br from-[#306060] to-[#254848]
                                        rounded-full flex items-center justify-center text-white shadow-md">
                                <i class="fa-solid fa-graduation-cap text-sm lg:text-base"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 p-4 lg:p-8 overflow-y-auto bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-gray-200 py-3 lg:py-4 px-4 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between text-xs lg:text-sm text-gray-600 space-y-2 sm:space-y-0">
                    <p>
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-3 lg:space-x-4">
                        <a href="#" class="hover:text-[#306060]">Privacy Policy</a>
                        <span class="text-gray-300">|</span>
                        <a href="#" class="hover:text-[#306060]">Terms of Service</a>
                        <span class="text-gray-300 hidden sm:inline">|</span>
                        <a href="#" class="hover:text-[#306060] hidden sm:inline">Contact</a>
                    </div>
                </div>
            </footer>

        </div>
    </div>
</body>

</html>