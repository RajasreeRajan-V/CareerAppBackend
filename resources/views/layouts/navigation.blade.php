<!-- SIDEBAR -->
<aside id="sidebar">

    <!-- Mobile Close Button -->
    <button id="closeSidebar" class="sidebar-close-btn">
        <i class="fa-solid fa-times"></i>
    </button>

    <!-- Desktop Minimize Toggle -->
    <button id="toggleSidebar" class="sidebar-toggle-btn">
        <i class="fa-solid fa-chevron-left" id="toggleIcon"></i>
    </button>

    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <span class="sidebar-text sidebar-app-name">
            {{ config('app.name', 'Admin Panel') }}
        </span>
    </div>

    <!-- User Profile -->
    <div class="sidebar-profile">
        <div class="sidebar-avatar">
            <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Logo">
        </div>
        <div class="sidebar-text sidebar-user-info">
            <p class="sidebar-user-name">{{ Auth::user()->name }}</p>
            <p class="sidebar-user-email">{{ Auth::user()->email }}</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <p class="sidebar-text nav-section-label">Main Menu</p>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-house nav-icon"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- Admission Banner -->
        <a href="{{ route('admin.admissionBanner.index') }}"
            class="nav-link {{ request()->routeIs('admin.admissionBanner.*') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-photo-film nav-icon"></i>
            <span class="sidebar-text">Admission Banner</span>
        </a>

        <!-- Colleges Dropdown -->
        <div class="nav-dropdown">
            <button type="button"
                class="nav-link nav-dropdown-toggle {{ request()->routeIs('admin.college.*') ? 'nav-link-active' : '' }}"
                onclick="toggleCollegeDropdown()">
                <i class="fa-solid fa-building-columns nav-icon"></i>
                <span class="sidebar-text nav-dropdown-label">Colleges</span>
                <i class="fa-solid fa-chevron-down nav-chevron sidebar-text" id="collegeChevron"></i>
            </button>
            <div id="collegeDropdown" class="nav-submenu hidden">
                <a href="{{ route('admin.college.create') }}"
                    class="nav-sublink {{ request()->routeIs('admin.college.create') ? 'nav-sublink-active' : '' }}">
                    <i class="fas fa-school"></i> Create College
                </a>
                <a href="{{ route('admin.college.index') }}"
                    class="nav-sublink {{ request()->routeIs('admin.college.index') ? 'nav-sublink-active' : '' }}">
                    <i class="fa-solid fa-book-open"></i> Manage Colleges
                </a>
            </div>
        </div>

        <!-- Careers Dropdown -->
        <div class="nav-dropdown">
            <button type="button"
                class="nav-link nav-dropdown-toggle {{ request()->routeIs('admin.career_nodes.*') ? 'nav-link-active' : '' }}"
                onclick="toggleCareerDropdown()">
                <i class="fa-solid fa-graduation-cap nav-icon"></i>
                <span class="sidebar-text nav-dropdown-label">Careers</span>
                <i class="fa-solid fa-chevron-down nav-chevron sidebar-text" id="careerChevron"></i>
            </button>
            <div id="careerDropdown" class="nav-submenu hidden">
                <a href="{{ route('admin.career_nodes.create') }}"
                    class="nav-sublink {{ request()->routeIs('admin.career_nodes.create') ? 'nav-sublink-active' : '' }}">
                    <i class="fa-solid fa-briefcase"></i> Create Career
                </a>
                <a href="{{ route('admin.career_nodes.index') }}"
                    class="nav-sublink {{ request()->routeIs('admin.career_nodes.index') ? 'nav-sublink-active' : '' }}">
                    <i class="fa-solid fa-user-doctor"></i> Manage Career
                </a>
            </div>
        </div>

        <!-- Career Path -->
        <a href="{{ route('admin.career_link.index') }}"
            class="nav-link {{ request()->routeIs('admin.career_link.index') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-arrow-trend-up nav-icon"></i>
            <span class="sidebar-text">Manage Career Path</span>
        </a>

        <!-- Career Banners -->
        <a href="{{ route('admin.careerBanner.index') }}"
            class="nav-link {{ request()->routeIs('admin.careerBanner.index') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-image nav-icon"></i>
            <span class="sidebar-text">Career Banners</span>
        </a>

        <!-- Recorded Videos -->
        <a href="{{ route('admin.RecordVideo.index') }}"
            class="nav-link {{ request()->routeIs('admin.RecordVideo.*') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-circle-play nav-icon"></i>
            <span class="sidebar-text">Recorded Videos</span>
        </a>

        <!-- Career Guidance -->
        <a href="{{ route('admin.guidance_banners.index') }}"
            class="nav-link {{ request()->routeIs('admin.guidance_banners.*') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-chalkboard-user nav-icon"></i>
            <span class="sidebar-text">Career Guidance</span>
        </a>

        <!-- College Registrations -->
        {{-- <a href="{{ route('admin.college_registration.index') }}"
            class="nav-link {{ request()->routeIs('admin.college_registration.*') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-file-signature nav-icon"></i>
            <span class="sidebar-text">College Registrations</span>
        </a> --}}
         <!-- Create Location -->
        <a href="{{ route('admin.createLocation.index') }}"
            class="nav-link {{ request()->routeIs('admin.createLocation.*') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-location-dot nav-icon"></i>
            <span class="sidebar-text">Create Location</span>
        </a>
          <!-- Create Location -->
        <a href="{{ route('admin.userManagement.index') }}"
            class="nav-link {{ request()->routeIs('admin.userManagement.*') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-users nav-icon"></i>
            <span class="sidebar-text">Manage Users</span>
        </a>
          <a href="{{ route('admin.career_guidance_registration.index') }}"
            class="nav-link {{ request()->routeIs('admin.career_guidance_registration.*') ? 'nav-link-active' : '' }}">
            <i class="fa-solid fa-compass nav-icon"></i>
            <span class="sidebar-text">Guidance Registrations</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link logout-btn">
                <i class="fa-solid fa-right-from-bracket nav-icon"></i>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebarOverlay"></div>

