@extends('layouts.app')

@section('title', 'Home - Agribusiness Platform')

@section('content')

    @vite('resources/js/landing.js')


    <!-- 🏠 Home Section -->
    <section id="home"
        class="relative min-h-screen flex flex-col justify-center items-center bg-cover bg-center bg-fixed text-white text-center px-4 md:px-6 -mt-16 pt-16 overflow-hidden"
        style="background-image: url('{{ asset('images/background_image.jpg') }}');">
        
        <!-- Modern Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/90 via-green-950/80 to-emerald-900/90 backdrop-blur-[2px]"></div>
        
        <!-- Background Blobs (Pure Tailwind) -->
        <div class="absolute w-[30rem] h-[30rem] top-0 left-0 bg-gradient-to-br from-green-500 to-green-700 rounded-full blur-[80px] opacity-40 animate-pulse" style="animation-duration: 4s;"></div>
        <div class="absolute w-[25rem] h-[25rem] bottom-0 right-0 bg-gradient-to-br from-emerald-600 to-emerald-900 rounded-full blur-[80px] opacity-40 animate-pulse" style="animation-duration: 5s;"></div>

        <div class="relative z-10 max-w-6xl mx-auto flex flex-col items-center mt-10">
            
            <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-100 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-8 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </span>
                <span class="text-sm md:text-base font-medium text-green-50 tracking-wide">Empowering 10,000+ Local Farmers</span>
            </div>

            <h1 class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200 text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-black mb-6 tracking-tight drop-shadow-xl">
                Welcome to <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-emerald-300">AgriConnect</span>
            </h1>
            
            <p class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-300 text-base md:text-xl lg:text-2xl mb-12 leading-relaxed max-w-3xl mx-auto font-light text-gray-200 drop-shadow-md">
                A modern ecosystem connecting farmers directly with buyers, eliminating middlemen and ensuring premium fair trade.
            </p>
            
            <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-500 flex flex-col sm:flex-row gap-4 md:gap-6 justify-center w-full sm:w-auto">
                <a href="#about"
                    class="group relative overflow-hidden bg-white text-green-800 font-bold px-8 py-4 rounded-xl shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_30px_rgba(255,255,255,0.4)] transition-all duration-300 transform hover:-translate-y-1 block w-full sm:w-auto text-center">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Learn More <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </span>
                    <div class="absolute inset-0 h-full w-full scale-0 rounded-xl transition-all duration-300 group-hover:scale-100 group-hover:bg-gray-50/50"></div>
                </a>
                <a href="{{ route('register') }}"
                    class="group relative overflow-hidden bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold px-8 py-4 rounded-xl hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-1 block w-full sm:w-auto text-center shadow-lg">
                    Join Network
                </a>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <a href="#about" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce text-white/50 hover:text-white z-20 transition-colors">
            <i class="fas fa-chevron-down text-2xl drop-shadow-md"></i>
        </a>
    </section>


    <!-- 📖 About Section -->
    <section id="about" class="py-20 md:py-32 bg-gray-50 text-center px-4 md:px-6 relative overflow-hidden">
        <!-- Decorative subtle background -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-green-100 rounded-full blur-[80px] opacity-60"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-emerald-100 rounded-full blur-[80px] opacity-60"></div>

        <div class="relative z-10 max-w-6xl mx-auto">
            <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out text-center mb-16">
                <span class="text-green-600 font-bold text-sm tracking-wider uppercase mb-2 block">Our Mission</span>
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight">Revolutionizing <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-700 to-emerald-500">Agriculture</span></h2>
                <p class="max-w-3xl mx-auto text-gray-600 text-lg md:text-xl leading-relaxed">
                    We are committed to revolutionizing the agricultural supply chain by empowering farmers and providing buyers
                    with direct access to the freshest produce. Our platform is built on the principles of transparency, fairness,
                    and sustainability.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
                <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-100 bg-white/80 backdrop-blur-md border border-green-50 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] p-8 md:p-10 rounded-3xl group hover:-translate-y-3">
                    <div class="relative w-20 h-20 mx-auto mb-6">
                        <div class="absolute inset-0 bg-green-200 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-300"></div>
                        <div class="absolute inset-0 bg-white rounded-2xl shadow-sm flex items-center justify-center -rotate-3 group-hover:rotate-0 transition-transform duration-300 border border-green-50">
                            <svg class="w-10 h-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3">Farmer Empowerment</h3>
                    <p class="text-gray-600 leading-relaxed">Direct access to markets without intermediaries, ensuring fair prices and better margins for your harvest.</p>
                </div>

                <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200 bg-white/80 backdrop-blur-md border border-green-50 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] p-8 md:p-10 rounded-3xl group hover:-translate-y-3">
                    <div class="relative w-20 h-20 mx-auto mb-6">
                        <div class="absolute inset-0 bg-emerald-200 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-300"></div>
                        <div class="absolute inset-0 bg-white rounded-2xl shadow-sm flex items-center justify-center -rotate-3 group-hover:rotate-0 transition-transform duration-300 border border-emerald-50">
                            <i class="fa-regular fa-handshake text-emerald-600 text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3">Transparent Trade</h3>
                    <p class="text-gray-600 leading-relaxed">Real-time price discovery and direct communication between farmers and buyers builds lasting trust.</p>
                </div>

                <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-300 bg-white/80 backdrop-blur-md border border-green-50 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] p-8 md:p-10 rounded-3xl group hover:-translate-y-3">
                    <div class="relative w-20 h-20 mx-auto mb-6">
                        <div class="absolute inset-0 bg-teal-200 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-300"></div>
                        <div class="absolute inset-0 bg-white rounded-2xl shadow-sm flex items-center justify-center -rotate-3 group-hover:rotate-0 transition-transform duration-300 border border-teal-50">
                            <i class="fas fa-leaf text-teal-600 text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3">Quality Assurance</h3>
                    <p class="text-gray-600 leading-relaxed">Verified farmer profiles and product quality certifications guarantee reliability for every order.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🎉 How it Works Section -->
    <section id="how-it-work" class="py-20 md:py-32 bg-white px-4 md:px-6">
        <div class="max-w-6xl mx-auto">
            <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out text-center mb-12 md:mb-16">
                <span class="text-green-600 font-bold text-sm tracking-wider uppercase mb-2 block">Process</span>
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-4 tracking-tight">How It Works</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Our streamlined digital platform makes agricultural commerce seamless and fair for all parties involved.</p>
            </div>

            <!-- Modern Pill Toggle -->
            <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out flex justify-center mb-16">
                <div class="relative bg-gray-100 p-1.5 rounded-full inline-flex shadow-inner">
                    <!-- The sliding background indicator -->
                    <div id="pill-indicator" class="absolute top-1.5 left-1.5 bottom-1.5 w-[calc(50%-6px)] bg-white rounded-full shadow-sm transition-transform duration-300 ease-in-out"></div>
                    
                    <button id="for-farmer-btn" class="relative z-10 w-40 md:w-48 py-3 rounded-full text-base font-bold transition-colors duration-300 text-green-900">
                        For Farmers
                    </button>
                    <button id="for-buyers-btn" class="relative z-10 w-40 md:w-48 py-3 rounded-full text-base font-medium transition-colors duration-300 text-gray-500 hover:text-gray-700">
                        For Buyers
                    </button>
                </div>
            </div>

            <!-- Farmers Content -->
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center transition-opacity duration-500" id="for-farmers">
                <div class="order-2 lg:order-1 reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out">
                    <div class="space-y-8 relative">
                        
                        <div class="flex items-start gap-5 group relative">
                            <!-- Tailwind Step Line -->
                            <div class="absolute top-[48px] bottom-[-32px] left-[23px] w-[2px] bg-gradient-to-b from-green-200 to-green-100 z-0 hidden sm:block"></div>
                            
                            <div class="relative z-10 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-green-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">1</div>
                            <div class="pt-1">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-green-700 transition-colors">Register Your Profile</h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">Create a comprehensive profile to showcase your farm, your story, and commitment to quality produce.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5 group relative">
                            <!-- Tailwind Step Line -->
                            <div class="absolute top-[48px] bottom-[-32px] left-[23px] w-[2px] bg-gradient-to-b from-green-100 to-green-50 z-0 hidden sm:block"></div>
                            
                            <div class="relative z-10 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-green-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">2</div>
                            <div class="pt-1">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-green-700 transition-colors">List Your Products</h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">Easily upload your available products with detailed information including quantity, pricing, and harvest dates.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5 group relative">
                            <div class="relative z-10 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-green-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">3</div>
                            <div class="pt-1">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-green-700 transition-colors">Connect with Buyers</h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">Receive inquiries directly from restaurants, retailers, and consumers looking for fresh, local produce.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out flex justify-center relative">
                    <div class="absolute inset-0 bg-green-200 rounded-3xl blur-2xl opacity-50 transform rotate-3"></div>
                    <img src="{{ asset('images/farmer.jpg') }}" class="relative w-full max-w-md object-cover rounded-3xl shadow-2xl transform hover:rotate-1 hover:scale-[1.02] transition-all duration-500 border-4 border-white" alt="Farmer working on farm">
                    
                    <!-- Floating stat card -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Average Increase</p>
                            <p class="text-xl font-bold text-gray-900">+40% Revenue</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyers Content -->
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center hidden transition-opacity duration-500 opacity-0" id="for-buyers">
                <div class="order-2 lg:order-1">
                    <div class="space-y-8 relative">
                        
                        <div class="flex items-start gap-5 group relative">
                            <!-- Tailwind Step Line -->
                            <div class="absolute top-[48px] bottom-[-32px] left-[23px] w-[2px] bg-gradient-to-b from-teal-200 to-teal-100 z-0 hidden sm:block"></div>
                            
                            <div class="relative z-10 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-teal-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">1</div>
                            <div class="pt-1">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-teal-700 transition-colors">Create Profile</h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">Sign up and build your buyer profile with your specific needs and business details.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5 group relative">
                            <!-- Tailwind Step Line -->
                            <div class="absolute top-[48px] bottom-[-32px] left-[23px] w-[2px] bg-gradient-to-b from-teal-100 to-teal-50 z-0 hidden sm:block"></div>
                            
                            <div class="relative z-10 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-teal-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">2</div>
                            <div class="pt-1">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-teal-700 transition-colors">Post Demands</h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">Easily list the products you need, specifying quantity, quality requirements, and budget.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5 group relative">
                            <!-- Tailwind Step Line -->
                            <div class="absolute top-[48px] bottom-[-32px] left-[23px] w-[2px] bg-gradient-to-b from-teal-50 to-transparent z-0 hidden sm:block"></div>
                            
                            <div class="relative z-10 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-teal-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">3</div>
                            <div class="pt-1">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-teal-700 transition-colors">Match & Negotiate</h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">Platform matches your demands. Communicate directly with verified farmers to finalize terms.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5 group">
                            <div class="relative z-10 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-teal-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">4</div>
                            <div class="pt-1">
                                <h3 class="font-bold text-xl mb-2 text-gray-900 group-hover:text-teal-700 transition-colors">Track Delivery</h3>
                                <p class="text-gray-600 leading-relaxed text-base md:text-lg">Monitor your order from harvest to delivery with real-time transparency and updates.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 flex justify-center relative">
                    <div class="absolute inset-0 bg-teal-200 rounded-3xl blur-2xl opacity-50 transform -rotate-3"></div>
                    <img src="{{ asset('images/buyer2.jpg') }}" class="relative w-full max-w-md object-cover rounded-3xl shadow-2xl transform hover:-rotate-1 hover:scale-[1.02] transition-all duration-500 border-4 border-white" alt="Buyer examining produce">
                    
                    <!-- Floating stat card -->
                    <div class="absolute top-6 -right-6 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 animate-bounce" style="animation-duration: 3.5s;">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center text-teal-600 text-xl"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Quality Rate</p>
                            <p class="text-xl font-bold text-gray-900">100% Verified</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section id="alumni" class="py-20 md:py-32 bg-gray-50 text-center px-4 md:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out mb-16">
                <span class="text-green-600 font-bold text-sm tracking-wider uppercase mb-2 block">Testimonials</span>
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-4 tracking-tight">Success <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-700 to-emerald-500">Stories</span></h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Hear from the community members who have transformed their businesses with AgriConnect.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
                <!-- Testimonial 1 -->
                <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-100 bg-white p-8 md:p-10 rounded-[2rem] shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] hover:shadow-[0_10px_40px_-5px_rgba(34,197,94,0.15)] transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 relative mt-12">
                    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg"
                            class="rounded-full w-24 h-24 object-cover border-4 border-white shadow-xl" alt="Farmer">
                    </div>
                    <i class="fas fa-quote-left text-green-100 text-5xl absolute top-6 left-6"></i>
                    
                    <div class="pt-10">
                        <div class="flex justify-center text-yellow-400 mb-4 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 italic mb-6 leading-relaxed relative z-10 text-lg">"AgriConnect helped me double my income by connecting me directly with premium hotels in the city. The transparency is unmatched."</p>
                        <hr class="w-12 border-green-200 mx-auto mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Rajesh Kumar</h3>
                        <p class="text-green-600 font-medium text-sm">Organic Vegetable Farmer</p>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200 bg-white p-8 md:p-10 rounded-[2rem] shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] hover:shadow-[0_10px_40px_-5px_rgba(34,197,94,0.15)] transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 relative mt-12 tracking-tight">
                    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg"
                            class="rounded-full w-24 h-24 object-cover border-4 border-white shadow-xl" alt="Buyer">
                    </div>
                    <i class="fas fa-quote-left text-green-100 text-5xl absolute top-6 left-6"></i>
                    
                    <div class="pt-10">
                        <div class="flex justify-center text-yellow-400 mb-4 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="text-gray-700 italic mb-6 leading-relaxed relative z-10 text-lg">"I get the freshest produce at fair prices directly from farmers. My customers love the quality, and managing orders is effortless."</p>
                        <hr class="w-12 border-green-200 mx-auto mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Priya Sharma</h3>
                        <p class="text-green-600 font-medium text-sm">Restaurant Owner</p>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-300 bg-white p-8 md:p-10 rounded-[2rem] shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] hover:shadow-[0_10px_40px_-5px_rgba(34,197,94,0.15)] transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 relative mt-12">
                    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                        <img src="https://randomuser.me/api/portraits/men/67.jpg"
                            class="rounded-full w-24 h-24 object-cover border-4 border-white shadow-xl" alt="Cooperative">
                    </div>
                    <i class="fas fa-quote-left text-green-100 text-5xl absolute top-6 left-6"></i>
                    
                    <div class="pt-10">
                        <div class="flex justify-center text-yellow-400 mb-4 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 italic mb-6 leading-relaxed relative z-10 text-lg">"Our 200+ member farmers now have a unified platform to reach markets across the region. It's transformed our rural economy."</p>
                        <hr class="w-12 border-green-200 mx-auto mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Green Valley Co-op</h3>
                        <p class="text-green-600 font-medium text-sm">Farmers Cooperative</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 📬 Contact Section -->
    <section id="contact" class="py-24 relative overflow-hidden bg-gradient-to-br from-green-900 via-green-800 to-emerald-950 text-white text-center px-4 md:px-6">
        <!-- Background graphics -->
        <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-emerald-500 rounded-full blur-[120px] opacity-20 -mr-40 -mt-40 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-green-400 rounded-full blur-[100px] opacity-10 -mb-20 -ml-20 pointer-events-none"></div>
        
        <div class="relative z-10 max-w-5xl mx-auto">
            <div class="reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out mb-12">
                <h2 class="text-3xl md:text-5xl font-black mb-4 tracking-tight drop-shadow-lg">Get In Touch</h2>
                <p class="text-lg md:text-xl text-green-100 max-w-2xl mx-auto font-light">
                    Have questions or want to learn more about how AgriConnect can elevate your business?
                </p>
            </div>

            <div class="grid md:grid-cols-5 gap-10 lg:gap-16 items-center bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 md:p-12 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] reveal-group opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200">
                
                <div class="md:col-span-2 text-left space-y-8">
                    <div class="flex items-start group">
                        <div class="mr-5 mt-1 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-green-300 text-xl group-hover:bg-green-500 group-hover:text-white transition-colors duration-300 shadow-inner">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1 text-white">Our Office</h3>
                            <p class="text-green-100/70 font-light leading-relaxed">123 Agriculture Street, Farmville<br>State, Country 12345</p>
                        </div>
                    </div>

                    <div class="flex items-start group">
                        <div class="mr-5 mt-1 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-green-300 text-xl group-hover:bg-green-500 group-hover:text-white transition-colors duration-300 shadow-inner">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1 text-white">Call Us</h3>
                            <p class="text-green-100/70 font-light leading-relaxed">+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                        </div>
                    </div>

                    <div class="flex items-start group">
                        <div class="mr-5 mt-1 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-green-300 text-xl group-hover:bg-green-500 group-hover:text-white transition-colors duration-300 shadow-inner">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1 text-white">Email Us</h3>
                            <p class="text-green-100/70 font-light leading-relaxed">info@agriconnect.com<br>support@agriconnect.com</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <form class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <input type="text" placeholder="Your Name" class="w-full bg-white/10 border border-white/20 p-4 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white/20 transition-all shadow-inner">
                            <input type="email" placeholder="Your Email" class="w-full bg-white/10 border border-white/20 p-4 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white/20 transition-all shadow-inner">
                        </div>
                        <input type="text" placeholder="Subject" class="w-full bg-white/10 border border-white/20 p-4 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white/20 transition-all shadow-inner">
                        <textarea placeholder="Your Message" class="w-full bg-white/10 border border-white/20 p-4 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white/20 transition-all h-32 md:h-40 resize-none shadow-inner"></textarea>
                        
                        <button type="button" class="w-full bg-gradient-to-r from-green-400 to-emerald-500 text-green-950 font-black px-6 py-4 rounded-xl hover:from-green-300 hover:to-emerald-400 transition-all duration-300 transform hover:-translate-y-1 shadow-[0_10px_20px_rgba(16,185,129,0.3)] text-lg flex justify-center items-center gap-2">
                            Send Message <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
