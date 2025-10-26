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
    <section id="about" class="h-screen flex flex-col justify-center items-center bg-white text-center px-8">
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
    <section id="how-it-work"
        class="h-screen w-full flex flex-col justify-center items-center bg-gray-100 text-center px-8">
        <h2 class="text-4xl font-bold text-green-700 mt-14 mb-6">How it works </h2>
        <div class="flex border-b-2 pb-2 gap-5 border-gray-300 w-full justify-center items-center mb-4">
            <button class = "font-bold">
                For Farmers
            </button>
            <button class = "font-bold">
                For Buyers
            </button>
        </div>
        <div class="grid md:grid-cols-2 gap-8  bg-gray-700 h-2/3 w-full">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:scale-105 transition w-full">
                <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600" alt="Homecoming"
                    class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-blue-700">Homecoming 2025</h3>
                    <p class="text-gray-600 mb-4">Reconnect with your batch 9-6s9mates and celebrate the university’s
                        legacy.
                    </p>
                    <button class="bg-blue-700 text-white px-5 py-2 rounded-full hover:bg-blue-800">View
                        Details</button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:scale-105 transition w-full">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600" alt="Alumni Talk"
                    class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-blue-700">Alumni Talk Series</h3>
                    <p class="text-gray-600 mb-4">Be inspired by successful alumni sharing their professional journeys.
                    </p>
                    <button class="bg-blue-700 text-white px-5 py-2 rounded-full hover:bg-blue-800">Join Event</button>
                </div>
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
