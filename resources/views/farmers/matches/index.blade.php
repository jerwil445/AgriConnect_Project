@extends('layouts.farmers_page')

@section('content')
<div class="ml-64">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('farmer.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Buyer Matches</h2>
                    <p class="text-sm text-gray-500">Potential buyers interested in your products</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('products.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    My Products
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

    @if($products->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Products Listed</h3>
            <p class="text-gray-500 mb-6">You need to list products before buyers can find and match with you.</p>
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Your First Product
            </a>
        </div>
    @else
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $products->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Matches</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $products->sum(fn($p) => $p->matches->count()) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">New Matches</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $products->sum(fn($p) => $p->matches->where('status', 'New')->count()) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Active Chats</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $products->sum(fn($p) => $p->matches->where('status', 'Transaction Started')->count()) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Products with Matches -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
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
                                                            </h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        {{ $product->quantity }} {{ $product->unit }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-green-50 text-green-700 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        ₱{{ number_format($product->price, 2) }}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($product->status == 'Available') bg-green-100 text-green-800
                                        @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $product->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Match Badge -->
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $product->matches->count() }} Buyer Matches</p>
                                    <p class="text-xs text-gray-500">{{ $product->matches->where('status', 'New')->count() }} new</p>
                                </div>
                            </div>
                            @if($product->matches->where('status', 'New')->count() > 0)
                                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            @endif
                        </div>
                        
                        <!-- Recent Matches Preview -->
                        <div>
                            @if($product->matches->isEmpty())
                                <div class="text-center py-6 bg-gray-50 rounded-lg">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">No buyer matches yet</p>
                                    <p class="text-xs text-gray-400 mt-1">System will auto-match when buyers post demands</p>
                                </div>
                            @else
                                <div class="space-y-2 mb-4">
                                    @foreach($product->matches->take(2) as $match)
                                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="text-sm font-medium text-gray-900">
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
                                                    {{ $eggTypes[$match->demand->egg_type] ?? ucfirst(str_replace('_', ' ', $match->demand->egg_type)) }}
                                                </span>
                                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                                    @if($match->status == 'New') bg-blue-100 text-blue-800
                                                    @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($match->status == 'Matched') bg-green-100 text-green-800
                                                    @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                                    @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $match->status }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between text-xs text-gray-600">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    {{ $match->demand->quantity }} {{ $match->demand->unit ?? 'trays' }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ Str::limit($match->demand->location, 15) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <a href="{{ route('farmer.product.matches', $product) }}" 
                                   class="block w-full text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                                    View All {{ $product->matches->count() }} Matches
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection