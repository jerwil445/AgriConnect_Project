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
                    <h2 class="text-lg font-semibold text-gray-900">Revenue Trend
                        ({{ ucfirst(request('revenue_range', 'daily')) }})</h2>
                    <select id="revenueRangeFilter"
                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all cursor-pointer">
                        <option value="daily" {{ request('revenue_range') == 'daily' ? 'selected' : '' }}>Daily (30 Days)
                        </option>
                        <option value="monthly" {{ request('revenue_range') == 'monthly' ? 'selected' : '' }}>Monthly (12
                            Months)</option>
                        <option value="yearly" {{ request('revenue_range') == 'yearly' ? 'selected' : '' }}>Yearly (5 Years)
                        </option>
                    </select>
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

        <!-- Comprehensive Market Insights -->
        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <!-- Regional Demand -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Regional Market Demand</h2>
                    <i class="fas fa-globe-asia text-green-500"></i>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="regionalDemandChart"></canvas>
                </div>
            </div>

            <!-- Supply vs Demand -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Supply vs Market Demand</h2>
                    <i class="fas fa-balance-scale text-orange-500"></i>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="supplyDemandChart"></canvas>
                </div>
            </div>

            <!-- Match Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Demand Match Insights</h2>
                    <i class="fas fa-handshake text-blue-500"></i>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="matchStatusChart"></canvas>
                </div>
            </div>

            <!-- Detailed Delivery Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Delivery Status Distribution</h2>
                    <i class="fas fa-truck text-purple-500"></i>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="deliveryStatusChart"></canvas>
                </div>
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
            orderRateData: {!! json_encode($orderRateData) !!},
            regionalDemand: {
                labels: {!! json_encode($regionalDemand->pluck('province')) !!},
                datasets: {!! json_encode($regionalDemand->pluck('demand_count')) !!}
            },
            matchStatus: {
                labels: {!! json_encode($matchStatusDistribution->pluck('status')) !!},
                datasets: {!! json_encode($matchStatusDistribution->pluck('count')) !!}
            },
            deliveryStatus: {
                labels: {!! json_encode($orderStatusDistribution->pluck('status')) !!},
                datasets: {!! json_encode($orderStatusDistribution->pluck('count')) !!}
            },
            supplyDemand: {
                labels: {!! json_encode($supplyDemandData['labels']) !!},
                supply: {!! json_encode(array_values($supplyDemandData['supply']->toArray())) !!},
                demand: {!! json_encode(array_values($supplyDemandData['demand']->toArray())) !!}
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown handler
            const filter = document.getElementById('revenueRangeFilter');
            if (filter) {
                filter.addEventListener('change', function () {
                    const range = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('revenue_range', range);
                    window.location.href = url.toString();
                });
            }
        });
    </script>
    @vite('resources/js/farmer/farmer-analytics.js')

    <script>
        // Register datalabels plugin globally immediately
        if (typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
            console.log('ChartDataLabels registered successfully');
        } else {
            console.error('ChartDataLabels not found');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const data = window.farmerAnalyticsData;
            if (!data) return;

            // 1. Revenue Trend Chart (Large dots, premium tooltips)
            console.log('Initializing Farmer Revenue Trend with All Days forced');
            const revenueCanvas = document.getElementById('revenueChart');
            if (revenueCanvas) {
                const rtx = revenueCanvas.getContext('2d');
                const gradient = rtx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(22, 163, 74, 0.2)');
                gradient.addColorStop(1, 'rgba(22, 163, 74, 0)');

                new Chart(rtx, {
                    type: 'line',
                    data: {
                        labels: data.trendLabels,
                        datasets: [{
                            label: 'Revenue (₱)',
                            data: data.trendValues,
                            borderColor: '#16a34a',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#16a34a',
                            pointBorderWidth: 2.5,
                            pointRadius: 5,
                            pointHoverRadius: 10,
                            pointHoverBackgroundColor: '#16a34a',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 3,
                            pointHitRadius: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onHover: (event, chartElement) => {
                            event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                        },
                        interaction: {
                            mode: 'nearest',
                            intersect: true,
                        },
                        plugins: {
                            datalabels: {
                                display: false
                            },
                            legend: { display: true, position: 'top' },
                            tooltip: {
                                enabled: true,
                                backgroundColor: 'rgba(255, 255, 255, 0.98)',
                                titleColor: '#111827',
                                bodyColor: '#4b5563',
                                borderColor: '#e2e8f0',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 12,
                                displayColors: true,
                                usePointStyle: true,
                                boxPadding: 6,
                                callbacks: {
                                    label: function (context) {
                                        const value = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(context.parsed.y);
                                        return 'Revenue: ' + value;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { 
                                grid: { display: false },
                                ticks: {
                                    autoSkip: false,
                                    source: 'labels',
                                    maxTicksLimit: 100,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f3f4f6', borderDash: [5, 5] },
                                ticks: {
                                    callback: function (value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Top Products Bar Chart
            const productsCanvas = document.getElementById('productsChart');
            if (productsCanvas) {
                new Chart(productsCanvas, {
                    type: 'bar',
                    data: {
                        labels: data.productLabels,
                        datasets: [{
                            label: 'Total Revenue (₱)',
                            data: data.productRevenues,
                            backgroundColor: [
                                '#3b82f6', // Blue
                                '#10b981', // Green
                                '#f59e0b', // Amber
                                '#ef4444', // Red
                                '#8b5cf6', // Purple
                                '#06b6d4', // Cyan
                                '#f43f5e'  // Rose
                            ],
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                display: true,
                                color: '#fff',
                                font: { weight: 'bold' },
                                formatter: (value) => '₱' + value.toLocaleString()
                            },
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                callbacks: {
                                    label: function (context) {
                                        return '₱' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 3. Order Rate Doughnut
            const orderRateCanvas = document.getElementById('orderRateChart');
            if (orderRateCanvas) {
                new Chart(orderRateCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'Cancelled', 'Rejected'],
                        datasets: [{
                            data: data.orderRateData,
                            backgroundColor: ['#16a34a', '#f97316', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            datalabels: {
                                display: true,
                                color: '#fff',
                                font: { weight: 'bold' },
                                formatter: (value, ctx) => {
                                    let sum = 0;
                                    let dataArr = ctx.chart.data.datasets[0].data;
                                    dataArr.map(data => { sum += data; });
                                    let percentage = (value * 100 / sum).toFixed(1) + "%";
                                    return value > 0 ? percentage : '';
                                }
                            },
                            legend: { position: 'right' }
                        }
                    }
                });
            }

            // 4. Regional Demand
            const regionalCanvas = document.getElementById('regionalDemandChart');
            if (regionalCanvas) {
                new Chart(regionalCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: data.regionalDemand.labels,
                        datasets: [{
                            data: data.regionalDemand.datasets,
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            datalabels: {
                                display: true,
                                color: '#fff',
                                font: { weight: 'bold' },
                                anchor: 'center',
                                align: 'center'
                            },
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }

            // 5. Match Status
            const matchCanvas = document.getElementById('matchStatusChart');
            if (matchCanvas) {
                new Chart(matchCanvas, {
                    type: 'pie',
                    data: {
                        labels: data.matchStatus.labels,
                        datasets: [{
                            data: data.matchStatus.datasets,
                            backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                display: true,
                                color: '#fff',
                                font: { weight: 'bold' },
                                anchor: 'center',
                                align: 'center'
                            },
                            legend: { position: 'right' }
                        }
                    }
                });
            }

            // 6. Delivery Status
            const deliveryCanvas = document.getElementById('deliveryStatusChart');
            if (deliveryCanvas) {
                new Chart(deliveryCanvas, {
                    type: 'bar',
                    data: {
                        labels: data.deliveryStatus.labels,
                        datasets: [{
                            label: 'Orders',
                            data: data.deliveryStatus.datasets,
                            backgroundColor: [
                                '#3b82f6', // Blue
                                '#10b981', // Green
                                '#f59e0b', // Amber
                                '#ef4444', // Red
                                '#8b5cf6'  // Purple
                            ],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                display: true,
                                anchor: 'end',
                                align: 'top',
                                color: '#6b7280',
                                font: { weight: 'bold' }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 7. Supply vs Demand
            const supplyDemandCanvas = document.getElementById('supplyDemandChart');
            if (supplyDemandCanvas) {
                new Chart(supplyDemandCanvas, {
                    type: 'bar',
                    data: {
                        labels: data.supplyDemand.labels,
                        datasets: [
                            {
                                label: 'Your Supply',
                                data: data.supplyDemand.supply,
                                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                                borderRadius: 4
                            },
                            {
                                label: 'Market Demand',
                                data: data.supplyDemand.demand,
                                backgroundColor: 'rgba(249, 115, 22, 0.7)',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                display: true,
                                anchor: 'end',
                                align: 'top',
                                color: '#6b7280',
                                font: { size: 10, weight: 'bold' }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
@endsection