@extends('layouts.admin_page')
@vite('resources/css/app.css')
@section('content')
    <div
        class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden min-h-screen">

        <!-- Ambient Background Accents -->
        <div
            class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-indigo-100/30 rounded-full blur-[100px] -mt-40 -mr-40 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-emerald-100/30 rounded-full blur-[80px] -mb-40 -ml-40 pointer-events-none">
        </div>

        <main class="relative z-10 flex-1 p-8 lg:p-12">
            <div class="max-w-7xl mx-auto">

                <!-- Header -->
                <div class="mb-12">
                    <h1 class="text-4xl font-black text-gray-800 tracking-tight leading-none mb-2">Systems Intelligence</h1>
                    <p
                        class="text-sm font-bold text-gray-500 uppercase tracking-[0.2em] opacity-60 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        AgriConnect Core Dashboard | Real-time Analytics
                    </p>
                </div>

                <!-- Major KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

                    <!-- Stat Card: Users -->
                    <div
                        class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 shadow-sm border border-blue-100 group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <span
                                class="text-[10px] font-black text-blue-400 bg-blue-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Total
                                Identites</span>
                        </div>
                        <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalUsers }}</div>
                        <p class="text-[11px] font-bold text-gray-400 flex items-center gap-1.5 uppercase tracking-wide">
                            <span class="text-blue-500">{{ $totalFarmers }} Farmers</span> | <span
                                class="text-indigo-500">{{ $totalBuyers }} Buyers</span>
                        </p>
                    </div>

                    <!-- Stat Card: Supply -->
                    <div
                        class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform">
                                <i class="fas fa-boxes text-xl"></i>
                            </div>
                            <span
                                class="text-[10px] font-black text-emerald-400 bg-emerald-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Inventory
                                Count</span>
                        </div>
                        <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalProducts }}</div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Active Market Listings</p>
                    </div>

                    <!-- Stat Card: Demand -->
                    <div
                        class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 shadow-sm border border-amber-100 group-hover:scale-110 transition-transform">
                                <i class="fas fa-bullseye text-xl"></i>
                            </div>
                            <span
                                class="text-[10px] font-black text-amber-400 bg-amber-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Requests
                                Open</span>
                        </div>
                        <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalDemands }}</div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Aggregated Buyer Demand</p>
                    </div>

                    <!-- Stat Card: Settlements -->
                    <div
                        class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-purple-500/5 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 shadow-sm border border-purple-100 group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-invoice-dollar text-xl"></i>
                            </div>
                            <span
                                class="text-[10px] font-black text-purple-400 bg-purple-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Settlements</span>
                        </div>
                        <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalTransactions }}</div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Finalized Agreement Flows</p>
                    </div>

                </div>

                <!-- Visualization Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">

                    <!-- Main Trend Chart -->
                    <div
                        class="lg:col-span-8 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-10">
                            <div>
                                <h3 class="text-lg font-black text-gray-800 leading-none">Revenue Trajectory</h3>
                                <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Aggregated system sales
                                    performance</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-3 h-3 rounded-full bg-indigo-500 border-2 border-white shadow-sm shadow-indigo-500/20">
                                </div>
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Market Value
                                    Index</span>
                            </div>
                        </div>
                        <div class="h-[18rem] relative">
                            <canvas id="salesTrendsChart"></canvas>
                        </div>
                    </div>

                    <!-- Product Popularity -->
                    <div
                        class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm flex flex-col">
                        <div class="mb-10">
                            <h3 class="text-lg font-black text-gray-800 leading-none">Inventory Volume</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Active units per category</p>
                        </div>
                        <div class="h-[18rem] relative flex-grow">
                            <canvas id="productPopularityChart"></canvas>
                        </div>
                    </div>

                    <!-- Regional Demand Pie -->
                    <div
                        class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm">
                        <div class="mb-8 text-center">
                            <h3 class="text-lg font-black text-gray-800 leading-none">Supply Distribution</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Regional Market Concentration</p>
                        </div>
                        <div class="h-[18rem] relative">
                            <canvas id="regionalDemandChart"></canvas>
                        </div>
                    </div>

                    <!-- Match Lifecycle -->
                    <div
                        class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm text-center">
                        <div class="mb-8">
                            <h3 class="text-lg font-black text-gray-800 leading-none">Sync Intelligence</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Match lifecycle distribution</p>
                        </div>
                        <div class="h-[18rem] relative flex justify-center">
                            <canvas id="matchStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- Logistics Overvew -->
                    <div
                        class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm flex flex-col">
                        <div class="mb-10 lg:text-right">
                            <h3 class="text-lg font-black text-gray-800 leading-none">Logistics Tracking</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Current delivery fulfillment
                                states</p>
                        </div>
                        <div class="h-[18rem] relative flex-grow">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Footer Stats (Notifications) -->
                <div
                    class="bg-indigo-600 rounded-[2rem] p-10 flex flex-col md:flex-row items-center justify-between gap-8 shadow-xl shadow-indigo-600/20 overflow-hidden relative group">
                    <div
                        class="absolute -right-10 -top-10 text-white/5 text-[15rem] pointer-events-none group-hover:scale-110 group-hover:rotate-12 transition-transform duration-700 leading-none opacity-20">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="flex items-center gap-6 relative z-10">
                        <div
                            class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center text-white text-3xl shadow-lg border border-white/10">
                            <i class="fas fa-broadcast-tower animate-ping absolute text-sm opacity-50"></i>
                            <i class="fas fa-bell relative z-20"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white leading-none">Pending Notifications</h3>
                            <p class="text-indigo-100 font-bold uppercase tracking-widest text-[10px] mt-2">Active system
                                alerts requiring attention</p>
                        </div>
                    </div>
                    <div class="text-center md:text-right relative z-10">
                        <div class="text-6xl font-black text-white leading-none mb-2">{{ $pendingNotifications }}</div>
                        <span
                            class="bg-white/10 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-white/20 backdrop-blur-sm">System
                            Priority High</span>
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
        // Register the data labels plugin
        Chart.register(ChartDataLabels);

        // Global Font Styling
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = "#9ca3af";

        // Parse logic
        const salesTrendsData = [@foreach($salesTrends as $trend) { date: '{{$trend->date}}', total: {{$trend->total ?: 0}} }, @endforeach];
        const productPopularityData = [@foreach($productPopularity as $p) { name: '{{$p->product_name}}', count: {{$p->transaction_count}} }, @endforeach];
        const regionalDemandData = [@foreach($regionalDemand as $d) { location: '{{$d->location}}', count: {{$d->demand_count}} }, @endforeach];
        const matchStatusData = [@foreach($matchStatusDistribution as $s) { status: '{{$s->status}}', count: {{$s->count}} }, @endforeach];
        const orderStatusData = [@foreach($orderStatusDistribution as $s) { status: '{{$s->delivery_status}}', count: {{$s->count}} }, @endforeach];

        // Chart Configuration Helpers
        const createGrad = (ctx, start, stop) => {
            const h = ctx.canvas.height;
            const g = ctx.createLinearGradient(0, 0, 0, h);
            g.addColorStop(0, start);
            g.addColorStop(1, stop);
            return g;
        };

        // Sales Trends: Premium Area Chart
        new Chart(document.getElementById('salesTrendsChart'), {
            type: 'line',
            data: {
                labels: salesTrendsData.map(d => d.date),
                datasets: [{
                    label: 'System Revenue',
                    data: salesTrendsData.map(d => d.total),
                    borderColor: '#6366f1',
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    backgroundColor: (context) => {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                        return gradient;
                    },
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, datalabels: { display: false } },
                scales: {
                    y: { grid: { borderDash: [5, 5], color: '#f3f4f6' }, border: { display: false }, ticks: { font: { weight: 'bold', size: 10 } } },
                    x: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: 'bold', size: 10 } } }
                }
            }
        });

        // Product Popularity: Polished Bar
        new Chart(document.getElementById('productPopularityChart'), {
            type: 'bar',
            data: {
                labels: productPopularityData.map(d => d.name),
                datasets: [{
                    data: productPopularityData.map(d => d.count),
                    backgroundColor: '#10b981',
                    borderRadius: 12,
                    hoverBackgroundColor: '#059669',
                    maxBarThickness: 30
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, datalabels: { color: '#000', font: { weight: 'black', size: 9 }, anchor: 'end', align: 'right' } },
                scales: {
                    x: { grid: { display: false }, border: { display: false }, ticks: { display: false } },
                    y: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: 'black', size: 10 }, color: '#374151' } }
                }
            }
        });

        // Regional: Minimalist Pie
        new Chart(document.getElementById('regionalDemandChart'), {
            type: 'pie',
            data: {
                labels: regionalDemandData.map(d => d.location),
                datasets: [{
                    data: regionalDemandData.map(d => d.count),
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 8, padding: 20, font: { weight: 'bold', size: 10 } } },
                    datalabels: {
                        color: '#fff', font: { weight: 'black', size: 9 }, formatter: (v, ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            return Math.round((v / total) * 100) + '%';
                        }
                    }
                }
            }
        });

        // Match Status: Premium Doughnut
        new Chart(document.getElementById('matchStatusChart'), {
            type: 'doughnut',
            data: {
                labels: matchStatusData.map(d => d.status),
                datasets: [{
                    data: matchStatusData.map(d => d.count),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                    borderWidth: 0,
                    hoverOffset: 10,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 8, padding: 15, font: { weight: 'bold', size: 10 } } },
                    datalabels: { display: false }
                }
            }
        });

        // Order Status: Polished Bar
        new Chart(document.getElementById('orderStatusChart'), {
            type: 'bar',
            data: {
                labels: orderStatusData.map(d => d.status),
                datasets: [{
                    data: orderStatusData.map(d => d.count),
                    backgroundColor: '#3b82f6',
                    borderRadius: 12,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, datalabels: { font: { weight: 'black', size: 10 }, anchor: 'end', align: 'top' } },
                scales: {
                    y: { grid: { borderDash: [5, 5], color: '#f3f4f6' }, border: { display: false }, ticks: { font: { weight: 'bold' } } },
                    x: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: 'black', size: 9 }, color: '#374151' } }
                }
            }
        });

    </script>

    <style>
        /* Custom Scrollbar for better UI experience */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
    </style>
@endsection