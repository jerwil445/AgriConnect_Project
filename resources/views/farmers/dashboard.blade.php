@extends('layouts.farmers_page')

@section('title', 'Farmer Dashboard • AgriConnect')

@section('content')
<div class="ml-64">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl shadow-sm p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->first_name }}!</h1>
                <p class="text-green-100 mt-1">Manage your farm products and track your sales at a glance.</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500">Total Products</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $availableProducts }} available</p>
        </div>

        <!-- New Matches -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($newMatches > 0)
                    <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full animate-pulse">{{ $newMatches }} New</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">Total Matches</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalMatches }}</p>
            <a href="{{ route('farmer.matches') }}" class="text-xs text-green-600 hover:text-green-700 mt-2 inline-block">View all matches →</a>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                @if($pendingOrders > 0)
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-600 text-xs font-bold rounded-full">Action needed</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">Pending Orders</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $pendingOrders }}</p>
            <a href="{{ route('farmer.orders') }}" class="text-xs text-yellow-600 hover:text-yellow-700 mt-2 inline-block">View orders →</a>
        </div>

        <!-- Total Sales -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500">Total Sales</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">₱{{ number_format($totalSales, 2) }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $activeChats }} active chats</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid gap-6 xl:grid-cols-[1.2fr_1fr] mb-6">
        <!-- Recent Orders -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                    <p class="text-sm text-gray-500">Latest transactions from buyers</p>
                </div>
                <a href="{{ route('farmer.orders') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">View all →</a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">Order #{{ $order->id }}</p>
                                <p class="text-xs text-gray-500">{{ $order->buyer->first_name }} {{ $order->buyer->last_name }} • {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-sm font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full 
                                @if($order->status == 'Ordered') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'Accepted') bg-green-100 text-green-800
                                @elseif($order->status == 'Prepared') bg-blue-100 text-blue-800
                                @elseif($order->status == 'In Transit') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">No orders yet</p>
                </div>
            @endif
        </section>

        <!-- Top Products -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Top Products</h2>
                    <p class="text-sm text-gray-500">Most matched products</p>
                </div>
            </div>

            @if($topProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($topProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ ucfirst(str_replace('_', ' ', $product->egg_type)) }}</p>
                                <p class="text-xs text-gray-500">{{ $product->quantity }} {{ $product->unit }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600">{{ $product->matches_count }}</p>
                            <p class="text-xs text-gray-500">matches</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500">No products yet</p>
                    <a href="{{ route('products.create') }}" class="text-xs text-green-600 hover:text-green-700 mt-2 inline-block">Add your first product →</a>
                </div>
            @endif
        </section>
    </div>

    <!-- Quick Actions -->
    <div class="grid md:grid-cols-4 gap-4">
        <a href="{{ route('products.create') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md hover:border-green-300 transition group">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-200 transition">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Add Product</h3>
            <p class="text-xs text-gray-500 mt-1">List new eggs for sale</p>
        </a>

        <a href="{{ route('farmer.matches') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md hover:border-blue-300 transition group">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-200 transition">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">View Matches</h3>
            <p class="text-xs text-gray-500 mt-1">Check buyer demands</p>
        </a>

        <a href="{{ route('farmer.orders') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md hover:border-yellow-300 transition group">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-200 transition">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Manage Orders</h3>
            <p class="text-xs text-gray-500 mt-1">Track your orders</p>
        </a>

        <a href="{{ route('farmer.messages') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md hover:border-purple-300 transition group">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 transition">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Messages</h3>
            <p class="text-xs text-gray-500 mt-1">Chat with buyers</p>
        </a>
    </div>
</div>
@endsection
