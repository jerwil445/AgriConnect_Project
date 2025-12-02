<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'AgriMarket - Home')

@section('content')
    <!-- Hero Section -->
    <div class="bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Welcome to AgriMarket</h1>
                <p class="text-xl md:text-2xl mb-8 text-green-100">
                    Connecting Farmers Directly with Buyers
                </p>
                <p class="text-lg mb-8 max-w-2xl mx-auto">
                    Eliminate middlemen, get fair prices, and build sustainable agricultural trade networks
                    with our transparent marketplace platform.
                </p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}" class="btn-primary bg-white text-green-600 hover:bg-green-100">
                        Get Started
                    </a>
                    <a href="#features"
                        class="btn-secondary border-2 border-white bg-transparent hover:bg-white hover:text-green-600">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Platform Features</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="card text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">👨‍🌾</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">For Farmers</h3>
                <p class="text-gray-600">
                    List your products, get fair prices, connect directly with verified buyers,
                    and receive payments securely.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="card text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🏪</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">For Buyers</h3>
                <p class="text-gray-600">
                    Source quality agricultural products directly from farmers,
                    negotiate prices, and ensure supply chain transparency.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="card text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">📊</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Smart Matching</h3>
                <p class="text-gray-600">
                    Our intelligent matching engine connects farmers and buyers based on
                    product type, quantity, location, and preferences.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-green-600">500+</div>
                    <div class="text-gray-600">Farmers</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600">200+</div>
                    <div class="text-gray-600">Buyers</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600">1,200+</div>
                    <div class="text-gray-600">Transactions</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600">$2M+</div>
                    <div class="text-gray-600">Trade Volume</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-green-700 text-white py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-4">Ready to Join Our Agricultural Network?</h2>
            <p class="text-xl text-green-100 mb-8">
                Start your journey towards fair and transparent agricultural trading today.
            </p>
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="btn-primary bg-white text-green-600 hover:bg-green-100">
                    Register as Farmer
                </a>
                <a href="{{ route('register') }}"
                    class="btn-secondary border-2 border-white bg-transparent hover:bg-white hover:text-green-600">
                    Register as Buyer
                </a>
            </div>
        </div>
    </div>
@endsection
