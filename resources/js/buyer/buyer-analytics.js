document.addEventListener('DOMContentLoaded', function() {
    const data = window.buyerAnalyticsData || {};
    
    // 1. Sourcing Trend Chart (Line/Area)
    const trendCanvas = document.getElementById('sourcingTrendChart');
    if (trendCanvas) {
        const trendCtx = trendCanvas.getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: data.monthlySpending?.labels || [],
                datasets: [
                    {
                        label: 'Capital (₱)',
                        data: data.monthlySpending?.spending || [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.05)',
                        borderWidth: 4,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 3,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Volume (kg)',
                        data: data.monthlySpending?.volume || [],
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4,
                        fill: false,
                        pointRadius: 2,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleFont: { family: 'Inter', weight: 'bold', size: 12 },
                        bodyFont: { family: 'Inter', size: 12 },
                        usePointStyle: true,
                    }
                },
                scales: {
                    y: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', weight: 'bold', size: 10 }, color: '#94a3b8' }
                    },
                    y1: {
                        position: 'right',
                        grid: { display: false },
                        ticks: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', weight: 'bold', size: 10 }, color: '#94a3b8' }
                    }
                }
            }
        });
    }

    // 2. Category Allocation Chart (Donut)
    const splitCanvas = document.getElementById('categorySplitChart');
    if (splitCanvas) {
        const splitCtx = splitCanvas.getContext('2d');
        new Chart(splitCtx, {
            type: 'doughnut',
            data: {
                labels: data.productStats?.labels || [],
                datasets: [{
                    data: data.productStats?.spent || [],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#64748b'],
                    borderWidth: 0,
                    cutout: '80%',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleFont: { weight: 'bold' },
                        callbacks: {
                            label: function(item) {
                                return ' ₱' + item.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
