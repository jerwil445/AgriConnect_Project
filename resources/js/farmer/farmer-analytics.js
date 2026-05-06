/**
 * Farmer Analytics JS - v1.0.2
 */
document.addEventListener('DOMContentLoaded', function () {
    // Check if analytics data is available
    if (!window.farmerAnalyticsData) return;

    const data = window.farmerAnalyticsData;

    // Chart global defaults
    Chart.defaults.font.family = "'Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.color = '#6b7280'; // gray-500


    // 2. Top Products Bar Chart
    const productsCanvas = document.getElementById('productsChart');
    if (productsCanvas) {
        const ptx = productsCanvas.getContext('2d');
        new Chart(ptx, {
            type: 'bar',
            data: {
                labels: data.productLabels,
                datasets: [{
                    label: 'Total Generated Revenue (₱)',
                    data: data.productRevenues,
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
                    datalabels: { display: false },
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
                    data: data.orderRateData,
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

    // 4. Regional Demand Chart
    const regionalCanvas = document.getElementById('regionalDemandChart');
    if (regionalCanvas) {
        new Chart(regionalCanvas, {
            type: 'doughnut',
            data: {
                labels: data.regionalDemand.labels,
                datasets: [{
                    data: data.regionalDemand.datasets,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    datalabels: { display: false },
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });
    }

    // 5. Match Status Chart
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
                    datalabels: { display: false },
                    legend: { position: 'right' }
                }
            }
        });
    }

    // 6. Delivery Status Chart
    const deliveryCanvas = document.getElementById('deliveryStatusChart');
    if (deliveryCanvas) {
        new Chart(deliveryCanvas, {
            type: 'bar',
            data: {
                labels: data.deliveryStatus.labels,
                datasets: [{
                    label: 'Transactions',
                    data: data.deliveryStatus.datasets,
                    backgroundColor: 'rgba(139, 92, 246, 0.7)',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    datalabels: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 7. Supply vs Demand Chart
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
                    datalabels: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});

