@extends('layouts.admin_page')
@vite('resources/css/app.css')
@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden min-h-screen font-sans">
    
    <!-- Ambient Background Accents -->
    <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-indigo-100/30 rounded-full blur-[100px] -mt-40 -mr-40 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-emerald-100/30 rounded-full blur-[80px] -mb-40 -ml-40 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header section -->
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-800 tracking-tight leading-none mb-2">Systems Intelligence</h1>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-[0.2em] opacity-60 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        AgriConnect Core Dashboard | Real-time Analytics
                    </p>
                </div>
            </div>

            <!-- Major KPIs: Categorized Color Theme -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                <!-- Stat Card: Users (Blue) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 shadow-sm border border-blue-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-blue-400 bg-blue-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Identity Management</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalUsers }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total Registered Users</p>
                </div>

                <!-- Stat Card: Farmers (Green) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-tractor text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-emerald-400 bg-emerald-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Supply Side</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalFarmers }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Active Suppliers</p>
                </div>

                <!-- Stat Card: Buyers (Yellow/Amber) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 shadow-sm border border-amber-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-shopping-bag text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-amber-400 bg-amber-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Demand Side</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalBuyers }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Verified Procurements</p>
                </div>

                <!-- Stat Card: Products (Purple) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-purple-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 shadow-sm border border-purple-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-boxes text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-purple-400 bg-purple-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Catalog Size</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalProducts }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Live Market Listings</p>
                </div>

                <!-- Stat Card: Demands (Red) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 shadow-sm border border-rose-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-bullseye text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-rose-400 bg-rose-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Market Needs</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalDemands }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Open Requirements</p>
                </div>

                <!-- Stat Card: Matches (Indigo) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500 shadow-sm border border-indigo-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-handshake text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-indigo-400 bg-indigo-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Connections</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalMatches }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Sync Intelligence Active</p>
                </div>

                <!-- Stat Card: Transactions (Pink) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-pink-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-pink-50 rounded-2xl flex items-center justify-center text-pink-500 shadow-sm border border-pink-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-invoice-dollar text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-pink-400 bg-pink-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Settlements</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $totalTransactions }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Agreement Flows Finalized</p>
                </div>

                <!-- Stat Card: Notifications (Teal) -->
                <div class="group bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-teal-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-500 shadow-sm border border-teal-100 group-hover:scale-110 transition-transform">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black text-teal-400 bg-teal-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">Telemetry</span>
                    </div>
                    <div class="text-3xl font-black text-gray-800 leading-none mb-1">{{ $pendingNotifications }}</div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Pending System Alerts</p>
                </div>

            </div>

            <!-- Visualization Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
                
                <!-- Main Trend Chart -->
                <div class="lg:col-span-8 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="text-lg font-black text-gray-800 leading-none">Revenue Trajectory</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Aggregated system sales performance</p>
                        </div>
                    </div>
                    <div class="h-[20rem] relative">
                        <canvas id="salesTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Product Popularity -->
                <div class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm flex flex-col">
                    <div class="mb-10 text-center">
                        <h3 class="text-lg font-black text-gray-800 leading-none">Inventory Volume</h3>
                        <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Active units per category</p>
                    </div>
                    <div class="h-[20rem] relative flex-grow">
                        <canvas id="productPopularityChart"></canvas>
                    </div>
                </div>

                <!-- Regional Demand Pie -->
                <div class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm">
                    <div class="mb-8 text-center">
                        <h3 class="text-lg font-black text-gray-800 leading-none">Supply Distribution</h3>
                        <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Regional Market Concentration</p>
                    </div>
                    <div class="h-[20rem] relative">
                        <canvas id="regionalDemandChart"></canvas>
                    </div>
                </div>

                <!-- Match Lifecycle -->
                <div class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm text-center">
                    <div class="mb-8">
                        <h3 class="text-lg font-black text-gray-800 leading-none">Sync Intelligence</h3>
                        <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Match lifecycle distribution</p>
                    </div>
                    <div class="h-[20rem] relative flex justify-center">
                        <canvas id="matchStatusChart"></canvas>
                    </div>
                </div>

                <!-- Order Status Chart -->
                <div class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-sm flex flex-col">
                    <div class="mb-10 lg:text-right">
                        <h3 class="text-lg font-black text-gray-800 leading-none">Logistics Tracking</h3>
                        <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Active orders by status</p>
                    </div>
                    <div class="h-[20rem] relative flex-grow">
                        <canvas id="orderStatusChart"></canvas>
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
    // Register the data labels plugin
    Chart.register(ChartDataLabels);

    // Global Font Styling
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = "#9ca3af";

    // Data Parsing logic from PHP
    const salesTrendsData = [@foreach($salesTrends as $trend) { date: '{{$trend->date}}', total: {{$trend->total ?: 0}} },@endforeach];
    const productPopularityData = [@foreach($productPopularity as $p) { name: '{{$p->product_name}}', count: {{$p->transaction_count}} },@endforeach];
    const regionalDemandData = [@foreach($regionalDemand as $d) { location: '{{$d->location}}', count: {{$d->demand_count}} },@endforeach];
    const matchStatusData = [@foreach($matchStatusDistribution as $s) { status: '{{$s->status}}', count: {{$s->count}} },@endforeach];
    const orderStatusData = [@foreach($orderStatusDistribution as $s) { status: '{{$s->delivery_status}}', count: {{$s->count}} },@endforeach];

    // Totals for Percentages
    const regionalDemandTotal = regionalDemandData.reduce((sum, item) => sum + item.count, 0);
    const matchStatusTotal = matchStatusData.reduce((sum, item) => sum + item.count, 0);

    // Sales Trends: Area Chart (Blue/Teal theme)
    new Chart(document.getElementById('salesTrendsChart'), {
        type: 'line',
        data: {
            labels: salesTrendsData.map(d => d.date),
            datasets: [{
                label: 'Revenue',
                data: salesTrendsData.map(d => d.total),
                borderColor: '#4db6ac',
                backgroundColor: 'rgba(77, 182, 172, 0.1)',
                borderWidth: 3,
                pointRadius: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, datalabels: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            }
        }
    });

    // Product Popularity: Bar Chart (Blue theme)
    new Chart(document.getElementById('productPopularityChart'), {
        type: 'bar',
        data: {
            labels: productPopularityData.map(d => d.name),
            datasets: [{
                label: 'Transaction Count',
                data: productPopularityData.map(d => d.count),
                backgroundColor: 'rgba(54, 162, 235, 0.4)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                datalabels: { anchor: 'end', align: 'top', font: { weight: 'bold', size: 10 } }
            }
        }
    });

    // Regional Demand: Pie Chart (Multi-color theme)
    new Chart(document.getElementById('regionalDemandChart'), {
        type: 'pie',
        data: {
            labels: regionalDemandData.map(item => item.location),
            datasets: [{
                data: regionalDemandData.map(item => item.count),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 205, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } },
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => { sum += data; });
                        return (value * 100 / sum).toFixed(0) + "%";
                    },
                    color: '#333',
                    font: { weight: 'bold', size: 10 }
                }
            }
        }
    });

    // Match Status: Doughnut Chart (Multi-color theme)
    new Chart(document.getElementById('matchStatusChart'), {
        type: 'doughnut',
        data: {
            labels: matchStatusData.map(item => item.status),
            datasets: [{
                data: matchStatusData.map(item => item.count),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 205, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)'
                ],
                borderWidth: 0,
                cutout: '70%',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } },
                datalabels: { display: false }
            }
        }
    });

    // Order Status: Bar Chart (Purple theme)
    new Chart(document.getElementById('orderStatusChart'), {
        type: 'bar',
        data: {
            labels: orderStatusData.map(d => d.status),
            datasets: [{
                label: 'Order Count',
                data: orderStatusData.map(d => d.count),
                backgroundColor: 'rgba(153, 102, 255, 0.4)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                datalabels: { anchor: 'end', align: 'top', font: { weight: 'bold', size: 10 } }
            }
        }
    });
</script>

<style>
/* Custom Scrollbar for better UI experience */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>
@endsection