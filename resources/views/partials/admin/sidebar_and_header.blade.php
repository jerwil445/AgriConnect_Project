<body class="bg-gray-100">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 bg-green-500 shadow-lg z-30 flex items-center justify-between px-4 py-3  ">

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
                    <img class="h-9 w-9 rounded-full object-cover"
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&w=256&h=256&q=80"
                        alt="User profile">
                    <span class="text-white hidden md:block font-medium">John Doe</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 p-2"
                    id="user-dropdown">
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100 rounded-md">Profile</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100  rounded-md">Settings</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100 rounded-md">Sign
                        out</a>
                </div>
            </div>
        </div>
    </header>


    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-screen w-64 bg-white shadow-lg z-40 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h1 class="flex items-center gap-1 text-2xl font-bold text-green-700">
                <svg class="w-9 h-9 text-green-500 " xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
                </svg>
                <span>ArgiConnect</span>
            </h1>
            <button id="close-btn" class="md:hidden text-gray-500">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
               {{ request()->routeIs('admin.dashboard') || request()->path() == 'admin' ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                </svg>

                Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300
                {{ request()->routeIs('admin.users.*') ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2"
                        d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

               Users
            </a>
            <a href="#"
                class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium transition-all duration-300">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
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



    <script>
        // Sidebar toggle for mobile
        const menuBtn = document.getElementById('menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const closeBtn = document.getElementById('close-btn');

        menuBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // User dropdown toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        document.addEventListener('click', (e) => {
            if (userMenuButton.contains(e.target)) {
                userDropdown.classList.toggle('hidden');
            } else {
                userDropdown.classList.add('hidden');
            }
        });
    </script>

</body>