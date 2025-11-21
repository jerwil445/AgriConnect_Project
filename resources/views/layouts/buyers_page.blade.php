<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Buyer Dashboard • AgriConnect')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div class="min-h-screen flex flex-col bg-gray-50">
        <!-- Include buyer header -->
        @include('partials.buyers.header')

        <div class="flex-1 flex flex-col overflow-hidden pt-0">
            <main class="flex-1 overflow-y-auto px-4 bg-gray-50 buyer-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User dropdown functionality
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown-menu');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Mobile sidebar functionality
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    // Handle mobile sidebar toggle if needed
                });
            }
        });
    </script>
</body>

</html>