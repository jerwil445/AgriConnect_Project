@extends('layouts.farmers_page')

@section('title', 'Analytics • AgriConnect')

@section('content')
<div class="ml-64">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Analytics & Insights</h1>
                <p class="text-sm text-gray-500 mt-1">Track your performance and business metrics</p>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-green-100">Total Revenue</p>
            <p class="text-3xl font-bold mt-1">₱{{ number_format($totalRevenue, 2) }}</p>
            <p class="text-xs text-green-100 mt-2">From {{ $completedOrders }} completed orders</p>
        </div>

        <!-- Average Order Value -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500">Avg Order Value</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">₱{{ number_format($avgOrderValue ?? 0, 2) }}</p>
            <p class="text-xs text-gray-500 mt-2">Per transaction</p>
        </div>

        <!-- Match Conversion Rate -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500">Conversion Rate</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $matchConversionRate }}%</p>
            <p class="text-xs text-gray-500 mt-2">Matches to orders</p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $completedOrders }} completed</p>
        </div>
    </div>

    <!-- Charts and Tables Grid -->
    <div class="grid gap-6 xl:grid-cols-2 mb-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Revenue Trend</h2>
                    <p class="text-sm text-gray-500">Monthly revenue for the last 12 months</p>
                </div>
            </div>

            @if($monthlyRevenue->count() > 0)
                <div class="relative h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm text-gray-500">No revenue data yet</p>
                </div>
            @endif
        </div>

        <!-- Order Status Breakdown -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Order Status</h2>
                    <p class="text-sm text-gray-500">Distribution by status</p>
                </div>
            </div>

            @if($ordersByStatus->count() > 0)
                <div class="space-y-4">
                    @foreach($ordersByStatus as $status)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-3 h-3 rounded-full 
                                @if($status->status == 'Ordered') bg-yellow-500
                                @elseif($status->status == 'Accepted') bg-green-500
                                @elseif($status->status == 'Prepared') bg-blue-500
                                @elseif($status->status == 'In Transit') bg-indigo-500
                                @elseif($status->status == 'Delivered') bg-emerald-500
                                @else bg-gray-500
                                @endif">
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $status->status }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full 
                                    @if($status->status == 'Ordered') bg-yellow-500
                                    @elseif($status->status == 'Accepted') bg-green-500
                                    @elseif($status->status == 'Prepared') bg-blue-500
                                    @elseif($status->status == 'In Transit') bg-indigo-500
                                    @elseif($status->status == 'Delivered') bg-emerald-500
                                    @else bg-gray-500
                                    @endif" 
                                    style="width: {{ ($status->count / $totalOrders) * 100 }}%">
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-12 text-right">{{ $status->count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-gray-500">No orders yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="grid gap-6 xl:grid-cols-2 mb-6">
        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Top Selling Products</h2>
                    <p class="text-sm text-gray-500">Best performers by sales</p>
                </div>
            </div>

            @if($topProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($topProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ ucfirst(str_replace('_', ' ', $product->egg_type)) }}</p>
                                <p class="text-xs text-gray-500">{{ $product->quantity }} {{ $product->unit }} available</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600">{{ $product->transactions_count }}</p>
                            <p class="text-xs text-gray-500">sales</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-sm text-gray-500">No products yet</p>
                </div>
            @endif
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
                    <p class="text-sm text-gray-500">Latest 10 transactions</p>
                </div>
            </div>

            @if($recentTransactions->count() > 0)
                <div class="space-y-3">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-blue-600">#{{ $transaction->id }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">₱{{ number_format($transaction->total_amount, 2) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full 
                                @if($transaction->status == 'Ordered') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status == 'Accepted') bg-green-100 text-green-800
                                @elseif($transaction->status == 'Prepared') bg-blue-100 text-blue-800
                                @elseif($transaction->status == 'In Transit') bg-indigo-100 text-indigo-800
                                @elseif($transaction->status == 'Delivered') bg-emerald-100 text-emerald-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $transaction->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-gray-500">No transactions yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Product & Match Stats -->
    <div class="grid gap-4 sm:grid-cols-3 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                    <p class="text-xs text-gray-500">Total Products</p>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-green-600 font-medium">{{ $availableProducts }} Available</span>
                <span class="text-red-600 font-medium">{{ $soldOutProducts }} Sold Out</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMatches }}</p>
                    <p class="text-xs text-gray-500">Total Matches</p>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-green-600 font-medium">{{ $acceptedMatches }} Accepted</span>
                <span class="text-gray-500 font-medium">{{ $totalMatches - $acceptedMatches }} Pending</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($matchConversionRate, 1) }}%</p>
                    <p class="text-xs text-gray-500">Success Rate</p>
                </div>
            </div>
            <p class="text-xs text-gray-500">Matches converted to orders</p>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($monthlyRevenue->count() > 0)
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const data = @json($monthlyRevenue);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => monthNames[item.month - 1]),
                datasets: [{
                    label: 'Revenue (₱)',
                    data: data.map(item => item.total),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endsection
