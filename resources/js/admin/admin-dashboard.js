document.addEventListener('DOMContentLoaded', () => {
    // Register the data labels plugin if available (assuming it was loaded via script tag)
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    if (typeof Chart === 'undefined') return;

    // Global Chart Defaults
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(255, 255, 255, 0.98)';
    Chart.defaults.plugins.tooltip.titleColor = '#111827';
    Chart.defaults.plugins.tooltip.bodyColor = '#4b5563';
    Chart.defaults.plugins.tooltip.borderColor = '#e2e8f0';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 12;
    Chart.defaults.plugins.tooltip.displayColors = true;
    Chart.defaults.plugins.tooltip.usePointStyle = true;
    Chart.defaults.plugins.tooltip.boxPadding = 6;

    const data = window.adminDashboardData || {};

    // Helper: Create Gradients
    const createGradient = (ctx, colorStart, colorEnd) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    };

    // 1. Sales Trends
    const salesTrendsCanvas = document.getElementById('salesTrendsChart');
    if (salesTrendsCanvas) {
        const ctx = salesTrendsCanvas.getContext('2d');
        const gradient = createGradient(ctx, 'rgba(75, 192, 192, 0.5)', 'rgba(75, 192, 192, 0)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.salesTrends?.labels || [],
                datasets: [{
                    label: 'Revenue',
                    data: data.salesTrends?.datasets || [],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgb(75, 192, 192)',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    intersect: true,
                },
                plugins: {
                    legend: { display: false },
                    datalabels: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(context.parsed.y);
                                return 'Revenue: ' + value;
                            }
                        }
                    }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Product Popularity
    const productPopularityCanvas = document.getElementById('productPopularityChart');
    if (productPopularityCanvas) {
        const ctx = productPopularityCanvas.getContext('2d');
        const gradient = createGradient(ctx, 'rgba(54, 162, 235, 0.8)', 'rgba(54, 162, 235, 0.2)');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.productPopularity?.labels || [],
                datasets: [{
                    label: 'Transactions',
                    data: data.productPopularity?.datasets || [],
                    backgroundColor: gradient,
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: { anchor: 'end', align: 'top', color: 'rgba(54, 162, 235, 1)', font: { weight: 'bold' } }
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
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
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
                    datalabels: { display: false }
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
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ],
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
                    datalabels: { display: false }
                }
            }
        });
    }

    // 5. Active Orders
    const orderStatusCanvas = document.getElementById('orderStatusChart');
    if (orderStatusCanvas) {
        const ctx = orderStatusCanvas.getContext('2d');
        const gradient = createGradient(ctx, 'rgba(153, 102, 255, 0.8)', 'rgba(153, 102, 255, 0.2)');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.orderStatusDistribution?.labels || [],
                datasets: [{
                    label: 'Orders',
                    data: data.orderStatusDistribution?.datasets || [],
                    backgroundColor: gradient,
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: { anchor: 'end', align: 'top', color: 'rgba(153, 102, 255, 1)', font: { weight: 'bold' } }
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
        const ctx = topFarmersCanvas.getContext('2d');
        const gradient = createGradient(ctx, 'rgba(34, 197, 94, 0.8)', 'rgba(34, 197, 94, 0.2)');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.topFarmers?.labels || [],
                datasets: [{
                    label: 'Sales (₱)',
                    data: data.topFarmers?.datasets || [],
                    backgroundColor: gradient,
                    borderRadius: 8,
                    maxBarThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: { anchor: 'end', align: 'top', color: 'rgba(34, 197, 94, 1)', font: { weight: 'bold' }, formatter: (value) => '₱' + Number(value).toLocaleString() }
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
        const ctx = topBuyersCanvas.getContext('2d');
        const gradient = createGradient(ctx, 'rgba(59, 130, 246, 0.8)', 'rgba(59, 130, 246, 0.2)');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.topBuyers?.labels || [],
                datasets: [{
                    label: 'Spent (₱)',
                    data: data.topBuyers?.datasets || [],
                    backgroundColor: gradient,
                    borderRadius: 8,
                    maxBarThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    datalabels: { anchor: 'end', align: 'top', color: 'rgba(59, 130, 246, 1)', font: { weight: 'bold' }, formatter: (value) => '₱' + Number(value).toLocaleString() }
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
                        label: 'Supply (Qty)',
                        data: data.supplyDemandData?.supply || [],
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Demand (Qty)',
                        data: data.supplyDemandData?.demand || [],
                        backgroundColor: 'rgba(249, 115, 22, 0.7)',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    datalabels: { display: false }
                },
                scales: {
                    y: { grid: { borderDash: [5, 5] }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
