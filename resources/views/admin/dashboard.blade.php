@extends('layouts.admin_page')
@vite('resources/css/app.css')
@section('content')
    <div class=" rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $totalUsers }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Farmers</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $totalFarmers }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Buyers</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $totalBuyers }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Products</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $totalProducts }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Demands</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $totalDemands }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Matches</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $totalMatches }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-pink-100 text-pink-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Transactions</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $totalTransactions }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-teal-100 text-teal-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending Notifications</p>
                                <p class="text-2xl font-semibold text-gray-800">
                                    {{ $pendingNotifications }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Sales Trends Chart -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Sales Trends</h2>
                        <div class="h-48">
                            <canvas id="salesTrendsChart"></canvas>
                        </div>
                    </div>

                    <!-- Product Popularity Chart -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Product Popularity</h2>
                        <div class="h-48">
                            <canvas id="productPopularityChart"></canvas>
                        </div>
                    </div>

                    <!-- Regional Demand Chart -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Regional Demand</h2>
                        <div class="h-48">
                            <canvas id="regionalDemandChart"></canvas>
                        </div>
                    </div>

                    <!-- Match Status Chart -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Match Status Distribution</h2>
                        <div class="h-48">
                            <canvas id="matchStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- Order Status Chart -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Active Orders by Status</h2>
                        <div class="h-48">
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

        // Parse the data from PHP to JavaScript
        const salesTrendsData = [
            @foreach ($salesTrends as $trend)
                {
                    date: '{{ $trend->date }}',
                    total: {{ $trend->total ?? 0 }}
                },
            @endforeach
        ];
        const productPopularityData = [
            @foreach ($productPopularity as $product)
                {
                    name: '{{ $product->product_name }}',
                    count: {{ $product->transaction_count }}
                },
            @endforeach
        ];
        const regionalDemandData = [
            @foreach ($regionalDemand as $demand)
                {
                    location: '{{ $demand->location }}',
                    count: {{ $demand->demand_count }}
                },
            @endforeach
        ];
        const matchStatusData = [
            @foreach ($matchStatusDistribution as $status)
                {
                    status: '{{ $status->status }}',
                    count: {{ $status->count }}
                },
            @endforeach
        ];
        const orderStatusData = [
            @foreach ($orderStatusDistribution as $status)
                {
                    status: '{{ $status->delivery_status }}',
                    count: {{ $status->count }}
                },
            @endforeach
        ];

        // Calculate total for percentage calculations
        const regionalDemandTotal = regionalDemandData.reduce((sum, item) => sum + item.count, 0);
        const matchStatusTotal = matchStatusData.reduce((sum, item) => sum + item.count, 0);

        // Sales Trends Chart
        const salesTrendsCtx = document.getElementById('salesTrendsChart').getContext('2d');
        const salesTrendsChart = new Chart(salesTrendsCtx, {
            type: 'line',
            data: {
                labels: salesTrendsData.map(item => item.date),
                datasets: [{
                    label: 'Revenue',
                    data: salesTrendsData.map(item => item.total),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    datalabels: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Amount (₹)'
                        }
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 5
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });

        // Product Popularity Chart
        const productPopularityCtx = document.getElementById('productPopularityChart').getContext('2d');
        const productPopularityChart = new Chart(productPopularityCtx, {
            type: 'bar',
            data: {
                labels: productPopularityData.map(item => item.name),
                datasets: [{
                    label: 'Transaction Count',
                    data: productPopularityData.map(item => item.count),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: (value) => value,
                        font: {
                            weight: 'bold',
                            size: 10
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Number of Transactions'
                        }
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 5
                        },
                        title: {
                            display: true,
                            text: 'Product'
                        }
                    }
                }
            }
        });

        // Regional Demand Chart
        const regionalDemandCtx = document.getElementById('regionalDemandChart').getContext('2d');
        const regionalDemandChart = new Chart(regionalDemandCtx, {
            type: 'pie',
            data: {
                labels: regionalDemandData.map(item => item.location),
                datasets: [{
                    label: 'Demand Count',
                    data: regionalDemandData.map(item => item.count),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            padding: 10,
                            font: {
                                size: 10
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value * 100 / sum).toFixed(1) + "%";
                            return percentage;
                        },
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = Math.round((value / regionalDemandTotal) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Match Status Chart
        const matchStatusCtx = document.getElementById('matchStatusChart').getContext('2d');
        const matchStatusChart = new Chart(matchStatusCtx, {
            type: 'doughnut',
            data: {
                labels: matchStatusData.map(item => item.status),
                datasets: [{
                    label: 'Match Count',
                    data: matchStatusData.map(item => item.count),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            padding: 10,
                            font: {
                                size: 10
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value * 100 / sum).toFixed(1) + "%";
                            return percentage;
                        },
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = Math.round((value / matchStatusTotal) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Order Status Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'bar',
            data: {
                labels: orderStatusData.map(item => item.status),
                datasets: [{
                    label: 'Order Count',
                    data: orderStatusData.map(item => item.count),
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: (value) => value,
                        font: {
                            weight: 'bold',
                            size: 10
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 10
                        },
                        title: {
                            display: true,
                            text: 'Order Status'
                        }
                    }
                }
            }
        });
    </script>
@endsection
