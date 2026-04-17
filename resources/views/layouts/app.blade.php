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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-DaluvxN5.css') }}">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Layout */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
            gap: 0;
            align-items: flex-start;
            /* allows sticky to work */
        }

        /* Main content area */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            margin: 0;
            /* no gap from sidebar */
        }

        /* Top bar */
        .top-bar {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-bar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-bar-title h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .top-bar-title p {
            font-size: 13px;
            color: #888;
            margin-top: 2px;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Search box */
        .search-box {
            position: relative;
        }

        .search-box input {
            width: 220px;
            padding: 8px 12px 8px 36px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #fafafa;
            transition: border-color 0.2s;
        }

        .search-box input:focus {
            border-color: #306060;
            background: #fff;
        }

        .search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 13px;
        }

        /* Icon buttons */
        .icon-btn {
            background: none;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            color: #555;
            font-size: 18px;
            transition: background 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            background: #f0f0f0;
        }

        .notification-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #e53e3e;
            border-radius: 50%;
        }

        /* Avatar */
        .avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #306060, #254848);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 15px;
            border-left: 1px solid #e0e0e0;
            padding-left: 12px;
            margin-left: 4px;
        }

        /* Sidebar toggle (mobile) */
        .sidebar-toggle {
            display: none;
        }

        /* Page content */
        .page-content {
            flex: 1;
            padding: 28px 32px;
            overflow-y: auto;
        }

        .page-content-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Footer */
        .app-footer {
            background: #fff;
            border-top: 1px solid #e0e0e0;
            padding: 14px 24px;
            font-size: 13px;
            color: #777;
        }

        .app-footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .footer-links a {
            color: #777;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #306060;
        }

        .footer-divider {
            color: #ccc;
        }

        /* Modal override */
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .form-label {
            color: #000;
            font-weight: 500;
        }

        /* Alpine cloak */
        [x-cloak] {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar-toggle {
                display: block;
            }

            .search-box {
                display: none;
            }

            .top-bar-title p {
                display: none;
            }

            .page-content {
                padding: 16px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="app-wrapper">

        {{-- SIDEBAR --}}
        @include('layouts.navigation')

        <!-- MAIN CONTENT -->
        <div class="main-content">

            <!-- TOP BAR -->
            <header class="top-bar">
                <div class="top-bar-inner">

                    <!-- Left: toggle + title -->
                    <div class="top-bar-left">
                        <button id="openSidebar" class="icon-btn sidebar-toggle">
                            <i class="fa-solid fa-bars"></i>
                        </button>

                        <div class="top-bar-title">
                            <h1>
                                @isset($header)
                                    {{ $header }}
                                @else
                                    Dashboard
                                @endisset
                            </h1>
                            <p>Welcome back, {{ Auth::user()->name }}!</p>
                        </div>
                    </div>

                    <!-- Right: search, notifications, avatar -->
                    <div class="top-bar-right">

                        <button class="icon-btn">
                            <i class="fa-regular fa-bell"></i>
                            <span class="notification-dot"></span>
                        </button>

                        <div class="avatar">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                    </div>
                </div>
            </header>
            <!-- PAGE CONTENT -->
            <main class="page-content">
                <div class="page-content-inner">
                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div class="alert d-flex align-items-center gap-2 border-0 shadow-sm mb-4"
                            style="background:#EAF3DE; color:#27500A; border-radius:10px; font-size:14px; padding:12px 16px;"
                            role="alert">
                            <i class="fa-solid fa-circle-check"
                                style="font-size:16px; color:#3B6D11; flex-shrink:0;"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"
                                style="filter: brightness(0.4);"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert d-flex align-items-center gap-2 border-0 shadow-sm mb-4"
                            style="background:#FEF2F2; color:#7F1D1D; border-radius:10px; font-size:14px; padding:12px 16px;"
                            role="alert">
                            <i class="fa-solid fa-circle-xmark"
                                style="font-size:16px; color:#DC2626; flex-shrink:0;"></i>
                            <span>{{ session('error') }}</span>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"
                                style="filter: brightness(0.4);"></button>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>

            <!-- FOOTER -->
            <footer class="app-footer">
                <div class="app-footer-inner">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <span class="footer-divider">|</span>
                        <a href="#">Terms of Service</a>
                        <span class="footer-divider">|</span>
                        <a href="#">Contact</a>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('build/assets/app-BXS-Op9n.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
