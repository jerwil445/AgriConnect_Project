<header class="bg-white shadow-sm buyer-page-header" style="position: -webkit-sticky; position: sticky; top: 0; z-index: 1000;">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <button id="mobileSidebarToggle"
                    class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-bars text-gray-700"></i>
                </button>
                <h1 class="flex items-center gap-1 text-2xl font-bold text-green-700">
                    <svg class="w-9 h-9 text-green-500 " xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
                    </svg>
                    <span>ArgiConnect</span>
                </h1>

            </div>

            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('buyer.dashboard') }}" 
                   class="text-gray-600 hover:text-primary-700 font-medium transition duration-200 pb-1
                          {{ request()->routeIs('buyer.dashboard') ? 'text-primary-700 border-b-2 border-primary-500' : '' }}">
                    Home
                </a>
                
                <a href="#" 
                   class="text-gray-600 hover:text-primary-700 font-medium transition duration-200 pb-1
                          {{ request()->is('messages*') ? 'text-primary-700 border-b-2 border-primary-500' : '' }}">
                    Messages
                </a>
                <a href="#" 
                   class="text-gray-600 hover:text-primary-700 font-medium transition duration-200 pb-1
                          {{ request()->is('analytics*') ? 'text-primary-700 border-b-2 border-primary-500' : '' }}">
                    Analytics
                </a>
                <a href="{{ route('demands.index') }}" 
                   class="text-gray-600 hover:text-primary-700 font-medium transition duration-200 pb-1
                          {{ request()->routeIs('demands.*') ? 'text-primary-700 border-b-2 border-primary-500' : '' }}">
                    My Demands
                </a>
                
            </nav>

            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    
            <button type="button" class="relative rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                    <span class="absolute -inset-1.5"></span>
                    <span class="sr-only">View notifications</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Profile dropdown with buyer name -->
                <div class="relative ml-3 flex items-center">
                    <!-- Buyer name display -->
                    <div class="text-right mr-3 hidden md:block">
                        @auth
                            <p class="text-sm font-medium text-gray-700">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            </p>
                            @if(Auth::user()->buyer)
                                <!-- <p class="text-xs text-gray-500">{{ Auth::user()->buyer->company_name }}</p> -->
                            @endif
                        @endauth
                    </div>
                    
                    <!-- Profile dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="relative flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-8 rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10" />
                        </button>

                        <div id="user-dropdown-menu" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <a href="{{ route('buyer.profile') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1">Your profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1">Settings</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1">Sign out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container mx-auto px-4 py-2">
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="md:hidden mb-4 flex space-x-2">
        <a href="{{ route('demands.index') }}"
            class="flex-1 flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 text-sm font-semibold">
            <i class="fas fa-list"></i>
            My Demands
        </a>
        <form action="{{ route('logout') }}" method="POST" class="flex-1">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 border border-primary-500 text-primary-700 px-4 py-2 rounded-lg hover:bg-primary-50 transition duration-200 text-sm font-semibold">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>
</div>
