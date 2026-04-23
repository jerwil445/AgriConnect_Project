document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') return;

    const data = window.buyerDashboardData || {};

    // 1. Spending Trend Chart
    const spendingCanvas = document.getElementById('buyerSpendingChart');
    if (spendingCanvas) {
        const ctx = spendingCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.spendingTrends?.labels || [],
                datasets: [{
                    label: 'Spent (₱)',
                    data: data.spendingTrends?.datasets || [],
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
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

    // 2. Category Distribution Chart
    const categoryCanvas = document.getElementById('buyerCategoryChart');
    if (categoryCanvas) {
        const ctx = categoryCanvas.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.categoryAllocation?.labels || [],
                datasets: [{
                    data: data.categoryAllocation?.datasets || [],
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
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 10, font: { size: 10 } } }
                }
            }
        });
    }
});
