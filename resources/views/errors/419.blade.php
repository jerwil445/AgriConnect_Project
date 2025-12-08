<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - AgriConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-4xl text-yellow-600"></i>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Session Expired
            </h1>

            <!-- Message -->
            <p class="text-gray-600 mb-6">
                Your session has expired for security reasons. This usually happens when:
            </p>

            <!-- Reasons -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>You've been inactive for too long</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>You logged out in another tab/window</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>The page was open for too long</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login Again
                </a>

                <a href="{{ route('home') }}" 
                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    <i class="fas fa-home mr-2"></i>
                    Go to Homepage
                </a>
            </div>

            <!-- Help Text -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    For security, please log in again to continue.
                </p>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">
                <i class="fas fa-lightbulb mr-1"></i> Pro Tip: Testing Multiple Users
            </h3>
            <p class="text-xs text-blue-700">
                To test multiple users simultaneously, use different browsers:
            </p>
            <ul class="text-xs text-blue-700 mt-2 space-y-1">
                <li>• Admin → Chrome</li>
                <li>• Farmer → Firefox</li>
                <li>• Buyer → Edge or Incognito</li>
            </ul>
        </div>
    </div>
</body>
</html>
