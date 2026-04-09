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
    {{-- Navbar --}}
    @auth
        @if(Auth::user()->buyer)
            @include('partials.navbar')
        @else
            @include('partials.navbar')
        @endif
    @else
        @include('partials.navbar')
    @endauth

    {{-- Page Content --}}
    <main class="pt-24">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    <!-- Scripts -->
    @vite('resources/js/app.js')
</body>

</html>