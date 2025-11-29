@extends('layouts.app')
@vite('resources/css/app.css')

@section('content')
<div id="registerModal"
    class="fixed inset-0 bg-gray-100 flex items-center justify-center overflow-hidden transition-all duration-300">
    <!-- Inner content container -->
    <div class="bg-white flex mt-16 items-center shadow-lg rounded-md p-6 space-x-6 md:w-2/4 relative">

        <div class="text-center w-full h-full relative transform transition-transform duration-300">
            <!-- Header -->
            <h2 class="text-2xl font-bold text-green-700 mb-1 flex items-center justify-center space-x-2 mt-2">
                <svg class="w-6 h-6 text-green-400 animate-bounce" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17,8C8,10,5.9,16.17,3.82,21.34L5.71,22l1.47-4.5C9,17,17,15,21,15c0-9-4-7-4-7Z" />
                </svg>
                <span>Create your account</span>
            </h2>

            <p class="text-gray-500 text-sm mt-1 mb-3">Join our network of Farmers and Buyers.</p>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" id="farmerForm"
                class="mt-3 transition-opacity duration-300 flex-col w-full">
                @csrf

                <!-- Hidden role input -->
                @php($activeRole = old('role', 'farmer'))
                <input type="hidden" name="role" id="roleInput" value="{{ $activeRole }}" required>

                <!-- Toggle buttons -->
                <div class="flex justify-around mt-3 bg-zinc-100 p-2 rounded-sm relative">
                    <div id="highlight"
                        class="absolute bg-green-500 w-1/2 h-full top-0 left-0 rounded-md transition-all duration-300">
                    </div>
                    <button type="button" id="farmerBtn" data-role="farmer"
                        class="z-20 relative font-semibold text-gray-700">Farmer</button>
                    <button type="button" id="buyerBtn" data-role="buyer"
                        class="z-20 relative font-semibold text-gray-700">Buyer</button>
                </div>

                <div class="flex my-4">
                    <div class="space-y-4 w-full mr-4">
                        <!-- Personal Information -->
                        <div class="personal-info text-center">
                            <h4 class="farm-h4 text-md font-semibold text-gray-700 border-b-2 border-green-500 pb-2">
                                Farmer Personal Information
                            </h4>
                            <h4
                                class="buyer-h4 text-md font-semibold text-gray-700 border-b-2 border-green-500 pb-2 hidden">
                                Buyer Personal Information
                            </h4>
                        </div>

                        <div class="flex space-x-2">
                            <div class="relative w-full">
                                @php($firstNameError = $errors->first('first_name'))
                                <input type="text" id="first_name" name="first_name"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('first_name') }}" required />
                                <label for="first_name"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    First Name
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $firstNameError ? '' : 'hidden' }}"
                                    data-error-for="first_name">
                                    {{ $firstNameError }}
                                </p>
                            </div>

                            <div class="relative w-full">
                                @php($lastNameError = $errors->first('last_name'))
                                <input type="text" id="last_name" name="last_name"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('last_name') }}" required />
                                <label for="last_name"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    Last Name
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $lastNameError ? '' : 'hidden' }}"
                                    data-error-for="last_name">
                                    {{ $lastNameError }}
                                </p>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <div class="relative w-full">
                                @php($phoneError = $errors->first('phone_number'))
                                <input type="text" id="phone" name="phone_number"
                                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " value="{{ old('phone_number') }}" required />
                                <label for="phone"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    Phone
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $phoneError ? '' : 'hidden' }}"
                                    data-error-for="phone_number">
                                    {{ $phoneError }}
                                </p>
                            </div>

                            <div class="relative w-full">
                                @php($cityError = $errors->first('city_region'))
                                <input type="text" id="city" name="city_region"
                                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " value="{{ old('city_region') }}" required />
                                <label for="city"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    City/Region
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $cityError ? '' : 'hidden' }}"
                                    data-error-for="city_region">
                                    {{ $cityError }}
                                </p>
                            </div>
                        </div>

                        <div class="relative">
                            @php($addressError = $errors->first('address'))
                            <input type="text" id="address" name="address"
                                class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder=" " value="{{ old('address') }}" required />
                            <label for="address"
                                class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                Full Address
                            </label>
                            <p class="input-error text-sm text-red-600 mt-1 {{ $addressError ? '' : 'hidden' }}"
                                data-error-for="address">
                                {{ $addressError }}
                            </p>
                        </div>

                        <div class="relative">
                            @php($emailError = $errors->first('email'))
                            <input type="email" id="email" name="email"
                                class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder=" " value="{{ old('email') }}" required />
                            <label for="email"
                                class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                Email
                            </label>
                            <p class="input-error text-sm text-red-600 mt-1 {{ $emailError ? '' : 'hidden' }}"
                                data-error-for="email">
                                {{ $emailError }}
                            </p>
                        </div>

                        <div class="relative">
                            @php($passwordError = $errors->first('password'))
                            <input type="password" id="password" name="password"
                                class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder=" " required />
                            <label for="password"
                                class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                Password
                            </label>
                            <p class="input-error text-sm text-red-600 mt-1 {{ $passwordError ? '' : 'hidden' }}"
                                data-error-for="password">
                                {{ $passwordError }}
                            </p>
                        </div>

                        <div class="relative">
                            @php($passwordConfirmationError = $errors->first('password_confirmation'))
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder=" " required />
                            <label for="password_confirmation"
                                class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                Confirm Password
                            </label>
                            <p class="input-error text-sm text-red-600 mt-1 {{ $passwordConfirmationError ? '' : 'hidden' }}"
                                data-error-for="password_confirmation">
                                {{ $passwordConfirmationError }}
                            </p>
                        </div>
                    </div>

                    <!-- FARMER INFO -->
                    <div class="relative w-full">
                        <div class="farmer-info-container flex flex-col space-y-4 bg-white pb-2 border-green-400 absolute top-0 z-10 rounded-sm w-full {{ $activeRole === 'buyer' ? 'hidden' : '' }}"
                            id="farm-info">
                            <h4 class="text-md font-semibold text-gray-700 border-b-2 border-green-500 pb-2">
                                Farm Information
                            </h4>

                            <div class="relative">
                                <input type="text" name="farm_name" id="f_farmname" data-role-field="farmer"
                                    @if($activeRole === 'buyer') disabled @endif
                                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " value="{{ old('farm_name') }}" />
                                <label for="f_farmname"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    Farm Name (Optional)
                                </label>
                            </div>

                            <div class="relative w-full">
                                @php($farmSizeError = $errors->first('farm_size'))
                                <input type="number" name="farm_size" id="farm-size" step="0.01" min="0" data-role-field="farmer"
                                    @if($activeRole === 'buyer') disabled @endif
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('farm_size') }}" data-role-required="farmer" required />
                                <label for="farm-size"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Farm Size (Acres/Hectares)
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $farmSizeError ? '' : 'hidden' }}"
                                    data-error-for="farm_size">
                                    {{ $farmSizeError }}
                                </p>
                            </div>

                            <div class="relative w-full">
                                @php($experienceError = $errors->first('experience_years'))
                                <input type="number" name="experience_years" id="year-experience" min="0" max="100" step="1" data-role-field="farmer"
                                    @if($activeRole === 'buyer') disabled @endif
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('experience_years') }}" data-role-required="farmer" required />
                                <label for="year-experience"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Years of Experience
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $experienceError ? '' : 'hidden' }}"
                                    data-error-for="experience_years">
                                    {{ $experienceError }}
                                </p>
                            </div>

                            <div class="relative w-full">
                                @php($productTypeError = $errors->first('product_type'))
                                <input type="text" name="product_type" id="product_type" data-role-field="farmer"
                                    @if($activeRole === 'buyer') disabled @endif
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('product_type') }}" data-role-required="farmer" required />
                                <label for="product_type"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Product Type/s
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $productTypeError ? '' : 'hidden' }}"
                                    data-error-for="product_type">
                                    {{ $productTypeError }}
                                </p>
                            </div>
                        </div>

                        <!-- BUYER INFO -->
                        <div class="buyer-info-container flex flex-col space-y-4 bg-white w-full rounded-sm {{ $activeRole === 'buyer' ? '' : 'hidden' }}"
                            id="buyer-info">
                            <h4 class="text-md font-semibold text-gray-700 border-b-2 border-green-500 pb-2">
                                Buyer Business Information
                            </h4>

                            <div class="relative w-full">
                                <input type="text" id="company-name" name="company_name" data-role-field="buyer"
                                    @if($activeRole !== 'buyer') disabled @endif
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('company_name') }}" />
                                <label for="company-name"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Business/Company Name (Optional)
                                </label>
                            </div>

                            <div class="relative w-full">
                                @php($businessTypeError = $errors->first('business_type'))
                                <select name="business_type" id="business_type" data-role-field="buyer"
                                    @if($activeRole !== 'buyer') disabled @endif
                                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
                                    data-role-required="buyer" required>
                                    <option value="" disabled {{ old('business_type') ? '' : 'selected' }} hidden></option>
                                    <option value="wholesaler" {{ old('business_type') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                                    <option value="retailer" {{ old('business_type') == 'retailer' ? 'selected' : '' }}>Retailer</option>
                                    <option value="exporter" {{ old('business_type') == 'exporter' ? 'selected' : '' }}>Exporter</option>
                                    <option value="processor" {{ old('business_type') == 'processor' ? 'selected' : '' }}>Processor</option>
                                    <option value="restaurant" {{ old('business_type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                    <option value="others" {{ old('business_type') == 'others' ? 'selected' : '' }}>Others</option>
                                </select>
                                <label for="business_type"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all">
                                    Business Type
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $businessTypeError ? '' : 'hidden' }}"
                                    data-error-for="business_type">
                                    {{ $businessTypeError }}
                                </p>
                            </div>

                            <div class="relative w-full">
                                @php($preferredProductsError = $errors->first('preferred_products'))
                                <input type="text" id="preferred_products" name="preferred_products" data-role-field="buyer"
                                    @if($activeRole !== 'buyer') disabled @endif
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('preferred_products') }}" data-role-required="buyer" required />
                                <label for="preferred_products"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Preferred Products
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $preferredProductsError ? '' : 'hidden' }}"
                                    data-error-for="preferred_products">
                                    {{ $preferredProductsError }}
                                </p>
                            </div>

                            <div class="relative w-full">
                                @php($buyerAddressError = $errors->first('buyer_address'))
                                <input type="text" id="b_address" name="buyer_address" data-role-field="buyer"
                                    @if($activeRole !== 'buyer') disabled @endif
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " value="{{ old('buyer_address') }}" data-role-required="buyer" required />
                                <label for="b_address"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Address
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $buyerAddressError ? '' : 'hidden' }}"
                                    data-error-for="buyer_address">
                                    {{ $buyerAddressError }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-green-700 text-white rounded-md py-1.5 font-semibold hover:bg-green-600">
                    Register
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-2">
                Already have an account?
                <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">
                    Login here
                </a>
            </p>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include the register.js file directly for this page -->
@vite('resources/js/register.js')

@endsection