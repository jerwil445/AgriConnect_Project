<body class="bg-gray-100">
    <!-- Header -->
    <header
        class="fixed top-0 left-0 right-0 bg-green-500 shadow-lg z-30 flex items-center justify-between px-4 py-3  ">
        <!-- Left: Mobile Menu Button -->
        <button id="menu-btn" class="md:hidden text-white focus:outline-none">
            <i class="fa-solid fa-bars text-2xl"></i>
        </button>

        <!-- Center: Search Bar -->
        <div class="flex-1 max-w-md mx-4 hidden sm:block">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <!-- <form method="GET" action="{{ route('admin.users.index') }}">
                    <input type="text" name="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 sm:text-sm"
                        placeholder="Search users..." value="{{ request('search') }}">
                </form> -->
            </div>
        </div>

        <!-- Right: Notifications + User Menu -->
        <div class="flex items-center gap-5">
            <!-- Notification Icon -->
            <button
                class="p-2 rounded-full text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>

            <!-- User Dropdown -->
            <div class="relative" id="user-menu">
                <button class="flex items-center space-x-2 focus:outline-none" id="user-menu-button">
                    <img class="h-9 w-9 rounded-full object-cover border border-white/20 shadow-sm"
                        src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) . '&background=dcfce7&color=14532d' }}"
                        alt="User profile">
                    <span class="text-white hidden md:block font-medium">{{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white transition-transform duration-200"
                        id="user-menu-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100 transform origin-top-right transition-all duration-200"
                    id="user-dropdown">
                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                    </div>
                    <a href="{{ route('admin.profile') }}"
                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                        <i class="fas fa-user-circle opacity-50"></i> Profile
                    </a>
                    <a href="{{ route('admin.settings') }}"
                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                        <i class="fas fa-cog opacity-50"></i> Settings
                    </a>
                    <div class="border-t border-gray-50 mt-1 pt-1">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt opacity-50"></i> Sign out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-screen w-64 bg-white shadow-lg z-40 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h1 class="flex items-center gap-1 text-2xl font-bold text-green-700">
                <svg class="w-9 h-9 text-green-500 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
                </svg>
                <span
                    class="text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-green-800 to-emerald-500">AgriConnect</span>
            </h1>
            <button id="close-btn" class="md:hidden text-gray-500">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
               {{ request()->routeIs('admin.dashboard') || request()->path() == 'admin' ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                </svg>

                Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
            {{ request()->routeIs('admin.users.*') ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2"
                        d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                Users
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
                {{ request()->routeIs('admin.products.*') ? 'bg-green-100 text-green-600' : '' }}">
                <i class="fas fa-box-open text-base text-black"></i>
                Products list
            </a>
            <a href="{{ route('admin.demands.index') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
                {{ request()->routeIs('admin.demands.*') ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4.5V19a1 1 0 0 0 1 1h15M7 14l4-4 4 4 5-5m0 0h-3.207M20 9v3.207" />
                </svg>

                Demands
            </a>
            <a href="{{ route('admin.matches.index') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
                {{ request()->routeIs('admin.matches.*') ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                Matches
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
                {{ request()->routeIs('admin.transactions.*') ? 'bg-green-100 text-green-600' : '' }}">
                <i class="fa-regular fa-handshake text-base text-gray-800 dark:text-black"></i>
                Transactions
            </a>
            <a href="{{ route('admin.verifications.index') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
                {{ request()->routeIs('admin.verifications.*') ? 'bg-green-100 text-green-600' : '' }}">
                <i class="fas fa-user-check text-base text-gray-800 dark:text-black"></i>
                Verifications
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
                {{ request()->routeIs('admin.settings') ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"
                        d="M10 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h2m10 1a3 3 0 0 1-3 3m3-3a3 3 0 0 0-3-3m3 3h1m-4 3a3 3 0 0 1-3-3m3 3v1m-3-4a3 3 0 0 1 3-3m-3 3h-1m4-3v-1m-2.121 1.879-.707-.707m5.656 5.656-.707-.707m-4.242 0-.707.707m5.656-5.656-.707.707M12 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                Settings
            </a>
        </nav>
    </aside>

    <!-- Overlay (for mobile) -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>

    @vite('resources/js/partials/admin/partial-admin-sidebar-header.js')

</body>