<style>
    /* ─────────────────────────────────────────
       SIDEBAR SHELL — sticky on desktop, never scrolls
    ───────────────────────────────────────── */
    #sidebar {
        width: 240px;
        min-width: 240px;
        flex-shrink: 0;
        background: linear-gradient(180deg, #306060 0%, #254848 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow: hidden;
        transition: width 0.3s ease, min-width 0.3s ease;
        z-index: 100;
        margin: 0;
    }

    /* Desktop minimized state */
    #sidebar.minimized {
        width: 62px;
        min-width: 62px;
    }
    #sidebar.minimized .sidebar-text,
    #sidebar.minimized .nav-submenu {
        display: none !important;
    }

    /* ─────────────────────────────────────────
       MOBILE — Sidebar becomes a fixed overlay drawer
    ───────────────────────────────────────── */
    @media (max-width: 992px) {
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px !important;
            min-width: 260px !important;
            height: 100%;
            z-index: 500;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        #sidebar.open {
            transform: translateX(0);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        }
    }

    /* ─────────────────────────────────────────
       MOBILE OVERLAY (backdrop)
    ───────────────────────────────────────── */
    #sidebarOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 499; /* just below sidebar */
    }
    #sidebarOverlay.show {
        display: block;
    }

    /* ─────────────────────────────────────────
       MOBILE CLOSE BUTTON
    ───────────────────────────────────────── */
    .sidebar-close-btn {
        display: none;
        position: absolute;
        top: 14px;
        right: 14px;
        width: 34px;
        height: 34px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 8px;
        color: #fff;
        font-size: 15px;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        z-index: 10;
        transition: background 0.2s;
    }
    .sidebar-close-btn:hover {
        background: rgba(255, 255, 255, 0.28);
    }
    @media (max-width: 992px) {
        .sidebar-close-btn {
            display: flex;
        }
    }

    /* ─────────────────────────────────────────
       DESKTOP MINIMIZE TOGGLE BUTTON
    ───────────────────────────────────────── */
    .sidebar-toggle-btn {
        display: none;
        position: absolute;
        top: 78px;
        right: -13px;
        width: 26px;
        height: 26px;
        background: #fff;
        border: none;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
        cursor: pointer;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #306060;
        z-index: 10;
        transition: background 0.2s;
    }
    .sidebar-toggle-btn:hover {
        background: #f0f0f0;
    }
    @media (min-width: 993px) {
        .sidebar-toggle-btn {
            display: flex;
        }
    }

    /* ─────────────────────────────────────────
       LOGO
    ───────────────────────────────────────── */
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 18px;
        height: 64px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        flex-shrink: 0;
    }
    /* On mobile, leave room for close button */
    @media (max-width: 992px) {
        .sidebar-logo {
            padding-right: 52px;
        }
    }
    .sidebar-logo-icon {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .sidebar-app-name {
        font-size: 16px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ─────────────────────────────────────────
       USER PROFILE
    ───────────────────────────────────────── */
    .sidebar-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        flex-shrink: 0;
    }
    .sidebar-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.3);
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.1);
    }
    .sidebar-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .sidebar-user-info {
        min-width: 0;
    }
    .sidebar-user-name {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
    }
    .sidebar-user-email {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.6);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 2px 0 0;
    }

    /* ─────────────────────────────────────────
       NAVIGATION LIST
    ───────────────────────────────────────── */
    .sidebar-nav {
        flex: 1;
        padding: 14px 10px;
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .sidebar-nav::-webkit-scrollbar {
        display: none;
    }

    .nav-section-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.45);
        padding: 0 12px;
        margin: 0 0 6px;
    }

    /* Nav link */
    .nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 12px;
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.2s;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        margin-bottom: 2px;
        min-height: 44px; /* accessible touch target */
    }
    .nav-link:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .nav-link-active {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
    }
    .nav-icon {
        font-size: 15px;
        width: 18px;
        flex-shrink: 0;
        text-align: center;
    }

    /* Dropdown wrapper */
    .nav-dropdown {
        margin-bottom: 2px;
    }
    .nav-dropdown-label {
        flex: 1;
    }
    .nav-chevron {
        font-size: 10px;
        margin-left: auto;
        transition: transform 0.25s;
    }
    .nav-chevron.rotated {
        transform: rotate(180deg);
    }

    /* Submenu */
    .nav-submenu {
        margin-left: 28px;
        margin-top: 2px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .nav-submenu.hidden {
        display: none;
    }
    .nav-sublink {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 8px;
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        font-size: 13px;
        transition: background 0.2s;
        min-height: 40px;
    }
    .nav-sublink:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .nav-sublink-active {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }

    /* ─────────────────────────────────────────
       LOGOUT FOOTER
    ───────────────────────────────────────── */
    .sidebar-footer {
        padding: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        flex-shrink: 0;
    }
    .logout-btn {
        color: rgba(255, 255, 255, 0.8);
    }
    .logout-btn:hover {
        background: rgba(229, 62, 62, 0.25);
        color: #fff;
    }
</style>

<script>
(function () {
    'use strict';

    function initSidebar() {
        const sidebar    = document.getElementById('sidebar');
        const overlay    = document.getElementById('sidebarOverlay');
        const openBtn    = document.getElementById('openSidebar');   // hamburger in app.blade header
        const closeBtn   = document.getElementById('closeSidebar');
        const toggleBtn  = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');

        const collegeDropdown = document.getElementById('collegeDropdown');
        const collegeChevron  = document.getElementById('collegeChevron');
        const careerDropdown  = document.getElementById('careerDropdown');
        const careerChevron   = document.getElementById('careerChevron');

        let isMinimized = localStorage.getItem('sidebarMinimized') === 'true';

        // Apply initial minimized state if on desktop
        if (!isMobile() && isMinimized) {
            sidebar.classList.add('minimized');
            if (toggleIcon) toggleIcon.className = 'fa-solid fa-chevron-right';
        }

        function isMobile() {
            return window.innerWidth <= 992;
        }

        /* ── Mobile: open ── */
        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // prevent scroll behind drawer
        }

        /* ── Mobile: close ── */
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        /* ── Desktop: minimize / expand ── */
        function toggleMinimize() {
            if (isMobile()) return;
            isMinimized = !isMinimized;
            sidebar.classList.toggle('minimized', isMinimized);
            toggleIcon.className = isMinimized
                ? 'fa-solid fa-chevron-right'
                : 'fa-solid fa-chevron-left';
            localStorage.setItem('sidebarMinimized', isMinimized);
        }

        /* ── Dropdown handlers (called via onclick) ── */
        window.toggleCollegeDropdown = function () {
            if (!collegeDropdown || !collegeChevron) return;
            const nowHidden = collegeDropdown.classList.toggle('hidden');
            collegeChevron.classList.toggle('rotated', !nowHidden);
        };

        window.toggleCareerDropdown = function () {
            if (!careerDropdown || !careerChevron) return;
            const nowHidden = careerDropdown.classList.toggle('hidden');
            careerChevron.classList.toggle('rotated', !nowHidden);
        };

        /* Auto-open dropdowns when route is active */
        if ({{ request()->routeIs('admin.college.*') ? 'true' : 'false' }}) {
            collegeDropdown && collegeDropdown.classList.remove('hidden');
            collegeChevron  && collegeChevron.classList.add('rotated');
        }
        if ({{ request()->routeIs('admin.career_nodes.*') ? 'true' : 'false' }}) {
            careerDropdown && careerDropdown.classList.remove('hidden');
            careerChevron  && careerChevron.classList.add('rotated');
        }

        /* ── Attach event listeners ── */
        if (openBtn)   openBtn.addEventListener('click',   openSidebar);
        if (closeBtn)  closeBtn.addEventListener('click',  closeSidebar);
        if (overlay)   overlay.addEventListener('click',   closeSidebar);
        if (toggleBtn) toggleBtn.addEventListener('click', toggleMinimize);

        /* Close drawer when a nav link is tapped on mobile */
        sidebar.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobile()) setTimeout(closeSidebar, 80);
            });
        });

        /* Handle resize / orientation change */
        window.addEventListener('resize', function () {
            if (!isMobile()) {
                /* switched to desktop — reset mobile state */
                overlay.classList.remove('show');
                sidebar.classList.remove('open');
                document.body.style.overflow = '';
                /* reapply minimized state if needed */
                if (isMinimized) {
                    sidebar.classList.add('minimized');
                    if (toggleIcon) toggleIcon.className = 'fa-solid fa-chevron-right';
                }
            } else {
                /* switched to mobile — reset any minimized state */
                if (isMinimized) {
                    sidebar.classList.remove('minimized');
                    if (toggleIcon) toggleIcon.className = 'fa-solid fa-chevron-left';
                }
            }
        });
    }

    /* Boot after DOM is ready */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
})();
</script>