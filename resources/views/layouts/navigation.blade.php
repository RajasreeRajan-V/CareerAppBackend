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

    <!-- User Profile -->
    <div class="px-6 py-5 border-b border-white/10 relative z-10">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full overflow-hidden bg-white ring-2 ring-white/30">
                <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-white/70 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto relative z-10">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-2">
            Main Menu
        </p>

        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
        {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 shadow-lg' : '' }}">
            <i class="fa-solid fa-house mr-3"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('admin.admissionBanner.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all
   {{ request()->routeIs('admin.admissionBanner.*') ? 'bg-white/15 shadow-lg' : '' }}">
            <i class="fa-solid fa-photo-film mr-3"></i>
            <span class="font-medium">Admission Banner</span>
        </a>





        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-users mr-3"></i>
            <span class="font-medium">Users</span>
        </a>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-chart-line mr-3"></i>
            <span class="font-medium">Analytics</span>
        </a>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-calendar-days mr-3"></i>
            <span class="font-medium">Schedule</span>
        </a>

        <p class="px-4 mt-6 text-xs font-semibold text-white/50 uppercase tracking-wider mb-2">
            Settings
        </p>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-gear mr-3"></i>
            <span class="font-medium">Settings</span>
        </a>

        <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
            <i class="fa-solid fa-circle-question mr-3"></i>
            <span class="font-medium">Help & Support</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="px-4 py-4 border-t border-white/10 relative z-10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center px-4 py-3 rounded-xl hover:bg-red-500/20 transition-all">
                <i class="fa-solid fa-right-from-bracket mr-3"></i>
                Logout
            </button>
        </form>
    </div>
</aside>