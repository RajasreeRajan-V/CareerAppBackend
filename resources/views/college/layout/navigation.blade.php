<style>
/* ── SIDEBAR STYLES ─────────────────────── */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 255px;
    height: 100vh;
    background: linear-gradient(175deg, #2a5454 0%, #306060 55%, #3a7070 100%);
    display: flex;
    flex-direction: column;
    z-index: 1050;
    transform: translateX(0);
    transition: transform .28s cubic-bezier(.4,0,.2,1);
    box-shadow: 4px 0 20px rgba(0,0,0,.15);
}

.sidebar.sidebar-hidden {
    transform: translateX(-100%);
}

/* Logo area */
.sb-logo {
    height: 64px;
    display: flex;
    align-items: center;
    padding: 0 18px;
    border-bottom: 1px solid rgba(255,255,255,.1);
    flex-shrink: 0;
    gap: 12px;
}

.sb-logo-icon {
    width: 34px; height: 34px;
    background: rgba(255,255,255,.18);
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    color: #fff;
}

.sb-logo-name {
    font-weight: 700;
    font-size: 1.05rem;
    color: #fff;
    letter-spacing: -.01em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* User Profile */
.sb-profile {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 14px 18px;
    border-bottom: 1px solid rgba(255,255,255,.1);
    flex-shrink: 0;
}

.sb-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(255,255,255,.25);
    flex-shrink: 0;
    background: rgba(255,255,255,.15);
}

.sb-avatar img { width: 100%; height: 100%; object-fit: cover; }

.sb-user-name {
    font-size: .95rem;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sb-user-email {
    font-size: .8rem;
    color: rgba(255,255,255,.55);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Nav scroll area */
.sb-nav {
    flex: 1;
    overflow-y: auto;
    padding: 14px 10px;
    min-height: 0;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.2) transparent;
}

.sb-nav::-webkit-scrollbar { width: 4px; }
.sb-nav::-webkit-scrollbar-track { background: transparent; }
.sb-nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,.22);
    border-radius: 4px;
}

.sb-section-label {
    font-size: .75rem;
    font-weight: 700;
    color: rgba(255,255,255,.38);
    text-transform: uppercase;
    letter-spacing: .1em;
    padding: 0 10px;
    margin: 4px 0 8px;
}

/* Nav links */
.sb-link {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 12px;
    border-radius: 9px;
    font-size: .900rem;
    color: rgba(255,255,255,.88);
    text-decoration: none;
    transition: background .18s, color .18s;
    margin-bottom: 2px;
    cursor: pointer;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    min-height: 44px;
}

.sb-link:hover {
    background: rgba(255,255,255,.12);
    color: #fff;
}

.sb-link.active {
    background: rgba(255,255,255,.18);
    color: #fff;
    font-weight: 600;
}

.sb-link-icon {
    width: 18px;
    text-align: center;
    flex-shrink: 0;
    font-size: .95rem;
}

.sb-chevron {
    margin-left: auto;
    font-size: .7rem;
    transition: transform .22s;
    color: rgba(255,255,255,.5);
}

.sb-chevron.open { transform: rotate(180deg); }

/* Dropdown sub-items */
.sb-sub {
    display: none;
    margin: 2px 0 2px 27px;
}

.sb-sub.open { display: block; }

.sb-sub-link {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 10px;
    border-radius: 7px;
    font-size: .9rem;
    color: rgba(255,255,255,.75);
    text-decoration: none;
    transition: background .15s, color .15s;
    margin-bottom: 1px;
    min-height: 38px;
}

