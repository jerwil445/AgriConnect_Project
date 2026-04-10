<header class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-200/50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] transition-all duration-300" id="main-nav">
    <nav class="container mx-auto flex justify-between items-center px-4 py-3 md:py-4">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="group flex items-center gap-2">
            <div class="relative flex items-center justify-center w-10 h-10 bg-green-100 rounded-xl group-hover:bg-green-200 transition-colors duration-300">
                <svg class="w-6 h-6 text-green-700 transform group-hover:rotate-12 transition-transform duration-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
                </svg>
            </div>
            <span class="text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-green-800 to-emerald-500">
                AgriConnect
            </span>
        </a>

        <!-- Desktop Links -->
        <ul class="hidden md:flex space-x-8 items-center">
            <li><a href="{{ route('home') }}" class="relative text-gray-600 font-semibold hover:text-green-700 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-green-600 after:transition-all after:duration-300 hover:after:w-full">Home</a></li>
            @if (!Route::is('login') && !Route::is('register'))
                <li><a href="#about" class="relative text-gray-600 font-semibold hover:text-green-700 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-green-600 after:transition-all after:duration-300 hover:after:w-full">About</a></li>
                <li><a href="#how-it-work" class="relative text-gray-600 font-semibold hover:text-green-700 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-green-600 after:transition-all after:duration-300 hover:after:w-full">Process</a></li>
                <li><a href="#success-stories" class="relative text-gray-600 font-semibold hover:text-green-700 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-green-600 after:transition-all after:duration-300 hover:after:w-full">Success Stories</a></li>
                <li><a href="#contact" class="relative text-gray-600 font-semibold hover:text-green-700 transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-0 after:bg-green-600 after:transition-all after:duration-300 hover:after:w-full">Contact</a></li>
            @endif
            
            {{-- Always show login/register links --}}
            <div class="flex items-center space-x-3 pl-4 border-l border-gray-200">
                <a href="{{ route('login') }}"
                    class="text-green-700 font-bold px-6 py-2.5 rounded-full border-2 border-transparent hover:border-green-100 hover:bg-green-50 transition-all duration-300">
                    Log in
                </a>
                <a href="{{ route('register') }}"
                    class="bg-gradient-to-r from-green-600 to-emerald-500 text-white font-bold px-7 py-2.5 rounded-full shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.23)] hover:-translate-y-0.5 transition-all duration-300">
                    Register    
                </a>
            </div>
        </ul>
        
        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
            <button id="mobile-menu-button" class="p-2 rounded-lg text-gray-600 hover:bg-green-50 hover:text-green-700 focus:outline-none transition-colors">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path id="menu-icon-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 shadow-2xl transition-all duration-300">
        <div class="px-4 pt-4 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-700 hover:text-green-700 hover:bg-green-50 hover:pl-6 transition-all duration-300">Home</a>
            @if (!Route::is('login') && !Route::is('register'))
                <a href="#about" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-700 hover:text-green-700 hover:bg-green-50 hover:pl-6 transition-all duration-300">About</a>
                <a href="#how-it-work" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-700 hover:text-green-700 hover:bg-green-50 hover:pl-6 transition-all duration-300">Process</a>
                <a href="#success-stories" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-700 hover:text-green-700 hover:bg-green-50 hover:pl-6 transition-all duration-300">Success Stories</a>
                <a href="#contact" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-700 hover:text-green-700 hover:bg-green-50 hover:pl-6 transition-all duration-300">Contact</a>
            @endif
            
            <div class="pt-6 pb-2 mt-4 border-t border-gray-100 flex flex-col gap-3">
                <a href="{{ route('login') }}"
                    class="block w-full text-center bg-gray-50 text-green-700 px-4 py-3.5 border border-green-100 rounded-xl hover:bg-green-100 transition-all duration-300 font-bold text-lg">
                    Log in
                </a>
                <a href="{{ route('register') }}"
                    class="block w-full text-center bg-gradient-to-r from-green-600 to-emerald-500 text-white px-4 py-3.5 rounded-xl shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] hover:shadow-lg transition-all duration-300 font-bold text-lg">
                    Get Started
                </a>
            </div>
        </div>
    </div>
</header>

@vite('resources/js/partials/partial-navbar.js')