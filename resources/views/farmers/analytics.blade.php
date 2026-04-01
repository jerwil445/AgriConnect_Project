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

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart global defaults
            Chart.defaults.font.family = "'Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif";
            Chart.defaults.color = '#6b7280'; // gray-500

            // 1. Revenue Line Chart
            const revenueCanvas = document.getElementById('revenueChart');
            if (revenueCanvas) {
                const rtx = revenueCanvas.getContext('2d');

                // Create gradient for the line chart fill
                const gradient = rtx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(22, 163, 74, 0.2)'); // Green-600 at 20%
                gradient.addColorStop(1, 'rgba(22, 163, 74, 0)');   // Transparent

                new Chart(rtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendLabels) !!},
                        datasets: [{
                            label: 'Revenue (₱)',
                            data: {!! json_encode($trendValues) !!},
                            borderColor: '#16a34a', // green-600
                            backgroundColor: gradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#16a34a',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#16a34a',
                            pointHoverBorderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { display: true, position: 'top' },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)', // gray-900
                                titleFont: { size: 13, weight: '600' },
                                bodyFont: { size: 14, weight: 'bold' },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function (context) {
                                        return '₱' + context.parsed.y.toLocaleString(undefined, { minimumFractionDigits: 2 });
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: { display: true, text: 'Date', font: { weight: 'bold' } },
                                grid: { display: false },
                                ticks: { maxTicksLimit: 10, align: 'inner' }
                            },
                            y: {
                                title: { display: true, text: 'Revenue (₱)', font: { weight: 'bold' } },
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: '#f3f4f6', borderDash: [5, 5] },
                                ticks: {
                                    maxTicksLimit: 6,
                                    callback: function (value) {
                                        if (value >= 1000) {
                                            return '₱' + (value / 1000).toFixed(value % 1000 !== 0 ? 1 : 0) + 'k';
                                        }
                                        return '₱' + value;
                                    }
                                }
                            }
                        }
                    } // Closed the options object
                });
            }

            // 2. Top Products Bar Chart
            const productsCanvas = document.getElementById('productsChart');
            if (productsCanvas) {
                const ptx = productsCanvas.getContext('2d');
                new Chart(ptx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($productLabels) !!},
                        datasets: [{
                            label: 'Total Generated Revenue (₱)',
                            data: {!! json_encode($productRevenues) !!},
                            backgroundColor: [
                                '#4ade80', // green-400
                                '#22c55e', // green-500
                                '#16a34a', // green-600
                                '#15803d', // green-700
                                '#166534'  // green-800
                            ],
                            hoverBackgroundColor: [
                                '#22c55e', // green-500
                                '#16a34a', // green-600
                                '#15803d', // green-700
                                '#166534', // green-800
                                '#14532d'  // green-900
                            ],
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top' },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                displayColors: false,
                                callbacks: {
                                    label: function (context) {
                                        return '₱' + context.parsed.y.toLocaleString(undefined, { minimumFractionDigits: 2 });
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: { display: true, text: 'Products', font: { weight: 'bold' } },
                                grid: { display: false }
                            },
                            y: {
                                title: { display: true, text: 'Revenue (₱)', font: { weight: 'bold' } },
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: '#f3f4f6', borderDash: [5, 5] },
                                ticks: {
                                    maxTicksLimit: 5,
                                    callback: function (value) {
                                        return '₱' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 3. Order Rate Doughnut Chart
            const orderRateCanvas = document.getElementById('orderRateChart');
            if (orderRateCanvas) {
                // Register datalabels plugin only for this chart
                Chart.register(ChartDataLabels);

                // Custom plugin: draw total in the center hole
                const centerTextPlugin = {
                    id: 'centerText',
                    beforeDraw(chart) {
                        const { width, height, ctx } = chart;
                        ctx.save();
                        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        // Center coordinates
                        const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                        const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                        // Draw "Total" label
                        ctx.font = '600 12px Inter, system-ui, sans-serif';
                        ctx.fillStyle = '#6b7280';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText('Total Orders', centerX, centerY - 14);
                        // Draw total number
                        ctx.font = 'bold 28px Inter, system-ui, sans-serif';
                        ctx.fillStyle = '#111827';
                        ctx.fillText(total, centerX, centerY + 14);
                        ctx.restore();
                    }
                };

                const otx = orderRateCanvas.getContext('2d');
                new Chart(otx, {
                    type: 'doughnut',
                    plugins: [centerTextPlugin],
                    data: {
                        labels: ['Completed', 'Cancelled', 'Rejected'],
                        datasets: [{
                            data: {!! json_encode($orderRateData) !!},
                            backgroundColor: [
                                '#16a34a', // green-600
                                '#f97316', // orange-500
                                '#ef4444'  // red-500
                            ],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'right',
                                labels: {
                                    padding: 24,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 12, weight: '500' }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                        return ` ${context.parsed} Orders (${pct}%)`;
                                    }
                                }
                            },
                            datalabels: {
                                display: function (context) {
                                    return context.dataset.data[context.dataIndex] > 0;
                                },
                                color: '#ffffff',
                                font: { weight: 'bold', size: 13 },
                                formatter: function (value, context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return value + '\n(' + pct + '%)';
                                },
                                anchor: 'center',
                                align: 'center',
                                textAlign: 'center'
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection