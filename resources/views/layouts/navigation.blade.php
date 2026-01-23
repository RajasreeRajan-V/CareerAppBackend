<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-[#306060] to-[#254848] 
    text-white flex flex-col shadow-2xl transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out overflow-hidden">

    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-3xl opacity-50"
        style="transform: translate(40%, -40%);"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full blur-2xl opacity-50"
        style="transform: translate(-40%, 40%);"></div>
    <div class="absolute top-1/3 right-0 w-20 h-20 bg-white/5 rounded-full blur-xl opacity-30"
        style="transform: translate(50%, 0);"></div>

    <!-- Mobile Close Button -->
    <button id="closeSidebar" class="lg:hidden absolute top-4 right-4 w-10 h-10 flex items-center justify-center 
    rounded-lg hover:bg-white/10 transition-colors z-20 hidden">
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
    <nav
  class="flex-1 px-4 py-6 space-y-1 relative z-10"
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

        <a href="{{ route('admin.college.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
   {{ request()->routeIs('admin.college.*') ? 'bg-white/15 shadow-lg' : '' }}">
            <i class="fa-solid fa-photo-film mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Manage Colleges</span>
        </a>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-users mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Users</span>
        </a>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-chart-line mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Analytics</span>
        </a>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-calendar-days mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Schedule</span>
        </a>

        <p class="px-4 mt-6 text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 sidebar-text">
            Settings
        </p>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-gear mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Settings</span>
        </a>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-circle-question mr-3 flex-shrink-0"></i>
            <span class="font-medium sidebar-text">Help & Support</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="px-4 py-4 border-t border-white/10 relative z-10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center px-4 py-3 rounded-xl hover:bg-red-500/20 transition-all">
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
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        let isMinimized = false;

        // Helper function to check if mobile
        function isMobile() {
            return window.innerWidth < 1024;
        }

        // Mobile sidebar functions
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');

            if (isMobile()) {
                closeBtn.classList.remove('hidden'); 
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            closeBtn.classList.add('hidden'); 
            document.body.style.overflow = '';
        }

        // Desktop toggle minimize function
        function toggleSidebarMinimize() {
            isMinimized = !isMinimized;

            if (isMinimized) {
                // Minimize sidebar
                sidebar.classList.remove('w-72');
                sidebar.classList.add('w-20');
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');

                // Hide text elements
                sidebarTexts.forEach(text => {
                    text.classList.add('opacity-0', 'hidden');
                });
            } else {
                // Expand sidebar
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-72');
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');

                // Show text elements
                sidebarTexts.forEach(text => {
                    text.classList.remove('opacity-0', 'hidden');
                });
            }
        }

        // Event listeners
        if (openBtn) {
            openBtn.addEventListener('click', openSidebar);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeSidebar);
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebarMinimize);
        }

        // Close sidebar when clicking on a link (mobile only)
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function () {
                if (isMobile()) {
                    closeSidebar();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (!isMobile()) {
                // Reset mobile state when switching to desktop
                overlay.classList.add('hidden');
                closeBtn.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });
</script>