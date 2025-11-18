<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farmer Dashboard • AgriConnect')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div class="min-h-screen flex bg-gray-50">
        @include('partials.farmers.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('partials.farmers.header')

            <main class="flex-1 overflow-y-auto px-6 py-8 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
