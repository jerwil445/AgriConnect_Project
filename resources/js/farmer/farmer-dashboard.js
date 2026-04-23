document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') return;

    const data = window.farmerDashboardData || {};

    // Helper: Create Gradient
    const createGradient = (ctx, colorStart, colorEnd) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    };

    // 1. Revenue Chart
    const revenueCanvas = document.getElementById('farmerRevenueChart');
    if (revenueCanvas) {
        const ctx = revenueCanvas.getContext('2d');
        const gradient = createGradient(ctx, 'rgba(34, 197, 94, 0.4)', 'rgba(34, 197, 94, 0)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.revenueTrends?.labels || [],
                datasets: [{
                    label: 'Revenue (₱)',
                    data: data.revenueTrends?.datasets || [],
                    borderColor: '#22c55e',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Product Sales Chart
    const productSalesCanvas = document.getElementById('farmerProductSalesChart');
    if (productSalesCanvas) {
        const ctx = productSalesCanvas.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.productSales?.labels || [],
                datasets: [{
                    data: data.productSales?.datasets || [],
                    backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });
    }

    // 3. Regional Demand Chart
    const regionalDemandCanvas = document.getElementById('farmerRegionalDemandChart');
    if (regionalDemandCanvas) {
        const ctx = regionalDemandCanvas.getContext('2d');
        const gradient = createGradient(ctx, 'rgba(59, 130, 246, 0.8)', 'rgba(59, 130, 246, 0.2)');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.regionalDemand?.labels || [],
                datasets: [{
                    label: 'Demand Signaling (Count)',
                    data: data.regionalDemand?.datasets || [],
                    backgroundColor: gradient,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