.sb-sub-link:hover { background: rgba(255,255,255,.1); color: #fff; }
.sb-sub-link.active { background: rgba(255,255,255,.12); color: #fff; }

/* Logout */
.sb-footer {
    padding: 10px;
    border-top: 1px solid rgba(255,255,255,.1);
    flex-shrink: 0;
}

.sb-logout {
    display: flex;
    align-items: center;
    gap: 11px;
    width: 100%;
    padding: 10px 12px;
    border-radius: 9px;
    font-size: 1rem;
    color: rgba(255,255,255,.75);
    background: transparent;
    border: none;
    cursor: pointer;
    transition: background .18s, color .18s;
    min-height: 44px;
}

.sb-logout:hover {
    background: rgba(239,68,68,.2);
    color: #fca5a5;
}

/* Mobile overlay */
.sb-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 1040;
}

.sb-overlay.show { display: block; }

/* Mobile close btn */
.sb-close-btn {
    display: none;
    position: absolute;
    top: 14px; right: 14px;
    width: 30px; height: 30px;
    background: rgba(255,255,255,.15);
    border: none;
    border-radius: 6px;
    color: #fff;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    transition: background .15s;
}

.sb-close-btn:hover { background: rgba(255,255,255,.25); }

@media (max-width: 991.98px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.sidebar-open { transform: translateX(0); }
    .sb-close-btn { display: flex; }
}
</style>

<!-- Overlay -->
<div class="sb-overlay" id="sbOverlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="appSidebar">

    <!-- Close (mobile) -->
    <button class="sb-close-btn" id="sbCloseBtn">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <!-- Logo -->
    <div class="sb-logo">
        <div class="sb-logo-icon">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <span class="sb-logo-name">{{ config('app.name', 'College Panel') }}</span>
    </div>

    <!-- User Profile -->
    <div class="sb-profile">
        <div class="sb-avatar">
            <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Logo">
        </div>
        <div style="min-width:0;">
            <div class="sb-user-name">{{ Auth::user()->name }}</div>
            <div class="sb-user-email">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <!-- Scrollable Nav -->
    <nav class="sb-nav">

        <div class="sb-section-label">Main Menu</div>

        <!-- Dashboard -->
        <a href="{{ route('college.dashboard') }}"
            class="sb-link {{ request()->routeIs('college.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house sb-link-icon"></i>
            Dashboard
        </a>

        <!-- Change Password -->
        <a href="{{ route('college.password.change') }}"
            class="sb-link {{ request()->routeIs('college.password.change') ? 'active' : '' }}">
            <i class="fa-solid fa-lock sb-link-icon"></i>
            Change Password
        </a>

        <!-- Update College Details -->
        <a href="{{ route('college.collegeEdit.index') }}"
            class="sb-link {{ request()->routeIs('college.collegeEdit.index') ? 'active' : '' }}">
            <i class="fa-solid fa-building-columns sb-link-icon"></i>
            Update College Details
        </a>

        <!-- College Registrations -->
        <a href="{{ route('college.collegeCourse.index') }}"
            class="sb-link {{ request()->routeIs('college.collegeCourse.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-signature sb-link-icon"></i>
            College Course Updation
        </a>

         <!-- Create Fee Structure -->
        <a href="{{ route('college.feeStructure.index') }}"
            class="sb-link {{ request()->routeIs('college.feeStructure.*') ? 'active' : '' }}">
            <i class="fa-solid fa-indian-rupee-sign nav-icon"></i>
            Create Fee Structure
        </a>
    </nav>

    <!-- Logout -->
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <i class="fa-solid fa-right-from-bracket sb-link-icon"></i>
                Logout
            </button>
        </form>
    </div>

</aside>

<script>
(function () {
    const sidebar  = document.getElementById('appSidebar');
    const overlay  = document.getElementById('sbOverlay');
    const closeBtn = document.getElementById('sbCloseBtn');

    function openSidebar() {
        sidebar.classList.add('sidebar-open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('sidebar-open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    window.openAppSidebar  = openSidebar;
    window.closeAppSidebar = closeSidebar;

    window.toggleDrop = function(dropId, chevronId) {
        document.getElementById(dropId).classList.toggle('open');
        document.getElementById(chevronId).classList.toggle('open');
    };

    // Close on link click (mobile)
    sidebar.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) setTimeout(closeSidebar, 80);
        });
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) closeSidebar();
    });
})();
</script>