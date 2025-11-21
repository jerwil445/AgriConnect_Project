@php
    $farmerName = auth()->user()->first_name ?? 'Farmer';
@endphp

<header class="bg-white shadow-sm border-b border-gray-200 ml-64">
    <div class="px-4 lg:px-8 py-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Dashboard</p>
                <h1 class="text-2xl font-semibold text-gray-900">Welcome back, {{ $farmerName }}</h1>
                <p class="text-sm text-gray-400">Monitor your farm performance at a glance.</p>
            </div>
            <button id="menu-toggle" class="md:hidden text-gray-500">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <button
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-bell"></i>
                Alerts
            </button>
            <div class="relative">
                <button id="user-menu-button" type="button" 
                        class="flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition focus:outline-none">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" 
                         alt="User" class="h-6 w-6 rounded-full">
                    <span>Profile</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                
                <div id="user-dropdown" class="absolute right-0 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden z-50">
                    <div class="py-1" role="none">
                        <a href="{{ route('farmer.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>