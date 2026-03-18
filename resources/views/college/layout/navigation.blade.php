<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-64 h-screen bg-[#306060] text-white flex flex-col
    -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    <!-- Mobile Close Button -->
    <button id="closeSidebar"
        class="lg:hidden absolute top-3 right-3 w-8 h-8 flex items-center justify-center
        rounded-md bg-white/10 hover:bg-white/20 transition-colors z-10">
        <i class="fa-solid fa-times text-sm"></i>
    </button>

    <!-- Logo -->
    <div class="h-16 flex items-center px-5 border-b border-white/10 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 rounded-md flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-graduation-cap text-sm"></i>
            </div>
            <span class="font-semibold text-base tracking-tight truncate sidebar-text">
                {{ config('app.name', 'Admin Panel') }}
            </span>
        </div>
    </div>

    <!-- User Profile -->
    <div class="px-5 py-4 border-b border-white/10 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full overflow-hidden bg-white/20 ring-2 ring-white/20 flex-shrink-0">
                <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0 sidebar-text">
                <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-white/60 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation — scrollable -->
    <nav class="flex-1 min-h-0 px-3 py-4 overflow-y-auto space-y-0.5"
        style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.25) rgba(255,255,255,0.05);">

        <p class="px-3 text-[10px] font-semibold text-white/40 uppercase tracking-widest mb-2 sidebar-text">
            Main Menu
        </p>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
            hover:bg-white/10
            {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 font-medium' : '' }}">
            <i class="fa-solid fa-house w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- Admission Banner -->
        <a href="{{ route('admin.admissionBanner.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
            hover:bg-white/10
            {{ request()->routeIs('admin.admissionBanner.*') ? 'bg-white/15 font-medium' : '' }}">
            <i class="fa-solid fa-photo-film w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-text">Admission Banner</span>
        </a>

        <!-- Colleges Dropdown -->
        <div>
            <button type="button" onclick="toggleCollegeDropdown()"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                hover:bg-white/10
                {{ request()->routeIs('admin.college.*') ? 'bg-white/15 font-medium' : '' }}">
                <i class="fa-solid fa-building-columns w-4 text-center flex-shrink-0"></i>
                <span class="sidebar-text flex-1 text-left">Colleges</span>
                <i id="collegeChevron" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 sidebar-text"></i>
            </button>
            <div id="collegeDropdown" class="hidden mt-0.5 ml-7 space-y-0.5">
                <a href="{{ route('admin.college.create') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs transition-colors
                    hover:bg-white/10 text-white/80
                    {{ request()->routeIs('admin.college.create') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fa-solid fa-plus w-3 text-center"></i>
                    <span>Create College</span>
                </a>
                <a href="{{ route('admin.college.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs transition-colors
                    hover:bg-white/10 text-white/80
                    {{ request()->routeIs('admin.college.index') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fa-solid fa-list w-3 text-center"></i>
                    <span>Manage Colleges</span>
                </a>
            </div>
        </div>

        <!-- Careers Dropdown -->
        <div>
            <button type="button" onclick="toggleCareerDropdown()"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                hover:bg-white/10
                {{ request()->routeIs('admin.career_nodes.*') ? 'bg-white/15 font-medium' : '' }}">
                <i class="fa-solid fa-graduation-cap w-4 text-center flex-shrink-0"></i>
                <span class="sidebar-text flex-1 text-left">Careers</span>
                <i id="careerChevron" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 sidebar-text"></i>
            </button>
            <div id="careerDropdown" class="hidden mt-0.5 ml-7 space-y-0.5">
                <a href="{{ route('admin.career_nodes.create') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs transition-colors
                    hover:bg-white/10 text-white/80
                    {{ request()->routeIs('admin.career_nodes.create') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fa-solid fa-plus w-3 text-center"></i>
                    <span>Create Career</span>
                </a>
                <a href="{{ route('admin.career_nodes.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs transition-colors
                    hover:bg-white/10 text-white/80
                    {{ request()->routeIs('admin.career_nodes.index') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fa-solid fa-list w-3 text-center"></i>
                    <span>Manage Careers</span>
                </a>
            </div>
        </div>

        <!-- Career Path -->
        <a href="{{ route('admin.career_link.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
            hover:bg-white/10
            {{ request()->routeIs('admin.career_link.index') ? 'bg-white/15 font-medium' : '' }}">
            <i class="fa-solid fa-arrow-trend-up w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-text">Manage Career Path</span>
        </a>

        <!-- Career Banners -->
        <a href="{{ route('admin.careerBanner.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
            hover:bg-white/10
            {{ request()->routeIs('admin.careerBanner.index') ? 'bg-white/15 font-medium' : '' }}">
            <i class="fa-solid fa-image w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-text">Career Banners</span>
        </a>

        <!-- Recorded Videos -->
        <a href="{{ route('admin.RecordVideo.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
            hover:bg-white/10
            {{ request()->routeIs('admin.RecordVideo.*') ? 'bg-white/15 font-medium' : '' }}">
            <i class="fa-solid fa-circle-play w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-text">Recorded Videos</span>
        </a>

        <!-- Career Guidance -->
        <a href="{{ route('admin.guidance_banners.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
            hover:bg-white/10
            {{ request()->routeIs('admin.guidance_banners.*') ? 'bg-white/15 font-medium' : '' }}">
            <i class="fa-solid fa-chalkboard-user w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-text">Career Guidance</span>
        </a>

        <!-- College Registrations -->
        <a href="{{ route('admin.college_registration.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
            hover:bg-white/10
            {{ request()->routeIs('admin.college_registration.*') ? 'bg-white/15 font-medium' : '' }}">
            <i class="fa-solid fa-file-signature w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-text">College Registrations</span>
        </a>

    </nav>

    <!-- Logout -->
    <div class="px-3 py-3 border-t border-white/10 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                hover:bg-red-500/20 transition-colors">
                <i class="fa-solid fa-right-from-bracket w-4 text-center flex-shrink-0"></i>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"></div>

<script>
(function () {
    function initSidebar() {
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('sidebarOverlay');
        const openBtn  = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        const collegeDropdown = document.getElementById('collegeDropdown');
        const collegeChevron  = document.getElementById('collegeChevron');
        const careerDropdown  = document.getElementById('careerDropdown');
        const careerChevron   = document.getElementById('careerChevron');

        function isMobile() { return window.innerWidth < 1024; }

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        window.toggleCollegeDropdown = function () {
            collegeDropdown.classList.toggle('hidden');
            collegeChevron.classList.toggle('rotate-180');
        };

        window.toggleCareerDropdown = function () {
            careerDropdown.classList.toggle('hidden');
            careerChevron.classList.toggle('rotate-180');
        };

        const isCollegeActive = {{ request()->routeIs('admin.college.*') ? 'true' : 'false' }};
        const isCareerActive  = {{ request()->routeIs('admin.career_nodes.*') ? 'true' : 'false' }};

        if (isCollegeActive) { collegeDropdown.classList.remove('hidden'); collegeChevron.classList.add('rotate-180'); }
        if (isCareerActive)  { careerDropdown.classList.remove('hidden');  careerChevron.classList.add('rotate-180'); }

        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay)  overlay.addEventListener('click', closeSidebar);

        if (openBtn) {
            openBtn.addEventListener('click', openSidebar);
        } else {
            setTimeout(() => {
                const btn = document.getElementById('openSidebar');
                if (btn) btn.addEventListener('click', openSidebar);
            }, 300);
        }

        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => { if (isMobile()) setTimeout(closeSidebar, 100); });
        });

        window.addEventListener('resize', () => {
            if (!isMobile()) {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
                sidebar.classList.remove('-translate-x-full');
            }
        });
    }

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', initSidebar)
        : initSidebar();
})();
</script>