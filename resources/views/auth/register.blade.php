@extends('layouts.app')

@vite('resources/css/app.css')

<div id="registerModal"
    class="modal fixed inset-0 backdrop-blur-sm bg-black bg-gray-100 flex items-center justify-center overflow-  transition-all duration-300">
    <!-- Inner content container -->
    <di 
        class="bg-white rounded-md text-center w-5/12 {{-- md:w-6/12 --}} max-h-[90vh] overflow-y-auto p-6 relative shadow-lg transform transition-transform duration-300">

        <!-- Close button -->
        <!-- <button onclick="closeModal('registerModal')"
            class="absolute  top-3 right-4 text-gray-500 hover:text-red-500 text-2xl">&times;</button> -->

        <!-- Header -->
        <h2 class="text-2xl font-bold text-green-700 mb-1 flex items-center justify-center space-x-2 mt-2">
            <svg class="w-6 h-6 text-green-400 animate-bounce" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17,8C8,10,5.9,16.17,3.82,21.34L5.71,22l1.47-4.5C9,17,17,15,21,15c0-9-4-7-4-7Z" />
            </svg>
            <span>Create your account</span>
        </h2>

        <p class="text-gray-500 text-sm mt-1 mb-3">Join our network of Farmers and Buyers.</p>

        <!-- Toggle buttons -->
        <div class="flex justify-around mt-3 bg-zinc-100 p-2 rounded-sm relative">
            <div id="highlight"
                class="absolute bg-green-500 w-1/2 h-full top-0 left-0 rounded-md  transition-all duration-300">
            </div>
            <button id="farmerBtn" class="z-20  relative font-semibold text-gray-700">Farmer</button>
            <button id="buyerBtn" class="z-20 relative font-semibold text-gray-700">Buyer</button>
        </div>

        <!-- FARMER FORM -->
        <form action="#" method="POST" id="farmerForm" class="space-y-4 mt-5 pb-6 transition-opacity duration-300  w-full  ">
            @csrf
            <div>
            <div class= "flex flex-col space-y-4 bg-green-100 border-green-400  pb- rounded-sm w-full"  id = "farm-info">
                <h4 class="text-lg font-semibold text-gray-700 border-b-2 border-green-500 pb-2">Farmer Information
                </h4>

                <div class="relative">
                    <input type="text" id="f_farmname"
                        class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                        placeholder=" " />
                    <label for="f_farmname"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                        Farm Name (Optional)
                    </label>
                </div>

                <div class="relative w-full">
                    <input type="text" id="farm-size"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="farm-size"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400  peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs ">
                        Farm Size (Acres/Hectares)
                    </label>
                </div>
                

                <div class="relative w-full">
                    <input type="text" id="year-experience"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="year-experience"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Years of Experience
                    </label>
                </div>

                <div class="relative w-full">
                    <input type="text" id="crop-specialization"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="crop-specialization"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Crops Specialization
                    </label>
                </div>

            </div>
            <div class= "flex flex-col space-y-4 bg-white  rounded-sm" id ="buyer-info">
                <h4 class="text-lg font-semibold text-gray-700 border-b-2 border-green-500 pb-2">Buyer Information</h4>

                {{-- <div class="business_type">
                    <select name="business_type" id="business_type"
                        class = "focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" required>
                        <option value="" disabled selected hidden></option>
                        <option value="wholesaler">Wholesaler</option>
                        <option value="retailer">Retailer</option>
                        <option value="exporter">Exporter</option>
                        <option value="processor">Processor</option>
                        <option value="restaurant">Restaurant</option>
                    </select>
                    <label for="business_type" class = "">Business Type</label>
                </div> --}}

                <div class=" relative w-full">
                    <input type="text" id="business_plan"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="business_plan"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Business Type
                    </label>
                </div>


                <div class=" relative w-full">
                    <input type="text" id="year-experience"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="year-experience"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Years of Experience
                    </label>
                </div>

                <div class="relative w-full">
                    <input type="text" id="crop-specialization"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="crop-specialization"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Crops Specialization
                    </label>
                </div>
            </div>
            </div>
            
                <div class="flex space-x-2">
                    <div class="relative w-full">
                        <input type="text" id="f_firstname"
                            class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                            placeholder=" " />
                        <label for="f_firstname"
                            class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                            Firstname
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="text" id="f_lastname"
                            class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                            placeholder=" " />
                        <label for="f_lastname"
                            class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                            Lastname
                        </label>
                    </div>
                </div>
                

                

                <div class="flex space-x-2">
                    <div class="relative w-full">
                        <input type="text" id="f_phone"
                            class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                            placeholder=" " />
                        <label for="f_phone"
                            class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                            Phone
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="text" id="f_city"
                            class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                            placeholder=" " />
                        <label for="f_city"
                            class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                            City/Region
                        </label>
                    </div>
                </div>

                <div class="relative">
                    <input type="text" id="f_address"
                        class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                        placeholder=" " />
                    <label for="f_address"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                        Full Address
                    </label>
                </div>



                <div class="relative">
                    <input type="email" id="f_email"
                        class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                        placeholder=" " />
                    <label for="f_email"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                        Email
                    </label>
                </div>

                <div class="relative">
                    <input type="password" id="f_password"
                        class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                        placeholder=" ">
                    <label for="f_password"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                        Password
                    </label>
                </div>
          

          

        </form>
 <button type="submit"
                class="w-full bg-green-700 text-white rounded-md py-1.5 font-semibold hover:bg-green-600">
                Register
            </button>
        <!-- BUYER FORM -->
        <!-- <form action="#" method="POST" id="buyerForm"
            class="space-y-4 mt-5 pb-6 pr-6 pl-6 opacity-0 pointer-events-none absolute left-0 top-32 w-full transition-all duration-300">
            @csrf
            <div class= "flex flex-col space-y-4 bg-white p-4 rounded-sm">
                <h4 class="text-lg font-semibold text-gray-700 border-b-2 border-green-500 pb-2">Buyer Information</h4>

                {{-- <div class="business_type">
                    <select name="business_type" id="business_type"
                        class = "focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" required>
                        <option value="" disabled selected hidden></option>
                        <option value="wholesaler">Wholesaler</option>
                        <option value="retailer">Retailer</option>
                        <option value="exporter">Exporter</option>
                        <option value="processor">Processor</option>
                        <option value="restaurant">Restaurant</option>
                    </select>
                    <label for="business_type" class = "">Business Type</label>
                </div> --}}

                <div class=" relative w-full">
                    <input type="text" id="business_plan"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="business_plan"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Business Type
                    </label>
                </div>


                <div class=" relative w-full">
                    <input type="text" id="year-experience"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="year-experience"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-green-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Years of Experience
                    </label>
                </div>

                <div class="relative w-full">
                    <input type="text" id="crop-specialization"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="crop-specialization"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 rounded-sm text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Crops Specialization
                    </label>
                </div>
            </div>
            <div class="flex space-x-2">
                <div class="relative w-full">
                    <input type="text" id="f_firstname"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="f_firstname"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600 peer-focus:text-xs">
                        Firstname
                    </label>
                </div>

                <div class="relative w-full">
                    <input type="text" id="f_lastname"
                        class="peer w-full px-2 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        placeholder=" " />
                    <label for="f_lastname"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                        Lastname
                    </label>
                </div>
            </div>

            <div class="relative">
                <input type="text" id="business-name"
                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                    placeholder=" " />
                <label for="business-name"
                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                    Business Name
                </label>
            </div>

            <div class="flex space-x-2">
                <div class="relative w-full">
                    <input type="text" id="f_phone"
                        class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                        placeholder=" " />
                    <label for="f_phone"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                        Phone
                    </label>
                </div>

                <div class="relative w-full">
                    <input type="text" id="f_city"
                        class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                        placeholder=" " />
                    <label for="f_city"
                        class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                        City/Region
                    </label>
                </div>
            </div>

            <div class="relative">
                <input type="text" id="f_address"
                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                    placeholder=" " />
                <label for="f_address"
                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                    Full Address
                </label>
            </div>



            <div class="relative">
                <input type="email" id="f_email"
                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                    placeholder=" " />
                <label for="f_email"
                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                    Email
                </label>
            </div>

            <div class="relative">
                <input type="password" id="f_password"
                    class="peer w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                    placeholder=" " />
                <label for="f_password"
                    class="absolute left-3 -top-2 text-sm bg-white px-1 text-gray-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-green-600">
                    Password
                </label>
            </div>

            <button type="submit"
                class="w-full bg-green-700 text-white rounded-md py-1.5 font-semibold hover:bg-green-600">
                Register
            </button>

        </form> -->
        <p class="text-center text-sm text-gray-600 mt-2">
            Already have an account?
            <a href = "{{ route('login')}}" class="text-green-600 font-semibold hover:underline">
                Login here
            </a>
        </p>
    </di>
</div>
