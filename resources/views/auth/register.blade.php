@extends('layouts.app')
@vite('resources/css/app.css')

@section('content')
<div id="registerModal"
    class="fixed inset-0 bg-gray-100 flex items-center justify-center overflow-y-auto transition-all duration-300">
    <!-- Inner content container -->
    <div class="bg-white flex mt-20 items-center shadow-lg rounded-lg space-x-6 md:w-7/12 h-5/6 relative">

        <div class="text-center w-full relative transform transition-transform duration-300">


            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" id="farmerForm"
                class="mt-3 transition-opacity duration-300 flex-col w-full h-full">
                @csrf

                <!-- Hidden role input -->
                @php($activeRole = old('role', 'farmer'))
                <input type="hidden" name="role" id="roleInput" value="{{ $activeRole }}" required>



                <div class="flex my-4 space-x-4 h-full ">

                    <div
                        class="w-10/12 space-x-4 flex flex-col justify-around bg-gradient-to-r from-green-800 to-emerald-500  backdrop-opacity-50 sticky top-0 rounded-l-lg">
                        <!-- Header -->
                        <div
                            class="text-2xl  font-bold text-green-700 mb-1 flex ml-5 justify-center items-center space-x-2 mt-2">
                            <div
                                class="relative flex items-center justify-center w-10 h-10 bg-green-500 rounded-xl group-hover:bg-green-200 transition-colors duration-300">
                                <svg class="w-5 h-5 text-green-100 transform text-center group-hover:rotate-12 transition-transform duration-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
                                </svg>
                            </div>
                            <span class="font-bold text-green-100 text-3xl">AgriConnect</span>

                        </div>
                        <div class="">
                            <p class="text-white text-4xl font-bold mt-1 mb-3">Grow together, <br> Trust grows with us
                            </p>
                            <p class="text-green-100 text-sm mt-1 mb-3">Join thousands of Farmers and Buyers on the
                                Philippines premier Agricultural Project.</p>
                        </div>
                        <div class="space-y-4 px-5 my-4">
                            <div class=" flex items-center justify-start space-x-2  ">
                                <span class="bg-green-500/50 backdrop-blur-md p-3 border-green-600 rounded-md ">
                                    <i class="fa-solid fa-check text-white"></i>
                                </span>
                                <span class="text-left">

                                    <p class="text-white">Direct Connections</p>
                                    <p class="text-green-200 text-sm">Connect the farmer Directly with verified buyers
                                    </p>

                                </span>

                            </div>
                            <div class=" flex items-center justify-start space-x-2  ">
                                <span class="bg-green-500/50 backdrop-blur-md p-3 border-green-600 rounded-md ">
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.5 11.5 11 13l4-3.5M12 20a16.405 16.405 0 0 1-5.092-5.804A16.694 16.694 0 0 1 5 6.666L12 4l7 2.667a16.695 16.695 0 0 1-1.908 7.529A16.406 16.406 0 0 1 12 20Z" />
                                    </svg>

                                </span>
                                <span class="text-left">

                                    <p class="text-white">Verified & Secure</p>
                                    <p class="text-green-200 text-sm">KYC-verified account for safe transaction</p>

                                </span>

                            </div>
                            <div class=" flex items-center justify-start space-x-2  ">
                                <span class="bg-green-500/50 backdrop-blur-md p-3 border-green-600 rounded-md ">
                                    <i class="fa-solid fa-arrow-trend-up text-white"></i>
                                </span>
                                <span class="text-left">

                                    <p class="text-white">Real-Time Market </p>
                                    <p class="text-green-200 text-sm">Live pricing and Order managemant tools</p>

                                </span>

                            </div>
                        </div>
                    </div>
                    <div class="w-full overflow-y-auto pr-4 py-8 rounded-r-lg">
                        <div class=" mb-4">
                            <div class="mb-5">
                                <h1
                                    class="text-3xl font-bold text-green-700 mb-1 flex items-center justify-center   space-x-2 mt-2">
                                    Create your account</h1>
                                <h3 class="text-gray-500 text-sm mt-1 mb-3">Fill your details to get started</h3>
                            </div>
                            <!-- Toggle buttons -->
                            <div
                                class="flex justify-around mt-3 bg-zinc-100 p-3 rounded-lg relative sticky top-0 shadow-inner  ">
                                <div id="highlight"
                                    class="absolute bg-gradient-to-r from-green-600 to-green-500 w-1/2 h-9 top-1.5 left-2 rounded-lg transition-all duration-300">
                                </div>
                                <button type="button" id="farmerBtn" data-role="farmer"
                                    class="z-20 relative text-white font-semibold text-gray-700">Farmer</button>
                                <button type="button" id="buyerBtn" data-role="buyer"
                                    class="z-20 relative  font-semibold text-gray-700">Buyer</button>
                            </div>
                        </div>
                        <div class="space-y-4 w-full py-2">

                            <!-- Personal Information -->
                            <div class="personal-info text-center">
                                <h4 class="farm-h4 text-md font-bold text-gray-700 border-b-2 border-green-500 pb-2">
                                    Farmer Personal Information
                                </h4>
                                <h4
                                    class="buyer-h4 text-md font-bold text-gray-700 border-b-2 border-green-500 pb-2 hidden">
                                    Buyer Personal Information
                                </h4>
                            </div>

                            <div class="flex space-x-2">
                                <div class="relative w-full">
                                    @php($firstNameError = $errors->first('first_name'))
                                    <input type="text" id="first_name" name="first_name"
                                        class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        placeholder=" " value="{{ old('first_name') }}" required />
                                    <label for="first_name"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
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
                                        class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        placeholder=" " value="{{ old('last_name') }}" required />
                                    <label for="last_name"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
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
                                        class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                        placeholder=" " value="{{ old('phone_number') }}" required />
                                    <label for="phone"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
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
                                        class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                        placeholder=" " value="{{ old('city_region') }}" required />
                                    <label for="city"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
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
                                    class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " value="{{ old('address') }}" required />
                                <label for="address"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
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
                                    class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " value="{{ old('email') }}" required />
                                <label for="email"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
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
                                    class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " required />
                                <label for="password"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
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
                                    class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " required />
                                <label for="password_confirmation"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    Confirm Password
                                </label>
                                <p class="input-error text-sm text-red-600 mt-1 {{ $passwordConfirmationError ? '' : 'hidden' }}"
                                    data-error-for="password_confirmation">
                                    {{ $passwordConfirmationError }}
                                </p>
                            </div>
                        </div>

                        <!-- FARMER INFO -->
                        <div class="relative w-full mt-4">
                            <div class="farmer-info-container flex flex-col space-y-4 bg-white pb-2 border-green-400 rounded-sm w-full {{ $activeRole === 'buyer' ? 'hidden' : '' }}"
                                id="farm-info">
                                <h4 class="text-md font-bold text-gray-700 border-b-2 border-green-500 pb-2">
                                    Farm Information
                                </h4>

                                <div class="relative">
                                    <input type="text" name="farm_name" id="f_farmname" data-role-field="farmer"
                                        @disabled($activeRole === 'buyer')
                                        class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                        placeholder=" " value="{{ old('farm_name') }}" />
                                    <label for="f_farmname"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                        Farm Name (Optional)
                                    </label>
                                </div>

                                <div class="flex space-x-2">
                                    <div class="relative w-full">
                                        @php($farmSizeError = $errors->first('farm_size'))
                                        <input type="number" name="farm_size" id="farm-size" step="0.01" min="0"
                                            data-role-field="farmer" @disabled($activeRole === 'buyer')
                                            class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            placeholder=" " value="{{ old('farm_size') }}" data-role-required="farmer"
                                            required />
                                        <label for="farm-size"
                                            class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                            Farm Size (Acres/Hectares)
                                        </label>
                                        <p class="input-error text-sm text-red-600 mt-1 {{ $farmSizeError ? '' : 'hidden' }}"
                                            data-error-for="farm_size">
                                            {{ $farmSizeError }}
                                        </p>
                                    </div>

                                    <div class="relative w-full">
                                        @php($experienceError = $errors->first('experience_years'))
                                        <input type="number" name="experience_years" id="year-experience" min="0"
                                            max="100" step="1" data-role-field="farmer" @disabled($activeRole === 'buyer')
                                            class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            placeholder=" " value="{{ old('experience_years') }}"
                                            data-role-required="farmer" required />
                                        <label for="year-experience"
                                            class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                            Years of Experience
                                        </label>
                                        <p class="input-error text-sm text-red-600 mt-1 {{ $experienceError ? '' : 'hidden' }}"
                                            data-error-for="experience_years">
                                            {{ $experienceError }}
                                        </p>
                                    </div>
                                </div>

                                <div class="relative w-full">
                                    @php($productTypeError = $errors->first('product_type'))
                                    <input type="text" name="product_type" id="product_type" data-role-field="farmer"
                                        @disabled($activeRole === 'buyer')
                                        class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        placeholder=" " value="{{ old('product_type') }}" data-role-required="farmer"
                                        required />
                                    <label for="product_type"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                        Product Type/s
                                    </label>
                                    <p class="input-error text-sm text-red-600 mt-1 {{ $productTypeError ? '' : 'hidden' }}"
                                        data-error-for="product_type">
                                        {{ $productTypeError }}
                                    </p>
                                </div>
                            </div>

                            <!-- BUYER INFO -->
                            <div class="buyer-info-container flex flex-col space-y-4 bg-white w-full pb-2 border-green-400 rounded-sm {{ $activeRole === 'buyer' ? '' : 'hidden' }}"
                                id="buyer-info">
                                <h4 class="text-md font-bold text-gray-700 border-b-2 border-green-500 pb-2">
                                    Buyer Business Information
                                </h4>



                                <div class="relative w-full">
                                    <input type="text" id="company-name" name="company_name" data-role-field="buyer"
                                        @disabled($activeRole !== 'buyer')
                                        class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        placeholder=" " value="{{ old('company_name') }}" />
                                    <label for="company-name"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                        Business/Company Name (Optional)
                                    </label>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="relative w-full">
                                        @php($businessTypeError = $errors->first('business_type'))
                                        <select name="business_type" id="business_type" data-role-field="buyer"
                                            @disabled($activeRole !== 'buyer')
                                            class="peer w-full px-3 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
                                            data-role-required="buyer" required>
                                            <option value="" disabled {{ old('business_type') ? '' : 'selected' }}
                                                hidden>
                                            </option>
                                            <option value="wholesaler" {{ old('business_type') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                                            <option value="retailer" {{ old('business_type') == 'retailer' ? 'selected' : '' }}>Retailer</option>
                                            <option value="exporter" {{ old('business_type') == 'exporter' ? 'selected' : '' }}>Exporter</option>
                                            <option value="processor" {{ old('business_type') == 'processor' ? 'selected' : '' }}>Processor</option>
                                            <option value="restaurant" {{ old('business_type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                            <option value="others" {{ old('business_type') == 'others' ? 'selected' : '' }}>
                                                Others</option>
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
                                        <input type="text" id="preferred_products" name="preferred_products"
                                            data-role-field="buyer" @disabled($activeRole !== 'buyer')
                                            class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            placeholder=" " value="{{ old('preferred_products') }}"
                                            data-role-required="buyer" required />
                                        <label for="preferred_products"
                                            class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                            Preferred Products
                                        </label>
                                        <p class="input-error text-sm text-red-600 mt-1 {{ $preferredProductsError ? '' : 'hidden' }}"
                                            data-error-for="preferred_products">
                                            {{ $preferredProductsError }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative w-full">
                                    @php($buyerAddressError = $errors->first('buyer_address'))
                                    <input type="text" id="b_address" name="buyer_address" data-role-field="buyer"
                                        @disabled($activeRole !== 'buyer')
                                        class="peer w-full px-2 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        placeholder=" " value="{{ old('buyer_address') }}" data-role-required="buyer"
                                        required />
                                    <label for="b_address"
                                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                        Address
                                    </label>
                                    <p class="input-error text-sm text-red-600 mt-1 {{ $buyerAddressError ? '' : 'hidden' }}"
                                        data-error-for="buyer_address">
                                        {{ $buyerAddressError }}
                                    </p>
                                </div>
                            </div>
                            <!-- Submit -->
                            <button type="submit"
                                class="w-full mt-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-md py-2.5 font-semibold hover:bg-green-600">
                                Register
                            </button>
                            <p class="text-center text-sm text-gray-600 mt-2">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">
                                    Login here
                                </a>
                            </p>
                        </div>

                    </div>
                </div>


            </form>



            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
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