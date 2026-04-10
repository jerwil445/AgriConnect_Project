@extends('layouts.farmers_page')

@section('title', 'Analytics • AgriConnect')

@section('content')
    <div class="ml-64 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Analytics Overview</h1>
                <p class="text-sm text-gray-500">Track your business performance and market trends over the last 30 days.
                </p>
            </div>
            <div
                class="bg-white border border-gray-200 shadow-sm rounded-lg px-3 py-1.5 flex items-center text-sm font-medium text-gray-600">
                <i class="far fa-calendar-alt mr-2 text-green-600"></i> Last 30 Days
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid gap-4 sm:grid-cols-3 mb-8">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <span class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-600"><i
                            class="fas fa-peso-sign"></i></span>
                </div>
                <p class="text-3xl font-bold text-gray-900">₱{{ number_format($totalRevenue, 2) }}</p>
                <p class="text-xs text-green-500 font-medium mt-2"><i class="fas fa-arrow-trend-up mr-1"></i> Based on
                    completed sales</p>
            </div>
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-medium text-gray-500">Total Orders</p>
                    <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600"><i
                            class="fas fa-box-open"></i></span>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                <p class="text-xs text-blue-500 font-medium mt-2"><i class="fas fa-check-double mr-1"></i> Successfully
                    fulfilled</p>
            </div>
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-medium text-gray-500">Top Performing Product</p>
                    <span class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-600"><i
                            class="fas fa-crown"></i></span>
                </div>
                <p class="text-xl font-bold text-gray-900 truncate mt-1">
                    {{ count($productLabels) > 0 ? $productLabels[0] : 'No Data' }}
                </p>
                <p class="text-xs text-purple-500 font-medium mt-2"><i class="fas fa-star mr-1"></i> Best seller by revenue
                </p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <!-- Revenue Trend Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Revenue Trend</h2>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">30 Days</span>
                </div>
                <!-- If there's absolutely NO revenue, show a placeholder, else show canvas -->
                @if($totalRevenue == 0)
                    <div
                        class="flex flex-col items-center justify-center h-64 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <i class="fas fa-chart-line text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">No revenue data for the last 30 days yet.</p>
                    </div>
                @else
                    <div class="relative h-72 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                @endif
            </div>

            <!-- Top Products Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Top Selling Products</h2>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Top 5</span>
                </div>
                @if(count($productLabels) == 0)
                    <div
                        class="flex flex-col items-center justify-center h-56 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <i class="fas fa-box text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">Complete an order to see your top products.</p>
                    </div>
                @else
                    <div class="relative h-64 w-full">
                        <canvas id="productsChart"></canvas>
                    </div>
                @endif
            </div>

            <!-- Order Success Rate -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Order Fulfillment Rate</h2>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">All Time</span>
                </div>
                @if(array_sum($orderRateData) == 0)
                    <div
                        class="flex flex-col items-center justify-center h-56 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <i class="fas fa-chart-pie text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">No orders received yet.</p>
                    </div>
                @else
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="orderRateChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js Resources -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script>
        window.farmerAnalyticsData = {
            trendLabels: {!! json_encode($trendLabels) !!},
            trendValues: {!! json_encode($trendValues) !!},
            productLabels: {!! json_encode($productLabels) !!},
            productRevenues: {!! json_encode($productRevenues) !!},
            orderRateData: {!! json_encode($orderRateData) !!}
        };
    </script>
    @vite('resources/js/farmer/farmer-analytics.js')

@endsection