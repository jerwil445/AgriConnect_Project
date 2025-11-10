@extends('layouts.app')
@vite('resources/css/app.css')

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

                <!-- Toggle buttons -->
                <div class="flex justify-around mt-3 bg-zinc-100 p-2 rounded-sm relative">
                    <div id="highlight"
                        class="absolute bg-green-500 w-1/2 h-full top-0 left-0 rounded-md transition-all duration-300">
                    </div>
                    <button type="button" id="farmerBtn" name="role" value="farmer"
                        class="z-20 relative font-semibold text-gray-700">Farmer</button>
                    <button type="button" id="buyerBtn" name="role" value="buyer"
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
                                <input type="text" id="first_name" name="first_name"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " required />
                                <label for="first_name"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    First Name
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" id="last_name" name="last_name"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " required />
                                <label for="last_name"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    Last Name
                                </label>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <div class="relative w-full">
                                <input type="text" id="phone" name="phone_number"
                                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " />
                                <label for="phone"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    Phone
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" id="city" name="city_region"
                                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " />
                                <label for="city"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    City/Region
                                </label>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="text" id="address" name="address"
                                class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder=" " />
                            <label for="address"
                                class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                Full Address
                            </label>
                        </div>

                        <div class="relative">
                            <input type="email" id="email" name="email"
                                class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder=" " required />
                            <label for="email"
                                class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                Email
                            </label>
                        </div>

                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder=" " required />
                            <label for="password"
                                class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                Password
                            </label>
                        </div>
                    </div>

                    <!-- FARMER INFO -->
                    <div class="relative w-full">
                        <div class="farmer-info-container flex flex-col space-y-4 bg-white pb-2 border-green-400 absolute top-0 z-10 rounded-sm w-full"
                            id="farm-info">
                            <h4 class="text-md font-semibold text-gray-700 border-b-2 border-green-500 pb-2">
                                Farm Information
                            </h4>

                            <div class="relative">
                                <input type="text" name="farm_name" id="f_farmname"
                                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder=" " />
                                <label for="f_farmname"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                                    Farm Name (Optional)
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="farm_size" id="farm-size"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " />
                                <label for="farm-size"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Farm Size (Acres/Hectares)
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="years_experience" id="year-experience"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " />
                                <label for="year-experience"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Years of Experience
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="product_type" id="product_type"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " />
                                <label for="product_type"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Product Type/s
                                </label>
                            </div>
                        </div>

                        <!-- BUYER INFO -->
                        <div class="buyer-info-container flex flex-col space-y-4 bg-white w-full rounded-sm hidden"
                            id="buyer-info">
                            <h4 class="text-md font-semibold text-gray-700 border-b-2 border-green-500 pb-2">
                                Buyer Business Information
                            </h4>

                            <div class="relative w-full">
                                <input type="text" id="company-name" name="company_name"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " />
                                <label for="company-name"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Business/Company Name (Optional)
                                </label>
                            </div>

                            <div class="business_type"> <select name="business_type" id="business_type"
                                    class = "focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    required>
                                    <option value="" disabled selected hidden></option>
                                    <option value="wholesaler">Wholesaler</option>
                                    <option value="retailer">Retailer</option>
                                    <option value="exporter">Exporter</option>
                                    <option value="processor">Processor</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="others">Others:</option>
                                </select>
                                <label for="business_type" class = "">Business Type</label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" id="preferred_products" name="preferred_products"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " />
                                <label for="preferred_products"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Preferred Products
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" id="b_address" name="b_address"
                                    class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder=" " />
                                <label for="b_address"
                                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                                    Address
                                </label>
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
        </div>
    </div>
</div>
