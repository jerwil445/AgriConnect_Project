@extends('layouts.farmers_page')

@section('title', 'Earnings & Payouts')

@section('content')
<div class="md:ml-64 p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Earnings & Payouts</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Track your income and payout history</p>
    </div>

    <!-- Earnings Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Total Earnings -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 sm:p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <span class="text-blue-100 text-sm font-medium">Total Earnings</span>
                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-3xl font-bold">₱{{ number_format($totalEarnings, 2) }}</div>
            <div class="text-blue-100 text-sm mt-2">All time earnings</div>
        </div>

        <!-- Paid Earnings -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 sm:p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <span class="text-green-100 text-sm font-medium">Paid Out</span>
                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-3xl font-bold">₱{{ number_format($paidEarnings, 2) }}</div>
            <div class="text-green-100 text-sm mt-2">Received payments</div>
        </div>

        <!-- Unpaid Earnings -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 sm:p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <span class="text-amber-100 text-sm font-medium">Pending Payout</span>
                <svg class="w-8 h-8 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-3xl font-bold">₱{{ number_format($unpaidEarnings, 2) }}</div>
            <div class="text-amber-100 text-sm mt-2">Awaiting payment</div>
        </div>

        <!-- Processing -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 sm:p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <span class="text-purple-100 text-sm font-medium">Processing</span>
                <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div class="text-3xl font-bold">₱{{ number_format($pendingEarnings, 2) }}</div>
            <div class="text-purple-100 text-sm mt-2">Being processed</div>
        </div>
    </div>

    <!-- Monthly Earnings Chart -->
    @if($monthlyEarnings->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mb-8">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Monthly Earnings Trend</h2>
        <div class="h-64">
            <canvas id="earningsChart"></canvas>
        </div>
    </div>
    @endif

    <!-- Earnings History Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900">Earnings History</h2>
        </div>

        @if($earnings->count() > 0)
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gross Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Platform Fee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payout Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($earnings as $earning)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $earning->earning_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="font-medium text-gray-900">#{{ $earning->transaction_id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₱{{ number_format($earning->gross_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                            -₱{{ number_format($earning->platform_fee, 2) }}
                            <span class="text-gray-500 text-xs">({{ $earning->platform_fee_percentage }}%)</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            ₱{{ number_format($earning->net_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($earning->payout_status === 'paid')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            @elseif($earning->payout_status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Processing
                                </span>
                            @elseif($earning->payout_status === 'on_hold')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    On Hold
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                    Unpaid
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $earning->payout_date ? $earning->payout_date->format('M d, Y') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 sm:p-6 border-t border-gray-200">
            {{ $earnings->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Earnings Yet</h3>
            <p class="text-gray-500">Your earnings will appear here once you complete orders.</p>
        </div>
        @endif
    </div>
</div>

@if($monthlyEarnings->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const monthlyData = @json($monthlyEarnings);
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const labels = monthlyData.map(item => months[item.month - 1] + ' ' + item.year);
    const earnings = monthlyData.map(item => parseFloat(item.total));
    const fees = monthlyData.map(item => parseFloat(item.fees));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.reverse(),
            datasets: [{
                label: 'Net Earnings',
                data: earnings.reverse(),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Platform Fees',
                data: fees.reverse(),
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
