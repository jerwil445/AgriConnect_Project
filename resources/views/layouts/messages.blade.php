<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ArgiConnect - Connecting Farmers and Buyers')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased">
    @auth
        @if(Auth::user()->buyer)
            {{-- Buyer Header Only --}}
            @include('partials.buyers.header')
            <main class="">
                @yield('content')
            </main>
        @else
            {{-- Farmer Layout with Sidebar and Header --}}
            <div class="min-h-screen  bg-gray-50">
                @include('partials.farmers.sidebar')
                @include('partials.farmers.header')
                <div class="flex-1 flex flex-col overflow-hidden ml-64">
                    <main class="flex-1 overflow-y-auto pbg-gray-50">
                        @yield('content')
                    </main>
                </div>
            </div>
        @endif
    @endauth

    {{-- Footer --}}
    <!-- @include('partials.footer') -->

    <!-- Scripts -->
    @vite('resources/js/app.js')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Farmer sidebar functionality
            const menuToggle = document.getElementById('menu-toggle');
            const closeBtn = document.getElementById('close-btn');
            const sidebar = document.getElementById('sidebar');
            
            // User dropdown functionality - fixed to work with both farmer and buyer headers
            const userMenuButton = document.getElementById('user-menu-button');
            // Try both possible dropdown IDs
            const userDropdown = document.getElementById('user-dropdown-menu') || document.getElementById('user-dropdown');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar && sidebar.contains(event.target);
                const isClickOnMenuToggle = menuToggle && menuToggle.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnMenuToggle && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>
</body>

</html>