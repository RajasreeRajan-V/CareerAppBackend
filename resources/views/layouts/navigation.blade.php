<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-[#306060] to-[#254848] 
    text-white flex flex-col shadow-2xl -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out overflow-hidden">

    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-3xl opacity-50 pointer-events-none"
        style="transform: translate(40%, -40%);"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full blur-2xl opacity-50 pointer-events-none"
        style="transform: translate(-40%, 40%);"></div>
    <div class="absolute top-1/3 right-0 w-20 h-20 bg-white/5 rounded-full blur-xl opacity-30 pointer-events-none"
        style="transform: translate(50%, 0);"></div>

    <!-- Mobile Close Button - FIXED -->
    <button id="closeSidebar"
        class="lg:hidden absolute top-4 right-4 w-10 h-10 flex items-center justify-center 
        rounded-lg hover:bg-white/10 active:bg-white/20 transition-colors bg-white/10 cursor-pointer border border-white/20"
        style="z-index: 1000 !important;">
        <i class="fa-solid fa-times text-xl"></i>
    </button>

    <!-- Desktop Toggle Button -->
    <button id="toggleSidebar" class="hidden lg:flex absolute -right-3 top-24 w-6 h-12 bg-white rounded-r-lg 
        items-center justify-center shadow-lg hover:bg-gray-100 transition-colors z-20 group">
        <i class="fa-solid fa-chevron-left text-gray-600 text-xs group-hover:text-[#306060] transition-colors"
            id="toggleIcon"></i>
    </button>

    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-white/10 relative z-10">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
            </div>
            <span class="text-xl font-bold tracking-tight sidebar-text whitespace-nowrap">
                {{ config('app.name', 'Admin Panel') }}
            </span>
        </div>
    </div>

    <!-- User Profile -->
    <div class="px-6 py-5 border-b border-white/10 relative z-10">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full overflow-hidden bg-white ring-2 ring-white/30 flex-shrink-0">
                <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0 sidebar-text">
                <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-white/70 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 relative z-10"
        style="overflow-y:auto; scrollbar-width:none; -ms-overflow-style:none; scroll-behavior:smooth;">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 sidebar-text">
            Main Menu
        </p>

        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
        {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 shadow-lg' : '' }}">
            <i class="fa-solid fa-house mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Dashboard</span>
        </a>

        <a href="{{ route('admin.admissionBanner.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
   {{ request()->routeIs('admin.admissionBanner.*') ? 'bg-white/15 shadow-lg' : '' }}">
            <i class="fa-solid fa-photo-film mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Admission Banner</span>
        </a>

        <!-- Colleges Dropdown -->
        <div class="group">
            <!-- Dropdown Toggle -->
            <button type="button" class="w-full flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
        {{ request()->routeIs('admin.college.*') ? 'bg-white/15 shadow-lg' : '' }}" onclick="toggleCollegeDropdown()">

                <i class="fa-solid fa-building-columns mr-3 flex-shrink-0"></i>

                <span class="font-medium sidebar-text flex-1 text-left">
                    Colleges
                </span>

                <i id="collegeChevron"
                    class="fa-solid fa-chevron-down text-xs transition-transform duration-300 sidebar-text"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="collegeDropdown" class="ml-10 mt-1 space-y-1 hidden">

                <a href="{{ route('admin.college.create') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-white/10 transition-all
                     {{ request()->routeIs('admin.college.create') ? 'bg-white/15' : '' }}">
                    <i class="fas fa-school me-2"></i>Create College
                </a>

                <a href="{{ route('admin.college.index') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-white/10 transition-all
                     {{ request()->routeIs('admin.college.index') ? 'bg-white/15' : '' }}">
                    <i class="fa-solid fa-book-open me-2"></i> Manage Colleges
                </a>
            </div>
        </div>

        <!-- Careers Dropdown -->
        <div class="group">
            <!-- Dropdown Toggle -->
            <button type="button" class="w-full flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
        {{ request()->routeIs('admin.career_nodes.*') ? 'bg-white/15 shadow-lg' : '' }}"
                onclick="toggleCareerDropdown()">

                <i class="fa-solid fa-graduation-cap mr-3 flex-shrink-0"></i>

                <span class="font-medium sidebar-text flex-1 text-left">
                    Careers
                </span>

                <i id="careerChevron"
                    class="fa-solid fa-chevron-down text-xs transition-transform duration-300 sidebar-text"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="careerDropdown" class="ml-10 mt-1 space-y-1 hidden">

                <a href="{{ route('admin.career_nodes.create') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-white/10 transition-all
   {{ request()->routeIs('admin.career_nodes.create') ? 'bg-white/15' : '' }}">
                    <i class="fa-solid fa-briefcase mr-2"></i>Create Career
                </a>
                <a href="{{ route('admin.career_nodes.index') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-white/10 transition-all
            {{ request()->routeIs('admin.career_nodes.index') ? 'bg-white/15' : '' }}">
                    <i class="fa-solid fa-user-doctor me-2"></i> Manage Career
                </a>

            </div>
        </div>

        <a href="{{ route('admin.career_link.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
   {{ request()->routeIs('admin.career_link.index') ? 'bg-white/15' : '' }}">
            <i class="fa-solid fa-arrow-trend-up me-2"></i><span class="font-medium sidebar-text">Manage Career
                Path</span>
        </a>
        
        <a href="{{ route('admin.careerBanner.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
   {{ request()->routeIs('admin.careerBanner.index') ? 'bg-white/15' : '' }}">
            <i class="fa-solid fa-image me-2"></i><span class="font-medium sidebar-text">Career Banners</span>
        </a>

    </nav>

    <!-- Logout -->
    <div class="px-4 py-4 border-t border-white/10 relative z-10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-4 py-3 rounded-xl hover:bg-red-500/20 transition-all">
                <i class="fa-solid fa-right-from-bracket mr-3 flex-shrink-0"></i>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"></div>

<!-- JavaScript for Mobile Menu Toggle -->
<script>
    (function () {
        'use strict';

        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const openBtn = document.getElementById('openSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');

            const collegeDropdown = document.getElementById('collegeDropdown');
            const collegeChevron = document.getElementById('collegeChevron');
            const careerDropdown = document.getElementById('careerDropdown');
            const careerChevron = document.getElementById('careerChevron');

            let isMinimized = false;

            if (!sidebar || !overlay) {
                console.error('Critical sidebar elements not found');
                return;
            }

            // ---------- Helpers ----------
            function isMobile() {
                return window.innerWidth < 1024;
            }

            // ---------- Mobile Sidebar ----------
            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                if (isMobile()) {
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // ---------- Desktop Minimize ----------
            function toggleSidebarMinimize() {
                if (isMobile()) return;

                isMinimized = !isMinimized;

                if (isMinimized) {
                    sidebar.classList.replace('w-72', 'w-20');
                    if (toggleIcon) {
                        toggleIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
                    }
                    sidebarTexts.forEach(text => text.classList.add('hidden'));
                } else {
                    sidebar.classList.replace('w-20', 'w-72');
                    if (toggleIcon) {
                        toggleIcon.classList.replace('fa-chevron-right', 'fa-chevron-left');
                    }
                    sidebarTexts.forEach(text => text.classList.remove('hidden'));
                }
            }

            // ---------- Dropdowns ----------
            window.toggleCollegeDropdown = function () {
                if (collegeDropdown && collegeChevron) {
                    collegeDropdown.classList.toggle('hidden');
                    collegeChevron.classList.toggle('rotate-180');
                }
            };

            window.toggleCareerDropdown = function () {
                if (careerDropdown && careerChevron) {
                    careerDropdown.classList.toggle('hidden');
                    careerChevron.classList.toggle('rotate-180');
                }
            };

            // Auto-open dropdowns if route is active
            const isCollegeRouteActive = {{ request()->routeIs('admin.college.*') ? 'true' : 'false' }};
            const isCareerRouteActive = {{ request()->routeIs('admin.career_nodes.*') ? 'true' : 'false' }};

            if (isCollegeRouteActive && collegeDropdown && collegeChevron) {
                collegeDropdown.classList.remove('hidden');
                collegeChevron.classList.add('rotate-180');
            }

            if (isCareerRouteActive && careerDropdown && careerChevron) {
                careerDropdown.classList.remove('hidden');
                careerChevron.classList.add('rotate-180');
            }

            // ---------- Event Listeners ----------

            // Close button - PRIMARY FIX
            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeSidebar();
                });
            }

            // Overlay click
            if (overlay) {
                overlay.addEventListener('click', function (e) {
                    e.preventDefault();
                    closeSidebar();
                });
            }

            // Open button (hamburger menu)
            if (openBtn) {
                openBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    openSidebar();
                });
            } else {
                // Retry after delay in case it loads later
                setTimeout(function () {
                    const laterOpenBtn = document.getElementById('openSidebar');
                    if (laterOpenBtn) {
                        laterOpenBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            openSidebar();
                        });
                    }
                }, 500);
            }

            // Desktop toggle
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    toggleSidebarMinimize();
                });
            }

            // Close sidebar when clicking links on mobile
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (isMobile()) {
                        setTimeout(closeSidebar, 100); // Small delay for better UX
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                    sidebar.classList.remove('-translate-x-full');
                } else {
                    if (isMinimized) {
                        sidebar.classList.replace('w-20', 'w-72');
                        if (toggleIcon) {
                            toggleIcon.classList.replace('fa-chevron-right', 'fa-chevron-left');
                        }
                        sidebarTexts.forEach(text => text.classList.remove('hidden'));
                        isMinimized = false;
                    }
                }
            });
        }

        // Initialize
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebar);
        } else {
            initSidebar();
        }
    })();
</script>