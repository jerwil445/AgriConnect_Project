@extends('layouts.farmers_page')

@section('title', 'Farmer Dashboard • AgriConnect')

@php
    $metrics = [
        ['label' => 'Active Listings', 'value' => $activeListings ?? 0, 'url' => route('farmer.products.index')],
        ['label' => 'Pending Orders', 'value' => $pendingOrders ?? 0, 'url' => route('farmer.orders')],
        ['label' => 'Total Sales', 'value' => '₱' . number_format($totalSales ?? 0, 2), 'url' => route('transactions.index')],
    ];
@endphp

@section('content')
    <div class="ml-64 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, Farmer! 👋</h1>
            <p class="text-sm text-gray-500">Here's what's happening on your farm today.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('farmer.products.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> New Listing
            </a>
            <a href="{{ route('farmer.orders') }}"
                class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm inline-flex items-center gap-2">
                <i class="fas fa-file-invoice"></i> Orders
            </a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 ml-64">
        @foreach ($metrics as $metric)
            <a href="{{ $metric['url'] }}"
                class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 sm:px-5 py-4 block hover:shadow-md transition-all hover:border-green-200 group">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">{{ $metric['label'] }}</p>
                    <i
                        class="fas fa-chevron-right text-gray-300 text-xs transition-transform group-hover:translate-x-1 group-hover:text-green-500"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-semibold text-gray-900 mt-2">{{ $metric['value'] }}</p>
                <span class="text-xs text-green-500 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fas fa-chevron-up text-[10px]"></i> This month +10%
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-6 sm:mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-2 ml-64 mb-8">

        <!-- Low Inventory Warnings -->
        <section
            class="bg-white rounded-2xl shadow-sm border border-red-100 p-4 sm:p-6 hover:shadow-md transition-shadow flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation text-red-500"></i> Low Inventory
                </h2>
                <a href="{{ route('farmer.products.index') }}"
                    class="text-xs text-red-600 hover:text-red-700 font-medium pb-0.5 border-b border-transparent hover:border-red-300 transition-colors">Manage
                    <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="space-y-4 flex-1">
                @forelse ($lowInventory as $item)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-red-600 font-bold">Only {{ $item->remainingInventory ? $item->remainingInventory->remaining_quantity : $item->quantity }} {{ $item->unit }} left!</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-6">
                        <i class="fas fa-boxes-stacked text-gray-300 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-500">Stock levels are good.</p>
                        <p class="text-xs text-gray-400 mt-1">No products currently running low.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Upcoming Deliveries -->
        <section
            class="bg-white rounded-2xl shadow-sm border border-orange-100 p-4 sm:p-6 hover:shadow-md transition-shadow flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-truck-fast text-orange-500"></i> Active Deliveries
                </h2>
                <a href="{{ route('farmer.orders') }}"
                    class="text-xs text-orange-600 hover:text-orange-700 font-medium pb-0.5 border-b border-transparent hover:border-orange-300 transition-colors">View
                    All <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="space-y-4 flex-1">
                @forelse ($upcomingDeliveries as $delivery)
                    <a href="{{ route('orders.show', $delivery->id) }}"
                        class="block p-3 bg-orange-50 rounded-xl border border-orange-100 hover:bg-orange-100 transition-colors cursor-pointer group">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Order
                                #{{ $delivery->id }} • {{ ucfirst($delivery->status) }}</p>
                            <i
                                class="fas fa-chevron-right text-orange-300 text-[10px] group-hover:text-orange-500 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                        <p class="text-xs text-gray-700 mt-1">{{ $delivery->product->product_name ?? 'Product' }} to
                            {{ $delivery->buyer->first_name ?? 'Buyer' }}</p>
                        <div class="mt-2 flex justify-end">
                            <span
                                class="text-[10px] font-bold uppercase tracking-wider text-orange-700 bg-orange-100/50 px-2 py-1 rounded border border-orange-200">
                                {{ $delivery->payment_method ?? 'Cash/COD' }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-6">
                        <i class="fas fa-route text-gray-300 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-500">No active shipments.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Recent Activity -->
        <!-- <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clock-rotate-left text-gray-500"></i> Recent Activity
                </h2>
                <a href="{{ route('farmer.orders') }}"
                    class="text-xs text-gray-500 hover:text-gray-700 font-medium pb-0.5 border-b border-transparent hover:border-gray-300 transition-colors">View
                    Logs <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="space-y-4 flex-1">
                @forelse ($activities as $activity)
                    <div class="flex items-start gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-600 flex-shrink-0">
                            <i class="fas {{ $activity['icon'] ?? 'fa-seedling' }} text-xs"></i>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-6">
                        <i class="fas fa-clipboard-list text-gray-300 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-500">No recent activity.</p>
                    </div>
                @endforelse
            </div>
        </section> -->

        <!-- Market Insights -->
        <!-- <section
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-shadow flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-chart-line text-purple-500"></i> Market Demand
                </h2>
                <a href="{{ route('demands.index') }}"
                    class="text-xs text-purple-600 hover:text-purple-700 font-medium pb-0.5 border-b border-transparent hover:border-purple-300 transition-colors">See
                    Demands <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="space-y-4 flex-1">
                @forelse ($marketDemands as $index => $demand)
                    @php
                        $colors = ['red', 'green', 'orange', 'blue', 'purple'];
                        $icons = ['fa-carrot', 'fa-leaf', 'fa-seedling', 'fa-apple-whole', 'fa-wheat-awn'];
                        $color = $colors[$index % count($colors)];
                        $icon = $icons[$index % count($icons)];
                    @endphp
                    <div
                        class="flex items-center justify-between p-3 bg-{{ $color }}-50 rounded-xl border border-{{ $color }}-100 transition-colors hover:bg-{{ $color }}-100">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-{{ $color }}-100 flex items-center justify-center text-{{ $color }}-600">
                                <i class="fas {{ $icon }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $demand->product_name }}</p>
                                <p class="text-xs text-{{ $color }}-600 font-medium">Top Demand</p>
                            </div>
                        </div>
                        <span
                            class="text-xs font-bold text-{{ $color }}-700 bg-white px-2 py-1 rounded shadow-sm border border-{{ $color }}-100">{{ $demand->buyers_count }}
                            buyers</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-6">
                        <i class="fas fa-chart-line text-gray-300 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-500">No market data available.</p>
                    </div>
                @endforelse
            </div>
        </section> -->
    </div>
@endsection