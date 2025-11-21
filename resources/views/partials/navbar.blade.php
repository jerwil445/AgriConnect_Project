<header class="fixed w-full z-50 bg-white shadow-md ">
    <nav class="container mx-auto flex justify-between items-center p-3">
        <h1 class="flex items-center gap-1 text-2xl font-bold text-green-700">
            <svg class="w-9 h-9 text-green-500 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
            </svg>
            <span><a href="{{ route('home') }}">ArgiConnect</a></span>
        </h1>

        <ul class="flex space-x-6 items-center">
            <li><a href="{{ route('home') }}" class="hover:text-green-600 font-medium">Home</a></li>
            @if (!Route::is('login') && !Route::is('register'))
                <li><a href="#about" class="hover:text-green-600 font-medium">About</a></li>
                <li><a href="#how-it-work" class="hover:text-green-600 font-medium">How it Works</a></li>
                <li><a href="#alumni" class="hover:text-green-600 font-medium">Alumni</a></li>
                <li><a href="#contact" class="hover:text-green-600 font-medium">Contact</a></li>
            @endif
            {{-- Always show login/register links --}}
            <div class="space-x-4">
                <a href="{{ route('login') }}"
                    class="bg-white text-green-600 px-4 py-1 border border-green-600 rounded hover:bg-green-600 hover:text-white transition-all duration-500">Login</a>
                <a href="{{ route('register') }}"
                    class="bg-green-600 text-white px-4 py-1  border rounded hover:bg-white hover:text-green-600 hover:border-green-600 transition-all duration-500">Register</a>
            </div>
        </ul>
    </nav>
</header>