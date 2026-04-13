@extends('layouts.app')

@vite('resources/css/app.css')
@vite('resources/js/role-toggle.js')

<div id="loginModal"
    class="modal fixed text-center inset-0 bg-gray-100  flex items-center justify-center   transition-all duration-300">
    <div class="bg-white  rounded-md md:w-2/4  relative shadow-lg transform transition-transform duration-300 flex">

        <div class="bg-white rounded-xl  flex justify-center items-center h-full transition w-full">
            <img src="{{ asset('images/farmer.jpg') }} " class=" rounded-sm" alt="Alumni Talk"
                class="w-full  h-48 object-cover">
        </div>
        <div class="w-full   flex flex-col justify-center items-center bg-gray-100s">
            <!-- <button onclick="closeModal('loginModal')"
            class="absolute  top-3 right-4 text-gray-500 hover:text-red-500 text-2xl">&times;</button> -->
            <h2 class="text-2xl font-bold text-center text-green-700 mb-2 flex items-center justify-center space-x-2">
                <svg class="w-6 h-6 text-green-400 animate-bounce" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17,8C8,10,5.9,16.17,3.82,21.34L5.71,22l1.47-4.5C9,17,17,15,21,15c0-9-4-7-4-7Z" />
                </svg>
                <span>Argi-Connect </span>
            </h2>

            <p class="text-gray-400 text-xs ">Welcome! Please Login or Register to continue.</p>
            <form action="{{ route('login.perform') }}" method="POST" class="space-y-6 w-full p-5">
                @csrf
                <!-- Email -->
                <div class="relative mb-6">
                    @php($emailError = $errors->first('email'))
                    <input type="email" id="email" name="email"
                        class="peer w-full px-3 py-3 border {{ $emailError ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " value="{{ old('email') }}" required autocomplete="email" />
                    <label for="email"
                        class="absolute left-3 -top-2 text-sm text-gray-600 bg-white px-1 transition-all duration-200 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-sm peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400">
                        Email
                    </label>
                    @if($emailError)
                        <p class="text-sm text-red-600 mt-1">{{ $emailError }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div class="relative mb-4">
                    @php($passwordError = $errors->first('password'))
                    <input type="password" id="password" name="password"
                        class="peer w-full px-3 pr-10 py-3 border {{ $passwordError ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " value="{{ old('password') }}" required autocomplete="current-password" />
                    <button type="button"
                        class="absolute right-3 top-3.5 text-gray-400 hover:text-green-600 focus:outline-none"
                        onclick="togglePasswordVisibility('password', this)">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                    <label for="password"
                        class="absolute left-3 -top-2 text-sm text-gray-600 bg-white px-1 transition-all duration-200 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-sm peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400">
                        Password
                    </label>
                    @if($passwordError)
                        <p class="text-sm text-red-600 mt-1">{{ $passwordError }}</p>
                    @endif
                </div>

                <div class="flex justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember-me" name="remember" value="1"
                            class="h-4 w-4 border-gray-300 rounded focus:ring-green-700" />
                        <label for="remember-me" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-semibold text-gray-400 hover:text-gray-500">Forgot
                        password?</a>
                </div>

                <button type="submit"
                    class="w-full rounded-md bg-gradient-to-r from-green-500 to-green-600 px-3 py-3 text-sm font-semibold text-white shadow hover:bg-green-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                    Sign in
                </button>

                <p class="text-center text-sm text-gray-600 mt-4">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-green-600 font-semibold hover:underline">
                        Register here
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>