<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'CareerApp'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <style>
        :root {
            --brand: #306060;
            --brand-dark: #254848;
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

        #guestWrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        #guestTopbar {
            height: 58px;
            background: #0f2626;
            border-bottom: 1px solid rgba(79, 163, 163, 0.25);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            flex-shrink: 0;
        }

        .guest-topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .guest-logo-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(79, 163, 163, 0.18);
            border: 1px solid rgba(79, 163, 163, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4fa3a3;
            font-size: 15px;
            flex-shrink: 0;
        }

        .guest-logo-name {
            font-size: 15px;
            font-weight: 700;
            color: #e0f0f0;
            letter-spacing: 0.3px;
            font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
        }

        .guest-topbar-nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .guest-topbar-nav a {
            font-size: 12.5px;
            color: #9dc9c9;
            text-decoration: none;
            padding: 6px 13px;
            border-radius: 6px;
            font-weight: 500;
            border: 1px solid transparent;
            transition: all 0.2s;
            font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
        }

        .guest-topbar-nav a:hover {
            background: rgba(79, 163, 163, 0.12);
            border-color: rgba(79, 163, 163, 0.30);
            color: #d0eaea;
        }

        .guest-topbar-nav a.active {
            background: rgba(79, 163, 163, 0.18);
            border-color: rgba(79, 163, 163, 0.40);
            color: #c5e8e8;
        }

        /* ── CONTENT ── */
        #guestContent {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        /* ── FOOTER ── */
        #guestFooter {
            background: #0f2626;
            border-top: 1px solid rgba(79, 163, 163, 0.20);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            font-size: .78rem;
            color: #6a9e9e;
            flex-shrink: 0;
            font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
        }

        #guestFooter a {
            color: #6a9e9e;
            text-decoration: none;
            transition: color .2s;
        }

        #guestFooter a:hover { color: #9dc9c9; }

        .footer-links { display: flex; gap: 18px; }

        button { font-family: inherit; }

        @media (max-width: 480px) {
            #guestTopbar { padding: 0 16px; }
            .guest-logo-name { display: none; }
            #guestFooter {
                flex-direction: column;
                text-align: center;
                padding: 12px 16px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="guestWrap">

        {{-- TOPBAR: child sets @section('topbar','hide') to suppress --}}
        @if(trim($__env->yieldContent('topbar')) !== 'hide')
            <header id="guestTopbar">
                <a class="guest-topbar-brand" href="{{ url('/') }}">
                    <div class="guest-logo-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="guest-logo-name">{{ config('app.name', 'CareerApp') }}</span>
                </a>
                <nav class="guest-topbar-nav">
                    <a href="{{ route('login') }}"
                       class="{{ request()->routeIs('login') ? 'active' : '' }}">
                        <i class="fa-solid fa-right-to-bracket" style="font-size:.75rem; margin-right:4px;"></i>
                        Login
                    </a>
                    <a href="#">
                        <i class="fa-solid fa-circle-question" style="font-size:.75rem; margin-right:4px;"></i>
                        Help
                    </a>
                </nav>
            </header>
        @endif

        {{-- PAGE CONTENT --}}
        <main id="guestContent">
            @yield('content')
        </main>

        {{-- FOOTER: child sets @section('footer','hide') to suppress --}}
        @if(trim($__env->yieldContent('footer')) !== 'hide')
            <footer id="guestFooter">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'CareerApp') }}. All rights reserved.</span>
                <div class="footer-links">
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                </div>
            </footer>
        @endif

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>