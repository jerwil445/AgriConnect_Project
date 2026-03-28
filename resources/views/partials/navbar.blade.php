<header class="fixed w-full top-0 z-50 bg-white shadow-md">
    <nav class="container mx-auto flex justify-between items-center p-2">
        <h1 class="flex items-center gap-2 text-2xl font-bold text-green-700">
            <svg class="w-10 h-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
            </svg>
            <span><a href="{{ route('home') }}" class="hover:text-green-800 transition-colors">ArgiConnect</a></span>
        </h1>

        <ul class="hidden md:flex space-x-8 items-center">
            <li><a href="{{ route('home') }}" class="hover:text-green-600 font-medium transition-colors">Home</a></li>
            @if (!Route::is('login') && !Route::is('register'))
                <li><a href="#about" class="hover:text-green-600 font-medium transition-colors">About</a></li>
                <li><a href="#how-it-work" class="hover:text-green-600 font-medium transition-colors">How it Works</a></li>
                <li><a href="#alumni" class="hover:text-green-600 font-medium transition-colors">Success Stories</a></li>
                <li><a href="#contact" class="hover:text-green-600 font-medium transition-colors">Contact</a></li>
            @endif
            {{-- Always show login/register links --}}
            <div class="space-x-3">
                <a href="{{ route('login') }}"
                    class="bg-white text-green-600 px-5 py-2 border border-green-600 rounded-md hover:bg-green-600 hover:text-white transition-all duration-300 font-medium">Login</a>
                <a href="{{ route('register') }}"
                    class="bg-green-600 text-white px-5 py-2 border border-green-600 rounded-md hover:bg-white hover:text-green-600 transition-all duration-300 font-medium">Register</a>
            </div>
        </ul>
        
        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
            <button id="mobile-menu-button" class="text-green-700 hover:text-green-900 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-green-700 hover:bg-green-50">Home</a>
            @if (!Route::is('login') && !Route::is('register'))
                <a href="#about" class="block px-3 py-2 rounded-md text-base font-medium text-green-700 hover:bg-green-50">About</a>
                <a href="#how-it-work" class="block px-3 py-2 rounded-md text-base font-medium text-green-700 hover:bg-green-50">How it Works</a>
                <a href="#alumni" class="block px-3 py-2 rounded-md text-base font-medium text-green-700 hover:bg-green-50">Success Stories</a>
                <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-green-700 hover:bg-green-50">Contact</a>
            @endif
            <div class="pt-4 pb-2 border-t border-gray-200">
                <a href="{{ route('login') }}"
                    class="block w-full text-center bg-white text-green-600 px-4 py-2 border border-green-600 rounded-full hover:bg-green-600 hover:text-white transition-all duration-300 font-medium mb-2">Login</a>
                <a href="{{ route('register') }}"
                    class="block w-full text-center bg-green-600 text-white px-4 py-2 border border-green-600 rounded-full hover:bg-white hover:text-green-600 transition-all duration-300 font-medium">Register</a>
            </div>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>