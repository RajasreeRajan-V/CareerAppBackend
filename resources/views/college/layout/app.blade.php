<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CareerApp') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --brand: #306060;
            --brand-dark: #254848;
            --sidebar-w: 255px;
            --topbar-h: 64px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f2f5f7;
            color: #1e2d3d;
        }

        #appShell {
            display: flex;
            min-height: 100vh;
        }

        #mainArea {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: margin-left .28s cubic-bezier(.4,0,.2,1);
        }

        @media (max-width: 991.98px) {
            #mainArea { margin-left: 0; }
        }

        #topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e4e9ee;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 14px;
            box-shadow: 0 1px 6px rgba(0,0,0,.05);
        }

        .topbar-hamburger {
            display: none;
            width: 36px;
            height: 36px;
            border: none;
            background: #f2f5f7;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #5a6a7a;
            font-size: .95rem;
            flex-shrink: 0;
            transition: background .15s;
        }

        .topbar-hamburger:hover { background: #e4e9ee; }

        @media (max-width: 991.98px) {
            .topbar-hamburger { display: flex; }
        }

        .topbar-titles { flex: 1; min-width: 0; }

        .topbar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e2d3d;
            margin: 0;
            line-height: 1.2;
        }

        .topbar-sub { font-size: .75rem; color: #8a97a6; margin: 0; }

        .topbar-search { position: relative; }

        .topbar-search input {
            width: 210px;
            padding: 8px 14px 8px 36px;
            border: 1.5px solid #e4e9ee;
            border-radius: 9px;
            font-size: .83rem;
            font-family: inherit;
            background: #f7f9fb;
            color: #1e2d3d;
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }

        .topbar-search input:focus {
            border-color: var(--brand);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(48,96,96,.1);
        }

        .topbar-search input::placeholder { color: #adb8c4; }

        .topbar-search i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #b0bac6;
            font-size: .78rem;
        }

        @media (max-width: 767.98px) {
            .topbar-search { display: none; }
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: #f2f5f7;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5a6a7a;
            cursor: pointer;
            flex-shrink: 0;
            font-size: .9rem;
            position: relative;
            transition: background .15s, color .15s;
        }

        .topbar-icon-btn:hover { background: #e4e9ee; color: var(--brand); }

        .notif-dot::after {
            content: '';
            position: absolute;
            top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: #e74c3c;
            border-radius: 50%;
            border: 1.5px solid #fff;
        }

        .topbar-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .82rem;
            flex-shrink: 0;
        }

        #pageContent {
            flex: 1;
            padding: 26px 28px;
            overflow-y: auto;
        }

        #pageContent > .content-inner {
            max-width: 1280px;
            margin: 0 auto;
        }

        @media (max-width: 575.98px) {
            #pageContent { padding: 16px; }
            #topbar { padding: 0 16px; }
        }

        #appFooter {
            background: #fff;
            border-top: 1px solid #e4e9ee;
            padding: 13px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            font-size: .78rem;
            color: #8a97a6;
        }

        #appFooter a { color: #8a97a6; text-decoration: none; transition: color .15s; }
        #appFooter a:hover { color: var(--brand); }

        .footer-links { display: flex; gap: 18px; }

        button { font-family: inherit; }
    </style>
    @stack('styles')
</head>

<body>
    <div id="appShell">

        @include('college.layout.navigation')

        <div id="mainArea">

            <header id="topbar">

                <button class="topbar-hamburger" onclick="window.openAppSidebar && openAppSidebar()" aria-label="Open menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-titles">
                    <p class="topbar-title">
                        @isset($header)
                            {{ $header }}
                        @else
                            Dashboard
                        @endisset
                    </p>
                    <p class="topbar-sub">Welcome back, {{ Auth::guard('college')->user()->name }}!</p>
                </div>

                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search…">
                </div>

                @php
                    $college = Auth::guard('college')->user();
                    $newViewCount = $college
                        ? \App\Models\CollegeView::where('college_id', $college->id)->where('is_read', false)->count()
                        : 0;
                @endphp

                <a href="{{ route('college.dashboard.viewers') }}" class="topbar-icon-btn" aria-label="Notifications" style="position:relative; text-decoration:none;">
                    <i class="fa-regular fa-bell"></i>
                    @if($newViewCount > 0)
                        <span style="
                            position: absolute;
                            top: 4px; right: 4px;
                            background: #e74c3c;
                            color: #fff;
                            font-size: 9px;
                            font-weight: 700;
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            min-width: 16px;
                            height: 16px;
                            border-radius: 8px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            padding: 0 3px;
                            border: 1.5px solid #fff;
                            line-height: 1;
                        ">{{ $newViewCount > 99 ? '99+' : $newViewCount }}</span>
                    @endif
                </a>

                <div class="topbar-avatar">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>

            </header>

            <main id="pageContent">
                <div class="content-inner">
                    @yield('content')
                </div>
            </main>

            <footer id="appFooter">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Contact</a>
                </div>
            </footer>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>