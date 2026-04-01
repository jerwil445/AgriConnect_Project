@extends('layouts.app')

@section('title', 'Home - Agribusiness Platform')

@section('content')

    <script>
        // Smooth scroll and tab functionality
        document.addEventListener("DOMContentLoaded", () => {
            // Smooth scrolling for anchor links
            document.querySelectorAll("a[href^='#']").forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute("href")).scrollIntoView({
                        behavior: "smooth"
                    });
                });

                // Close mobile menu when clicking nav link
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });

            // Tab switching functionality
            const farmerBtn = document.getElementById('for-farmer-btn');
            const buyerBtn = document.getElementById('for-buyers-btn');
            const farmersSection = document.getElementById('for-farmers');
            const buyersSection = document.getElementById('for-buyers');

            if (farmerBtn && buyerBtn && farmersSection && buyersSection) {
                buyerBtn.addEventListener('click', () => {
                    farmersSection.classList.add('hidden');
                    buyersSection.classList.remove('hidden');
                    farmerBtn.classList.remove('font-bold', 'text-green-700', 'border-b-2', 'border-green-700');
                    buyerBtn.classList.add('font-bold', 'text-green-700', 'border-b-2', 'border-green-700');
                });

                farmerBtn.addEventListener('click', () => {
                    buyersSection.classList.add('hidden');
                    farmersSection.classList.remove('hidden');
                    buyerBtn.classList.remove('font-bold', 'text-green-700', 'border-b-2', 'border-green-700');
                    farmerBtn.classList.add('font-bold', 'text-green-700', 'border-b-2', 'border-green-700');
                });
            }
        });
    </script>


    <!-- 🏠 Home Section -->
    <section id="home"
        class="relative min-h-screen flex flex-col justify-center items-center bg-cover bg-center text-white text-center px-4 md:px-6 -mt-16 pt-16"
        style="background-image: url('{{ asset('images/background_image.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <div class="relative z-10 max-w-6xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-4 md:mb-6">Welcome to <span class="text-green-500">ArgiConnect</span></h1>
            <p class="text-base md:text-lg lg:text-xl mb-6 md:mb-8 leading-relaxed max-w-2xl mx-auto">
                A modern web-based platform connecting farmers directly with buyers, eliminating middlemen and ensuring fair trade.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
                <a href="#about"
                    class="bg-white text-green-700 font-semibold px-6 md:px-8 py-3 md:py-3 rounded-md shadow-lg hover:bg-gray-100 transition duration-300 transform hover:-translate-y-1">
                    Learn More
                </a>
                <a href="{{ route('register') }}"
                    class="bg-transparent border-2 border-white text-white font-semibold px-6 md:px-8 py-3 md:py-3 rounded-md hover:bg-white hover:text-green-700 transition duration-300 transform hover:-translate-y-1">
                    Join Now
                </a>
            </div>
        </div>
    </section>


    <!-- 📖 About Section -->
    <section id="about" class="py-12 md:py-20 bg-white text-center px-4 md:px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-green-700 mb-4 md:mb-6">About Us</h2>
            <div class="max-w-3xl mx-auto text-gray-600 text-base md:text-lg mb-8 md:mb-12 leading-relaxed">
                <p>
                    We are committed to revolutionizing the agricultural supply chain by empowering farmers and providing buyers
                    with direct access to the freshest produce. Our platform is built on the principles of transparency, fairness,
                    and sustainability.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div
                    class="bg-green-50 p-6 md:p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex flex-col justify-center items-center text-center transform hover:-translate-y-2">
                    <svg class="w-12 h-12 md:w-16 md:h-16 bg-green-200 rounded-full p-3 md:p-4 text-green-600 mb-4 md:mb-5" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                    <h3 class="text-lg md:text-xl font-semibold text-green-600 mb-2 md:mb-3">Farmer Empowerment</h3>
                    <p class="text-sm md:text-base">Direct access to markets without intermediaries, ensuring fair prices for your produce.</p>
                </div>

                <div
                    class="bg-green-50 p-6 md:p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center text-center transform hover:-translate-y-2">
                    <div class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-green-200 rounded-full mb-4 md:mb-5">
                        <i class="fa-regular fa-handshake text-green-600 text-xl md:text-2xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-green-600 mb-2 md:mb-3">Transparent Trade</h3>
                    <p class="text-sm md:text-base">Real-time price discovery and direct communication between farmers and buyers.</p>
                </div>

                <div
                    class="bg-green-50 p-6 md:p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center text-center transform hover:-translate-y-2">
                    <div class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-green-200 rounded-full mb-4 md:mb-5">
                        <i class="fas fa-leaf text-green-600 text-xl md:text-2xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-green-600 mb-2 md:mb-3">Quality Assurance</h3>
                    <p class="text-sm md:text-base">Verified farmer profiles and product quality certifications for trust and reliability.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🎉 How it work Section -->
    <section id="how-it-work" class="py-12 md:py-16 bg-gradient-to-br from-green-50 to-emerald-50 px-4 md:px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8 md:mb-10">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-green-800 mb-2 md:mb-3">How It Works</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-base md:text-lg">Our platform connects farmers and buyers directly, eliminating middlemen and ensuring fair trade for all parties involved.</p>
            </div>

            <div class="flex border-b-2 border-gray-200 mb-6 md:mb-8 max-w-md mx-auto justify-center">
                <button class="font-bold text-green-700 border-b-2 border-green-700 pb-2 md:pb-3 px-4 md:px-8 transition-all duration-300 text-sm md:text-base" id="for-farmer-btn">
                    For Farmers
                </button>
                <button class="font-medium text-gray-500 pb-2 md:pb-3 px-4 md:px-8 hover:text-green-600 transition-all duration-300 text-sm md:text-base" id="for-buyers-btn">
                    For Buyers
                </button>
            </div>

            <div class="grid lg:grid-cols-2 gap-6 md:gap-8 items-center" id="for-farmers">
                <div class="order-2 lg:order-1">
                    <div class="space-y-4 md:space-y-6">
                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">1</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">Register Your Profile</h3>
                                <p class="text-gray-600 text-sm">Create a comprehensive profile to showcase your farm, your story, and commitment to quality produce.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">2</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">List Your Products</h3>
                                <p class="text-gray-600 text-sm">Easily upload your available products with detailed information including quantity, pricing, and harvest dates.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">3</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">Connect with Buyers</h3>
                                <p class="text-gray-600 text-sm">Receive inquiries directly from restaurants, retailers, and consumers looking for fresh, local produce.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 rounded-xl transition-all duration-300 transform hover:scale-[1.02] flex justify-center">
                    <img src="{{ asset('images/farmer.jpg') }}" class="w-full max-w-sm lg:w-80 object-cover rounded-xl shadow-lg" alt="Farmer working on farm">
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6 md:gap-8 items-center hidden" id="for-buyers">
                <div class="order-2 lg:order-1">
                    <div class="space-y-4 md:space-y-6">
                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">1</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">Register and Create Profile</h3>
                                <p class="text-gray-600 text-sm">Sign up and build your buyer profile with your specific needs and business details.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">2</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">Post Product Demands</h3>
                                <p class="text-gray-600 text-sm">Easily list the products you need, specifying quantity, quality requirements, and budget.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">3</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">View Matching Products</h3>
                                <p class="text-gray-600 text-sm">Our platform matches your demands with available farm products from verified sellers.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">4</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">Negotiate and Confirm</h3>
                                <p class="text-gray-600 text-sm">Communicate with farmers to negotiate prices and terms directly.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 md:gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-base md:text-lg border-2 border-green-200 transition-all duration-300 group-hover:border-green-300 group-hover:scale-110">5</div>
                            <div>
                                <h3 class="font-bold text-base md:text-lg mb-1 md:mb-2 text-gray-800">Track Your Order</h3>
                                <p class="text-gray-600 text-sm">Monitor your order from harvest to delivery with real-time updates.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 rounded-xl transition-all duration-300 transform hover:scale-[1.02] flex justify-center">
                    <img src="{{ asset('images/buyer2.jpg') }}" class="w-full max-w-sm lg:w-80 object-cover shadow-lg rounded-xl" alt="Buyer examining produce">
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section id="alumni" class="py-12 md:py-20 bg-white text-center px-4 md:px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-green-700 mb-3 md:mb-4">Success Stories</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mb-8 md:mb-12 text-base md:text-lg">Hear from farmers and buyers who have transformed their business with ArgiConnect</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div class="bg-green-50 p-6 md:p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg"
                        class="mx-auto rounded-full w-20 h-20 md:w-24 md:h-24 object-cover mb-4 md:mb-6 border-4 border-white shadow" alt="Farmer">
                    <h3 class="text-lg md:text-xl font-semibold text-green-700 mb-1 md:mb-2">Rajesh Kumar</h3>
                    <p class="text-gray-600 mb-3 md:mb-4 text-sm md:text-base">Organic Vegetable Farmer</p>
                    <p class="text-gray-700 italic mb-3 md:mb-4 text-sm md:text-base">"ArgiConnect helped me double my income by connecting me directly with premium hotels in the city."</p>
                    <div class="flex justify-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="bg-green-50 p-6 md:p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg"
                        class="mx-auto rounded-full w-20 h-20 md:w-24 md:h-24 object-cover mb-4 md:mb-6 border-4 border-white shadow" alt="Buyer">
                    <h3 class="text-lg md:text-xl font-semibold text-green-700 mb-1 md:mb-2">Priya Sharma</h3>
                    <p class="text-gray-600 mb-3 md:mb-4 text-sm md:text-base">Restaurant Owner</p>
                    <p class="text-gray-700 italic mb-3 md:mb-4 text-sm md:text-base">"I get the freshest produce at fair prices directly from farmers. My customers love the quality!"</p>
                    <div class="flex justify-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="bg-green-50 p-6 md:p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <img src="https://randomuser.me/api/portraits/men/67.jpg"
                        class="mx-auto rounded-full w-20 h-20 md:w-24 md:h-24 object-cover mb-4 md:mb-6 border-4 border-white shadow" alt="Cooperative">
                    <h3 class="text-lg md:text-xl font-semibold text-green-700 mb-1 md:mb-2">Green Valley Co-op</h3>
                    <p class="text-gray-600 mb-3 md:mb-4 text-sm md:text-base">Farmers Cooperative</p>
                    <p class="text-gray-700 italic mb-3 md:mb-4 text-sm md:text-base">"Our 200+ member farmers now have a unified platform to reach markets across the region."</p>
                    <div class="flex justify-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 📬 Contact Section -->
    <section id="contact" class="py-12 md:py-20 bg-gradient-to-r from-green-600 to-emerald-700 text-white text-center px-4 md:px-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-4 md:mb-6">Get In Touch</h2>
            <p class="text-base md:text-xl mb-8 md:mb-12 max-w-2xl mx-auto">
                Have questions or want to learn more about how ArgiConnect can benefit your business?
            </p>

            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div class="text-left">
                    <div class="mb-6 md:mb-8">
                        <div class="flex items-start mb-4 md:mb-6">
                            <div class="mr-3 md:mr-4 mt-1 text-lg md:text-xl">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h3 class="text-lg md:text-xl font-semibold mb-1 md:mb-2">Our Office</h3>
                                <p class="opacity-90 text-sm md:text-base">123 Agriculture Street, Farmville<br>State, Country 12345</p>
                            </div>
                        </div>

                        <div class="flex items-start mb-4 md:mb-6">
                            <div class="mr-3 md:mr-4 mt-1 text-lg md:text-xl">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h3 class="text-lg md:text-xl font-semibold mb-1 md:mb-2">Call Us</h3>
                                <p class="opacity-90 text-sm md:text-base">+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="mr-3 md:mr-4 mt-1 text-lg md:text-xl">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h3 class="text-lg md:text-xl font-semibold mb-1 md:mb-2">Email Us</h3>
                                <p class="opacity-90 text-sm md:text-base">info@argiconnect.com<br>support@argiconnect.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 md:p-8 shadow-xl text-gray-800">
                    <form>
                        <div class="mb-4 md:mb-5">
                            <input type="text" placeholder="Your Name" class="w-full border border-gray-300 p-3 md:p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm md:text-base">
                        </div>
                        <div class="mb-4 md:mb-5">
                            <input type="email" placeholder="Your Email" class="w-full border border-gray-300 p-3 md:p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm md:text-base">
                        </div>
                        <div class="mb-4 md:mb-5">
                            <textarea placeholder="Your Message" class="w-full border border-gray-300 p-3 md:p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 h-24 md:h-32 text-sm md:text-base"></textarea>
                        </div>
                        <button class="bg-green-600 text-white px-5 md:px-6 py-3 md:py-4 rounded-lg w-full hover:bg-green-700 transition duration-300 font-semibold text-base md:text-lg">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
