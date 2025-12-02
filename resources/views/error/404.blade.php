<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found - Agribusiness Market Matching Platform</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-green-600">404</h1>
            <p class="mt-4 text-lg text-gray-600">Page Not Found</p>
            <a href="{{ url('/') }}"
                class="mt-6 inline-block px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-md hover:bg-green-500">
                Go to Homepage
            </a>
        </div>
    </div>
</body>

</html>
