@extends('layouts.farmers_page')

@section('content')
<div class="ml-64">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('farmer.matches') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    @php
                        $eggTypes = [
                            'chicken' => '🐔 Chicken Eggs',
                            'duck' => '🦆 Duck Eggs',
                            'quail' => '🐦 Quail Eggs',
                            'native_chicken' => '🐓 Native Chicken Eggs',
                            'brown' => '🥚 Brown Eggs',
                            'white' => '🥚 White Eggs'
                        ];
                    @endphp
                    <h2 class="text-xl font-bold text-gray-900">Matches for {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}</h2>
                    <p class="text-sm text-gray-500">Review and respond to buyer demands</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('products.edit', $product) }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Edit Product
                </a>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @if(auth()->user()->unreadNotifications->count() > 0)
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-amber-800">
                        You have <strong>{{ auth()->user()->unreadNotifications->count() }}</strong> new notification(s)
                    </p>
                    <a href="{{ route('farmer.notifications') }}" class="text-xs text-amber-700 hover:text-amber-900 underline">View all notifications</a>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Info Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-5 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Product Details
                    </h3>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900">
                                                        @php
                                                            $eggTypes = [
                                                                'chicken' => 'Chicken',
                                                                'duck' => 'Duck',
                                                                'quail' => 'Quail',
                                                                'native_chicken' => 'Native Chicken',
                                                                'brown' => 'Brown Egg',
                                                                'white' => 'White Egg'
                                                            ];
                                                        @endphp
                                                        {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}
                                                    </h2>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium 
                                @if($product->status == 'Available') bg-green-100 text-green-800
                                @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                            </span>
                        </div>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-blue-600 font-medium">Available Quantity</p>
                                <p class="text-lg font-bold text-blue-900">{{ $product->quantity }} {{ $product->unit }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-green-600 font-medium">Total Price</p>
                                <p class="text-lg font-bold text-green-900">₱{{ number_format($product->price, 2) }}</p>
                            </div>
                        </div>
                        
                        <!-- Egg Sizes -->
                        @if($product->sizes && $product->sizes->count() > 0)
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Available Sizes</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->sizes as $size)
                                    @php
                                        $sizeLabels = [
                                            'small' => 'Small',
                                            'medium' => 'Medium',
                                            'large' => 'Large',
                                            'extra_large' => 'Extra Large',
                                            'jumbo' => 'Jumbo'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        {{ $sizeLabels[$size->size_name] ?? ucfirst(str_replace('_', ' ', $size->size_name)) }}: {{ $size->tray_count }} trays
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <div class="border-t border-gray-100 pt-4 space-y-2">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Harvest: <span class="font-medium text-gray-900">{{ $product->harvest_date->format('M d, Y') }}</span></span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Posted: <span class="font-medium text-gray-900">{{ $product->created_at->format('M d, Y') }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Match Summary Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                <div class="px-5 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Match Summary
                    </h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Total Matches</span>
                            <span class="text-2xl font-bold text-indigo-600">{{ $product->matches->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">New</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $product->matches->where('status', 'New')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Accepted</span>
                            <span class="text-2xl font-bold text-green-600">{{ $product->matches->where('status', 'Matched')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Active Chats</span>
                            <span class="text-2xl font-bold text-purple-600">{{ $product->matches->where('status', 'Transaction Started')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buyer Demands List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $product->matches->count() }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Buyer Demands</h3>
                                <p class="text-xs text-gray-500">{{ $product->matches->where('status', 'New')->count() }} new matches requiring action</p>
                            </div>
                        </div>
                        @if($product->matches->where('status', 'New')->count() > 0)
                            <span class="flex items-center gap-2 text-xs font-medium text-amber-700 bg-amber-100 px-3 py-1.5 rounded-full">
                                <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                                Action Required
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6">

                    @if($product->matches->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Matches Yet</h3>
                            <p class="text-gray-500 text-sm">The system will automatically match your product when buyers post demands.</p>
                            <p class="text-gray-400 text-xs mt-2">Make sure your product is marked as "Available" to receive matches.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                        @foreach($product->matches as $match)
                            @if($match->demand && $match->demand->buyer)
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 hover:border-green-300 hover:shadow-md transition-all duration-300 relative">
                                <!-- Delete Match Icon -->
                                <form action="{{ route('matches.destroy', $match) }}" method="POST" class="absolute top-3 right-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this match? This action cannot be undone.')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                                
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1 pr-8">
                                        <h3 class="font-bold text-lg text-gray-900">
                                                                @php
                                                                    $eggTypes = [
                                                                        'chicken' => 'Chicken',
                                                                        'duck' => 'Duck',
                                                                        'quail' => 'Quail',
                                                                        'native_chicken' => 'Native Chicken',
                                                                        'brown' => 'Brown Egg',
                                                                        'white' => 'White Egg'
                                                                    ];
                                                                    $buyerUser = $match->demand->buyer; // This is the User model
                                                                    $buyerProfile = $buyerUser->buyer ?? null; // This is the Buyer profile
                                                                    $buyerName = $buyerUser->first_name ?? 'Unknown';
                                                                    $buyerLastName = $buyerUser->last_name ?? 'Buyer';
                                                                @endphp
                                                                    {{ $eggTypes[$match->demand->egg_type] ?? ucfirst(str_replace('_', ' ', $match->demand->egg_type)) }}
                                                                </h3>
                                        <p class="text-gray-600 text-sm flex items-center gap-1 mt-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $buyerName }} {{ $buyerLastName }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                                        @if($match->status == 'New') bg-blue-100 text-blue-800
                                        @elseif($match->status == 'Matched') bg-green-100 text-green-800
                                        @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                        @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                        @elseif($match->status == 'Rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $match->status }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
                                    <div class="flex items-start gap-2 p-3 bg-white rounded-lg">
                                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Required Quantity</p>
                                            <p class="font-semibold text-gray-900">{{ $match->demand->quantity }} {{ $match->demand->unit ?? 'trays' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2 p-3 bg-white rounded-lg">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Delivery Date</p>
                                            <p class="font-semibold text-gray-900">{{ $match->demand->delivery_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2 p-3 bg-white rounded-lg">
                                        <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Location</p>
                                            <p class="font-semibold text-gray-900">{{ Str::limit($match->demand->location, 20) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 mb-4">
                                    @php
                                        $buyerUser = $match->demand->buyer; // User model
                                        $buyerProfile = $buyerUser->buyer ?? null; // Buyer profile
                                    @endphp
                                    <button type="button" 
                                            class="view-buyer-btn flex-1 text-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition"
                                            data-buyer-id="{{ $buyerUser->id ?? 0 }}"
                                            data-buyer-firstname="{{ $buyerUser->first_name ?? 'N/A' }}"
                                            data-buyer-lastname="{{ $buyerUser->last_name ?? 'N/A' }}"
                                            data-buyer-email="{{ $buyerUser->email ?? 'N/A' }}"
                                            data-buyer-phone="{{ $buyerProfile->phone_number ?? 'N/A' }}"
                                            data-buyer-company="{{ $buyerProfile->company_name ?? 'N/A' }}"
                                            data-buyer-businesstype="{{ $buyerProfile->business_type ?? 'N/A' }}"
                                            data-buyer-address="{{ $buyerProfile->address ?? 'N/A' }}">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        View Buyer Profile
                                    </button>
                                </div>
                            
                            @if($product->status == 'Sold Out')
                                <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    Product Sold Out
                                </button>
                            @elseif($match->status == 'New')
                                <div class="flex gap-2">
                                    <form action="{{ route('matches.accept', $match) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('matches.reject', $match) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2"
                                                onclick="return confirm('Are you sure you want to decline this match?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            @elseif($match->status == 'Matched' || $match->status == 'Transaction Started')
                                <form action="{{ route('matches.startTransaction', $match) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Message Buyer
                                    </button>
                                </form>
                            @elseif($match->status == 'Rejected')
                                <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    Match Declined
                                </button>
                            @else
                                <form action="{{ route('matches.startTransaction', $match) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Message
                                    </button>
                                </form>
                            @endif
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buyer Profile Modal -->
<div id="buyerProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full max-w-2xl mx-4">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Buyer Profile</h2>
            <button onclick="closeBuyerModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" 
                             alt="Profile" class="w-24 h-24 rounded-full mx-auto object-cover">
                        <h3 id="modal-buyer-name" class="text-lg font-medium text-gray-900 mt-4"></h3>
                        <p class="text-gray-500 text-sm">Buyer</p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <p id="modal-first-name" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <p id="modal-last-name" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <p id="modal-email" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <p id="modal-phone" class="text-gray-900"></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Buyer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                                    <p id="modal-company" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                                    <p id="modal-business-type" class="text-gray-900"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <p id="modal-address" class="text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing buyer modal...');
        
        const modal = document.getElementById('buyerProfileModal');
        if (!modal) {
            console.error('Buyer modal not found!');
            return;
        }
        
        // Add click event to all "View Buyer Profile" buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.view-buyer-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.view-buyer-btn');
                
                // Get data from button attributes
                const buyerId = btn.dataset.buyerId;
                const firstName = btn.dataset.buyerFirstname;
                const lastName = btn.dataset.buyerLastname;
                const email = btn.dataset.buyerEmail;
                const phone = btn.dataset.buyerPhone;
                const company = btn.dataset.buyerCompany;
                const businessType = btn.dataset.buyerBusinesstype;
                const address = btn.dataset.buyerAddress;
                
                console.log('Opening modal with data:', {buyerId, firstName, lastName, email, phone, company, businessType, address});
                
                // Set modal content
                document.getElementById('modal-buyer-name').textContent = firstName + ' ' + lastName;
                document.getElementById('modal-first-name').textContent = firstName;
                document.getElementById('modal-last-name').textContent = lastName;
                document.getElementById('modal-email').textContent = email;
                document.getElementById('modal-phone').textContent = phone;
                document.getElementById('modal-company').textContent = company;
                document.getElementById('modal-business-type').textContent = businessType;
                document.getElementById('modal-address').textContent = address;
                
                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                console.log('Modal displayed');
            }
        });
        
        // Close modal when clicking outside or on close button
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });
        
        // Close button handler
        const closeBtn = modal.querySelector('button[onclick="closeBuyerModal()"]');
        if (closeBtn) {
            closeBtn.onclick = function(e) {
                e.preventDefault();
                closeModal();
            };
        }
        
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            console.log('Modal closed');
        }
        
        console.log('Buyer modal initialized successfully');
    });
</script>
@endsection