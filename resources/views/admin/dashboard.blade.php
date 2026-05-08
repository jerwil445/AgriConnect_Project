@extends('layouts.admin_page')
@vite('resources/css/app.css')
@section('content')
    <div class="ml-72 mr-5 mt-20 min-h-screen bg-gray-50/50 rounded-3xl p-4">
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Dashboard Overview</h1>
                        <p class="text-gray-500 mt-1">Real-time ecosystem analytics & user management</p>
                    </div>
                    <div
                        class="bg-white/50 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/40 shadow-sm flex items-center space-x-3">
                        <span class="flex h-3 w-3 relative">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-sm font-semibold text-gray-600">System Live</span>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Users Card with Breakdown -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-l-4 border-blue-500 rounded-r-xl bg-white/80 backdrop-blur-sm">
                        <div class="flex flex-col h-full">
                            <div class="flex items-center mb-4">
                                <div
                                    class="p-3 rounded-xl bg-blue-100 text-blue-600 mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Ecosystem
                                        Users</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                                </div>
                            </div>
                            <div class="mt-auto grid grid-cols-3 gap-2 pt-4 border-t border-gray-100/50">
                                <div class="text-center">
                                    <p class="text-xs font-medium text-gray-400">Farmers</p>
                                    <p class="text-sm font-bold text-green-600">{{ $totalFarmers }}</p>
                                </div>
                                <div class="text-center border-x border-gray-100/50">
                                    <p class="text-xs font-medium text-gray-400">Buyers</p>
                                    <p class="text-sm font-bold text-yellow-600">{{ $totalBuyers }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-medium text-gray-400">Admins</p>
                                    <p class="text-sm font-bold text-blue-600">{{ $totalAdmins }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Card -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-l-4 border-purple-500 rounded-r-xl bg-white/80 backdrop-blur-sm">
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-xl bg-purple-100 text-purple-600 mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Available Products
                                </p>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Demands Card -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-l-4 border-red-500 rounded-r-xl bg-white/80 backdrop-blur-sm">
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-xl bg-red-100 text-red-600 mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Market Demands</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalDemands }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Matches Card -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-l-4 border-indigo-500 rounded-r-xl bg-white/80 backdrop-blur-sm">
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-xl bg-indigo-100 text-indigo-600 mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Successful Matches
                                </p>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalMatches }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Card -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-l-4 border-pink-500 rounded-r-xl bg-white/80 backdrop-blur-sm">
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-xl bg-pink-100 text-pink-600 mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Transactions
                                    Completed</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalTransactions }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Card -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-l-4 border-teal-500 rounded-r-xl bg-white/80 backdrop-blur-sm">
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-xl bg-teal-100 text-teal-600 mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pending Alerts</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $pendingNotifications }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Primary Charts (Trends & Popularity) -->
                <div class="flex flex-col gap-8 mb-8">
                    <!-- Sales Trends Chart -->
                    <div
                        class="glassmorphic shadow-lg p-8 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40 rounded-[2rem]">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-black text-gray-900 flex items-center tracking-tight">
                                <div
                                    class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-chart-line text-xs"></i>
                                </div>
                                Sales Trends
                            </h2>
                            <select id="salesRangeFilter"
                                class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                                <option value="daily" {{ request('sales_range') == 'daily' ? 'selected' : '' }}>Daily (30
                                    Days)</option>
                                <option value="monthly" {{ request('sales_range', 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly (12 Months)</option>
                                <option value="yearly" {{ request('sales_range') == 'yearly' ? 'selected' : '' }}>Yearly (5
                                    Years)</option>
                            </select>
                        </div>
                        <div class="h-96">
                            <canvas id="salesTrendsChart"></canvas>
                        </div>
                    </div>

                    <!-- Product Popularity Chart -->
                    <div
                        class="glassmorphic shadow-lg p-8 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40 rounded-[2rem]">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-black text-gray-900 flex items-center tracking-tight">
                                <div
                                    class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-fire text-xs"></i>
                                </div>
                                Product Popularity
                            </h2>
                        </div>
                        <div class="h-96">
                            <canvas id="productPopularityChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Secondary Charts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-8">
                    <!-- Regional Demand Chart -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-red-500 rounded-full mr-2"></span>
                                Regional Demand
                            </h2>
                        </div>
                        <div class="h-64">
                            <canvas id="regionalDemandChart"></canvas>
                        </div>
                    </div>

                    <!-- Match Status Chart -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-indigo-500 rounded-full mr-2"></span>
                                Match Status Distribution
                            </h2>
                        </div>
                        <div class="h-64">
                            <canvas id="matchStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- Order Status Chart -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-pink-500 rounded-full mr-2"></span>
                                Active Orders
                            </h2>
                        </div>
                        <div class="h-64">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                    <!-- Top Farmers -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-green-500 rounded-full mr-2"></span>
                                Top Farmers
                            </h2>
                        </div>
                        <div class="h-64">
                            <canvas id="topFarmersChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Buyers -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-blue-500 rounded-full mr-2"></span>
                                Top Buyers
                            </h2>
                        </div>
                        <div class="h-64">
                            <canvas id="topBuyersChart"></canvas>
                        </div>
                    </div>

                    <!-- Supply vs Demand -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-orange-500 rounded-full mr-2"></span>
                                Supply vs Demand
                            </h2>
                        </div>
                        <div class="h-64">
                            <canvas id="supplyDemandChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Actionable Widgets Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                    <!-- Pending KYC Verifications -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-yellow-500 rounded-full mr-2"></span>
                                Pending KYC Verifications
                            </h2>
                            <span
                                class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs font-bold uppercase tracking-wider">
                                Needs Action
                            </span>
                        </div>

                        <div class="space-y-4">
                            @forelse($pendingKYCUsers as $user)
                                <div
                                    class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-white/40 hover:bg-white/80 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-4">
                                            {{ substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </p>
                                            <p class="text-xs text-gray-500 font-medium uppercase tracking-tighter">
                                                {{ $user->role }} &bull; Joined {{ $user->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.users.index', ['search' => $user->email]) }}"
                                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                                        Review
                                    </a>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div
                                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-500 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">All caught up! No pending verifications.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Ecosystem Activity -->
                    <div
                        class="glassmorphic shadow-lg p-6 group transition-all duration-300 hover:shadow-xl bg-white/80 backdrop-blur-sm border border-white/40">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-6 bg-green-500 rounded-full mr-2"></span>
                                Ecosystem Activity Feed
                            </h2>
                            <button class="text-xs font-bold text-blue-500 hover:text-blue-600 uppercase tracking-wider">
                                View Logs
                            </button>
                        </div>

                        <div
                            class="relative space-y-6 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-100 before:to-transparent">
                            @foreach ($recentActivities as $activity)
                                <div class="relative flex items-center justify-between group">
                                    <div class="flex items-center">
                                        <!-- Icon -->
                                        <div
                                            class="flex items-center justify-center w-10 h-10 rounded-full bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-600 relative z-10 group-hover:scale-110 transition-transform duration-300">
                                            {!! $activity['icon'] !!}
                                        </div>
                                        <!-- Content -->
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <span
                                                    class="text-xs font-bold text-{{ $activity['color'] }}-500 uppercase tracking-widest mr-2">{{ $activity['type'] }}</span>
                                                <span
                                                    class="text-[10px] text-gray-400 font-medium">{{ $activity['time']->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm font-bold text-gray-800">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity['subtitle'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include Chart.js Plugin for Data Labels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        document.getElementById('salesRangeFilter').addEventListener('change', function () {
            const range = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('sales_range', range);
            window.location.href = url.toString();
        });

        window.adminDashboardData = {
            salesTrends: {
                labels: [@foreach($salesTrends as $trend)'{{ $trend->date }}', @endforeach],
                datasets: [@foreach($salesTrends as $trend){{ $trend->total }}, @endforeach]
            },
            productPopularity: {
                labels: [@foreach($productPopularity as $product)'{{ $product->product_name }}', @endforeach],
                datasets: [@foreach($productPopularity as $product){{ $product->transaction_count }}, @endforeach]
            },
            regionalDemand: {
                labels: [@foreach($regionalDemand as $demand)'{{ $demand->province }}', @endforeach],
                datasets: [@foreach($regionalDemand as $demand){{ $demand->demand_count }}, @endforeach]
            },
            matchStatusDistribution: {
                labels: [@foreach($matchStatusDistribution as $status)'{{ $status->status }}', @endforeach],
                datasets: [@foreach($matchStatusDistribution as $status){{ $status->count }}, @endforeach]
            },
            orderStatusDistribution: {
                labels: [@foreach($orderStatusDistribution as $status)'{{ $status->delivery_status }}', @endforeach],
                datasets: [@foreach($orderStatusDistribution as $status){{ $status->count }}, @endforeach]
            },
            topFarmers: {
                labels: [@foreach($topFarmers as $farmer)'{{ $farmer->farmer_name }}', @endforeach],
                datasets: [@foreach($topFarmers as $farmer){{ $farmer->total_sales }}, @endforeach]
            },
            topBuyers: {
                labels: [@foreach($topBuyers as $buyer)'{{ $buyer->buyer_name }}', @endforeach],
                datasets: [@foreach($topBuyers as $buyer){{ $buyer->total_spent }}, @endforeach]
            },
            supplyDemandData: {
                labels: [@foreach($supplyDemandData as $data)'{{ $data['name'] }}', @endforeach],
                supply: [@foreach($supplyDemandData as $data){{ $data['supply'] }}, @endforeach],
                demand: [@foreach($supplyDemandData as $data){{ $data['demand'] }}, @endforeach]
            }
        };
    </script>
    @vite('resources/js/admin/admin-dashboard.js')
@endsection