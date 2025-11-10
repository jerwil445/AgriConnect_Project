@extends('layouts.app')

@section('title', 'Home - Agribusiness Platform')

@section('content')

    <script>
        // Smooth scroll
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll("a[href^='#']").forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute("href")).scrollIntoView({
                        behavior: "smooth"
                    });
                });
            });
        });
    </script>


    <!-- 🏠 Home Section -->
    <section id="home"
        class="relative h-screen flex flex-col justify-center items-center bg-cover bg-center text-white text-center px-6 z-10"
        style="background-image: url('{{ asset('images/background_image.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div> <!-- Optional dark overlay -->

        <div class="relative z-10">
            <h2 class="text-5xl font-bold mb-4">Welcome to ArgiConnect</h2>
            <p class="max-w-2xl text-lg mb-6 leading-relaxed">
                A modern web-based platform to strengthen university engagement and connect our alumni community through
                profiles, stories, and events.
            </p>
            <div class="flex space-x-4 justify-center">
                <a href="#about"
                    class="bg-white text-green-700 font-semibold px-6 py-3 rounded-full shadow hover:bg-gray-100 transition">
                    Learn More
                </a>
                <button onclick="openModal('registerModal')"
                    class="border border-white font-semibold px-6 py-3 rounded-full hover:bg-white hover:text-green-700 transition">
                    Join Now
                </button>
            </div>
        </div>
    </section>


    <!-- 📖 About Section -->
    <section id="about" class="h-screen flex relative flex-col justify-center items-center bg-white text-center px-8">
        <h2 class="text-4xl font-bold text-green-700 mb-6">About Us</h2>
        <div class="max-w-4xl text-gray-600 text-lg leading-relaxed">
            <p>
                We are committed to revolutionizing the agricultural supply chain by empowering farmers and providing buyers
                with direct access to the freshest produce.Our platform is built on the principles of transparency, fairness
                and sustainability.
            </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 mt-10 max-w-5xl">
            <div
                class="bg-green-50 p-6 rounded-xl shadow-md hover:shadow-lg transition flex flex-col justify-center items-center text-center">
                <svg class="w-12 h-12 bg-green-200 rounded-lg p-2  text-green-600 mb-3" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                <h3 class="text-xl font-semibold text-green-600 mb-3">Profile Management</h3>
                <p>Alumni can update their information, achievements, and affiliations easily.</p>
            </div>

            <div
                class="bg-green-50 p-6 rounded-xl shadow-md hover:shadow-lg transition flex flex-col items-center text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-green-200 rounded-lg mb-3">
                    <i class="fa-regular fa-handshake text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-green-600 mb-3">Event Support</h3>
                <p>Manage and register for reunions, seminars, and university events.</p>
            </div>

            <div
                class="bg-green-50 p-6 rounded-xl shadow-md hover:shadow-lg transition flex flex-col items-center text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-green-200 rounded-lg mb-3">
                    <i class="fas fa-leaf text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-green-600 mb-3">Community Network</h3>
                <p>Connect with fellow alumni, mentors, and students for collaboration.</p>
            </div>

        </div>
    </section>

    <!-- 🎉 How it work Section -->
    <section id="how-it-work" class="h-screen w-full flex flex-col justify-center items-center bg-gray-100  px-8">
        <h2 class="text-4xl font-bold text-green-700 mt-14 mb-6">How it works </h2>
        <div class="flex border-b-2 pb-2 gap-5 border-gray-300 w-full justify-around items-center mb-4">
            <button class = "font-bold" id = "for-farmer-btn">
                For Farmers
            </button>
            <button class = "font-bold" id = "for-buyers-btn">
                For Buyers
            </button>
        </div>
        <div class="grid md:grid-cols-2 gap-8   h-2/3 w-full " id = "for-farmers">
            <div class="bg-white rounded-xl shadow-lg transition w-full flex justify-center flex-col items-center">
                <div class="    ">
                    <div class="flex   items-center p-6 gap-4">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-sm" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <div>
                            <h3 class = "font-bold">1. Register Your Farmer</h3>
                            <p class = "text-gray-400">Create profile to showcase your farm, your story, and commiment to
                                quality</p>
                        </div>
                    </div>
                    <div class="flex  items-center p-6 gap-4">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-sm" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                        </svg>

                        <div>
                            <h3 class = "font-bold">2. List Your Prodcucts</h3>
                            <p class = "text-gray-400">Easily upload your available product, including details about
                                quantily, pricing, and certification. </p>
                        </div>
                    </div>
                    <div class="flex  items-center p-6 gap-4 ">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-xs" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                        </svg>
                        <div>
                            <h3 class = "font-bold">3. Connect with Buyers</h3>
                            <p class = "text-gray-400">Receive inquiries directly from restaurants, retailers, and consumers
                                looking for fresh, local produce </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg flex justify-center items-center h-full transition w-full p-4">
                <img src="{{ asset('images/farmer.jpg') }} " class = "h-full w-full rounded-lg" alt="Alumni Talk"
                    class="w-full h-48 object-cover">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-8   h-2/3 w-full" id = "for-buyers">
            <div class="bg-white rounded-xl shadow-lg transition w-full flex justify-center flex-col items-center">
                <div class="    ">
                    <div class="flex   items-center p-6 gap-4">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-sm"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <div>
                            <h3 class = "font-bold">1. Register and Create Profile</h3>
                            <p class = "text-gray-400">Sign up and build your buyer profile with your specific needs </p>
                        </div>
                    </div>
                    <div class="flex  items-center p-6 gap-4">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-sm"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                        </svg>

                        <div>
                            <h3 class = "font-bold">2. Post Product Demands</h3>
                            <p class = "text-gray-400">Easily list the products you need to specifying quantity and
                                quality. </p>
                        </div>
                    </div>
                    <div class="flex  items-center p-6 gap-4 ">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-sm"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>


                        <div>
                            <h3 class = "font-bold">3. View Matching Products</h3>
                            <p class = "text-gray-400">Our platform matches your demands with available farm product. </p>
                        </div>
                    </div>
                    <div class="flex  items-center p-6 gap-4 ">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-sm"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11c.889-.086 1.416-.543 2.156-1.057a22.323 22.323 0 0 0 3.958-5.084 1.6 1.6 0 0 1 .582-.628 1.549 1.549 0 0 1 1.466-.087c.205.095.388.233.537.406a1.64 1.64 0 0 1 .384 1.279l-1.388 4.114M7 11H4v6.5A1.5 1.5 0 0 0 5.5 19v0A1.5 1.5 0 0 0 7 17.5V11Zm6.5-1h4.915c.286 0 .372.014.626.15.254.135.472.332.637.572a1.874 1.874 0 0 1 .215 1.673l-2.098 6.4C17.538 19.52 17.368 20 16.12 20c-2.303 0-4.79-.943-6.67-1.475" />
                        </svg>



                        <div>
                            <h3 class = "font-bold">4. Negotiate and Confirm</h3>
                            <p class = "text-gray-400">Communicate with farmer to negotiate prices and confirm orders. </p>
                        </div>
                    </div>
                    <div class="flex  items-center p-6 gap-4 ">
                        <svg class="w-12 h-12 bg-green-200 rounded-full p-2  text-green-600 mb-3 text-sm"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                        </svg>


                        <div>
                            <h3 class = "font-bold">5. View Matching Products</h3>
                            <p class = "text-gray-400">Monitor your order form the farm to your doorstep. </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg flex justify-center items-center h-full transition w-full p-4">
                <img src="{{ asset('images/buyer.jpg') }} " class = "h-full w-full rounded-lg" alt="Alumni Talk"
                    class="w-full h-48 object-cover">
            </div>
        </div>
    </section>

    <!-- 🧑‍🎓 Alumni Section -->
    <section id="alumni" class="h-screen flex flex-col justify-center items-center bg-white text-center px-8">
        <h2 class="text-4xl font-bold text-blue-700 mb-8">Featured Alumni</h2>
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl">
            <div class="bg-blue-50 p-6 rounded-xl shadow hover:shadow-lg transition">
                <img src="https://randomuser.me/api/portraits/men/1.jpg"
                    class="mx-auto rounded-full w-28 h-28 object-cover mb-4" alt="Alumnus">
                <h3 class="text-xl font-semibold text-blue-700">John Doe</h3>
                <p class="text-gray-600 mb-2">Software Engineer at Google</p>
                <a href="#" class="text-blue-600 hover:underline">View Profile</a>
            </div>
            <div class="bg-blue-50 p-6 rounded-xl shadow hover:shadow-lg transition">
                <img src="https://randomuser.me/api/portraits/women/2.jpg"
                    class="mx-auto rounded-full w-28 h-28 object-cover mb-4" alt="Alumnus">
                <h3 class="text-xl font-semibold text-blue-700">Jane Smith</h3>
                <p class="text-gray-600 mb-2">Marketing Director at Unilever</p>
                <a href="#" class="text-blue-600 hover:underline">View Profile</a>
            </div>
            <div class="bg-blue-50 p-6 rounded-xl shadow hover:shadow-lg transition">
                <img src="https://randomuser.me/api/portraits/men/3.jpg"
                    class="mx-auto rounded-full w-28 h-28 object-cover mb-4" alt="Alumnus">
                <h3 class="text-xl font-semibold text-blue-700">Carlos Reyes</h3>
                <p class="text-gray-600 mb-2">Founder, TechStart PH</p>
                <a href="#" class="text-blue-600 hover:underline">View Profile</a>
            </div>
        </div>
    </section>

    <!-- 📬 Contact Section -->
    <section id="contact"
        class="h-screen flex flex-col justify-center items-center    from-indigo-700 to-blue-600 text-white text-center px-8">
        <h2 class="text-4xl font-bold mb-4">Contact Us</h2>
        <p class="max-w-xl mb-6">
            Have questions or want to get involved? Reach out to our alumni relations team.
        </p>
        <form class="max-w-md w-full bg-white rounded-xl p-6 shadow-md text-gray-800">
            <input type="text" placeholder="Your Name" class="w-full border p-3 rounded mb-3 focus:outline-blue-600">
            <input type="email" placeholder="Your Email" class="w-full border p-3 rounded mb-3 focus:outline-blue-600">
            <textarea placeholder="Your Message" class="w-full border p-3 rounded mb-3 h-24 focus:outline-blue-600"></textarea>
            <button class="bg-blue-700 text-white px-6 py-2 rounded-full w-full hover:bg-blue-800">Send
                Message</button>
        </form>
    </section>
@endsection
