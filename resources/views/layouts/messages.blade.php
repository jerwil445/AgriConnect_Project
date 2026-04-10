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
    
    @vite('resources/js/layouts/layout-messages.js')

</body>

</html>