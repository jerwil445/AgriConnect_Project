@extends('layouts.app')

@vite('resources/css/app.css')

@section('content')
<div id="adminLogin"
    class="modal fixed text-center inset-0 bg-gray-100 flex items-center justify-center transition-all duration-300">
    <div class="bg-white rounded-md md:w-1/4 p-6 relative shadow-lg transform transition-transform duration-300 flex">

        <!-- Image Section (Left side) -->
        <!-- <div class="bg-white rounded-xl flex justify-center items-center h-full transition w-full p-4"> -->
        <!-- Using a solid green background placeholder for Admin, similar to the image layout -->
        <!-- <div class="w-full h-64 bg-green-700 rounded-lg flex items-center justify-center flex-col text-white shadow-inner">
                <svg class="w-16 h-16 mb-4 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <h3 class="text-xl font-bold">Admin Portal</h3>
                <p class="text-green-200 text-sm mt-2">Secure restricted access</p>
            </div> -->
        <!-- </div> -->

        <!-- Form Section (Right side) -->
        <div class="w-full flex flex-col justify-center items-center">

            <h2 class="text-2xl font-bold text-center text-green-700 mb-2 flex items-center justify-center space-x-2">
                <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Staff Login</span>
            </h2>

            <p class="text-gray-400 text-xs mb-6">Enter your administrator credentials below.</p>

            <!-- Success/Error Alerts -->
            @if (session('success'))
                <div class="w-full p-2 mb-4 bg-green-100 border border-green-500 text-green-700 rounded text-xs text-left">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="w-full p-2 mb-4 bg-red-100 border border-red-400 text-red-700 rounded text-xs text-left">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.perform') }}" method="POST" class="space-y-6 w-full text-left">
                @csrf

                <!-- Email -->
                <div class="relative mb-6">
                    @php($emailError = $errors->first('email'))
                    <input type="email" id="email" name="email"
                        class="peer w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " value="{{ old('email') }}" required autocomplete="email" />
                    <label for="email"
                        class="absolute left-3 -top-2 text-sm text-gray-600 bg-white px-1 transition-all duration-200 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-sm peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400">
                        Admin Email
                    </label>
                    @if($emailError)
                        <p class="text-sm text-red-600 mt-1">{{ $emailError }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div class="relative mb-4">
                    @php($passwordError = $errors->first('password'))
                    <input type="password" id="password" name="password"
                        class="peer w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " required autocomplete="current-password" />
                    <label for="password"
                        class="absolute left-3 -top-2 text-sm text-gray-600 bg-white px-1 transition-all duration-200 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-sm peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400">
                        Password
                    </label>
                    @if($passwordError)
                        <p class="text-sm text-red-600 mt-1">{{ $passwordError }}</p>
                    @endif
                </div>

                <div class="flex justify-between items-center mt-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember-me" name="remember" value="1"
                            class="h-4 w-4 border-gray-300 rounded focus:ring-green-700" />
                        <label for="remember-me" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-semibold text-gray-400 hover:text-gray-500">Forgot
                        password?</a>
                </div>

                <button type="submit"
                    class="w-full rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-green-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 mt-4">
                    Sign into Dashboard
                </button>

                <p class="text-center text-sm text-gray-600 mt-4">
                    <a href="{{ route('home') }}" class="text-green-600 font-semibold hover:underline">
                        &larr; Return to main site
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection