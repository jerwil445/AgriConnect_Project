document.addEventListener('DOMContentLoaded', () => {
    // Register the data labels plugin if available
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    if (typeof Chart === 'undefined') return;

    // Global Chart Defaults
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    const data = window.adminDashboardData || {};

    // 1. Sales Trends
    const salesTrendsCanvas = document.getElementById('salesTrendsChart');
    if (salesTrendsCanvas) {
        const ctx = salesTrendsCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(22, 163, 74, 0.2)');
        gradient.addColorStop(1, 'rgba(22, 163, 74, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.salesTrends?.labels || [],
                datasets: [{
                    label: 'Revenue',
                    data: data.salesTrends?.datasets || [],
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
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        display: false
                    }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { 
                        grid: { display: false },
                        ticks: {
                            autoSkip: false,
                            source: 'labels',
                            maxTicksLimit: 100,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // 2. Product Popularity
    const productPopularityCanvas = document.getElementById('productPopularityChart');
    if (productPopularityCanvas) {
        new Chart(productPopularityCanvas, {
            type: 'bar',
            data: {
                labels: data.productPopularity?.labels || [],
                datasets: [{
                    label: 'Transactions',
                    data: data.productPopularity?.datasets || [],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'top',
                        color: '#64748b',
                        font: { weight: 'bold' }
                    }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 3. Regional Demand (Doughnut)
    const regionalDemandCanvas = document.getElementById('regionalDemandChart');
    if (regionalDemandCanvas) {
        new Chart(regionalDemandCanvas, {
            type: 'doughnut',
            data: {
                labels: data.regionalDemand?.labels || [],
                datasets: [{
                    data: data.regionalDemand?.datasets || [],
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
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: { weight: 'bold' }
                    }
                }
            }
        });
    }

    // 4. Match Status
    const matchStatusCanvas = document.getElementById('matchStatusChart');
    if (matchStatusCanvas) {
        new Chart(matchStatusCanvas, {
            type: 'doughnut',
            data: {
                labels: data.matchStatusDistribution?.labels || [],
                datasets: [{
                    data: data.matchStatusDistribution?.datasets || [],
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: { weight: 'bold' }
                    }
                }
            }
        });
    }

    // 5. Active Orders
    const orderStatusCanvas = document.getElementById('orderStatusChart');
    if (orderStatusCanvas) {
        new Chart(orderStatusCanvas, {
            type: 'bar',
            data: {
                labels: data.orderStatusDistribution?.labels || [],
                datasets: [{
                    label: 'Orders',
                    data: data.orderStatusDistribution?.datasets || [],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'top',
                        color: '#64748b',
                        font: { weight: 'bold' }
                    }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 6. Top Farmers
    const topFarmersCanvas = document.getElementById('topFarmersChart');
    if (topFarmersCanvas) {
        new Chart(topFarmersCanvas, {
            type: 'bar',
            data: {
                labels: data.topFarmers?.labels || [],
                datasets: [{
                    label: 'Sales (₱)',
                    data: data.topFarmers?.datasets || [],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderRadius: 8,
                    maxBarThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'top',
                        color: '#10b981',
                        font: { weight: 'bold' },
                        formatter: (value) => '₱' + Number(value).toLocaleString()
                    }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { grid: { display: false }, ticks: { maxRotation: 45, minRotation: 45 } }
                }
            }
        });
    }

    // 7. Top Buyers
    const topBuyersCanvas = document.getElementById('topBuyersChart');
    if (topBuyersCanvas) {
        new Chart(topBuyersCanvas, {
            type: 'bar',
            data: {
                labels: data.topBuyers?.labels || [],
                datasets: [{
                    label: 'Spent (₱)',
                    data: data.topBuyers?.datasets || [],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderRadius: 8,
                    maxBarThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'top',
                        color: '#3b82f6',
                        font: { weight: 'bold' },
                        formatter: (value) => '₱' + Number(value).toLocaleString()
                    }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { grid: { display: false }, ticks: { maxRotation: 45, minRotation: 45 } }
                }
            }
        });
    }

    // 8. Supply vs Demand
    const supplyDemandCanvas = document.getElementById('supplyDemandChart');
    if (supplyDemandCanvas) {
        new Chart(supplyDemandCanvas, {
            type: 'bar',
            data: {
                labels: data.supplyDemandData?.labels || [],
                datasets: [
                    {
                        label: 'Supply',
                        data: data.supplyDemandData?.supply || [],
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                    },
                    {
                        label: 'Demand',
                        data: data.supplyDemandData?.demand || [],
                        backgroundColor: '#f97316',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'top',
                        color: '#64748b',
                        font: { weight: 'bold', size: 10 }
                    }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
