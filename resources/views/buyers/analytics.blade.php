@extends('layouts.buyers_page')

@section('title', 'Sourcing Analytics • AgriConnect')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl animate-fade-in">
    {{-- Header Section --}}
    <div class="mb-10 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-blue-600 font-black text-xs uppercase tracking-[0.2em]">
                <i class="fas fa-chart-line"></i> Performance Hub
            </div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Purchase Intelligence</h1>
            <p class="text-gray-500 font-medium">Monitoring capital allocation and sourcing efficiency across your network.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white/70 backdrop-blur-md border border-gray-100 px-5 py-3 rounded-2xl shadow-sm flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-xs font-black text-gray-600 uppercase tracking-widest">Live Sourcing Data</span>
            </div>
            <button onclick="window.print()" class="p-3.5 rounded-2xl bg-gray-900 text-white hover:bg-gray-800 transition-all shadow-lg active:scale-95">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>

    {{-- Performance Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Stat Card: Total Expenditure --}}
        <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full blur-2xl group-hover:bg-blue-100 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-wallet text-lg"></i>
                </div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Capital Deployed</div>
                <div class="text-3xl font-black text-gray-900 tracking-tighter">₱{{ number_format($totalSpent, 2) }}</div>
                <div class="mt-4 flex items-center gap-2">
                    <div class="flex items-center gap-1 {{ $spendingTrend >= 0 ? 'text-green-600' : 'text-red-500' }} text-xs font-black">
                        <i class="fas fa-arrow-{{ $spendingTrend >= 0 ? 'up' : 'down' }}"></i>
                        {{ abs(round($spendingTrend, 1)) }}%
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter text-nowrap">vs last month</span>
                </div>
            </div>
        </div>

        {{-- Stat Card: Fulfillment Rate --}}
        <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
             <div class="relative z-10">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-bullseye text-lg"></i>
                </div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Fulfillment Metric</div>
                <div class="text-3xl font-black text-gray-900 tracking-tighter">{{ $fulfillmentRate }}%</div>
                <div class="mt-4 w-full bg-gray-50 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full transition-all duration-1000" style="width: {{ $fulfillmentRate }}%"></div>
                </div>
            </div>
        </div>

        {{-- Stat Card: Demand Health --}}
        <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
             <div class="relative z-10">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-satellite-dish text-lg"></i>
                </div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Active Demands</div>
                <div class="text-3xl font-black text-gray-900 tracking-tighter">{{ $activeDemandsCount }}</div>
                <div class="mt-4 text-[10px] font-bold text-amber-600 uppercase tracking-widest">
                    Live Signaling on Market
                </div>
            </div>
        </div>

        {{-- Stat Card: Network Scale --}}
        <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
             <div class="relative z-10">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-handshake text-lg"></i>
                </div>
                <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Partner Network</div>
                <div class="text-3xl font-black text-gray-900 tracking-tighter">{{ $topSuppliers->count() }}</div>
                <div class="mt-4 text-[10px] font-bold text-purple-500 uppercase tracking-widest">
                    Verified Direct Farmers
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        {{-- Capital Trend Chart --}}
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-area text-xs"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Sourcing Volume Trend</h3>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Capital</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Volume (kg)</span>
                    </div>
                </div>
            </div>
            <div class="h-[350px]">
                <canvas id="sourcingTrendChart"></canvas>
            </div>
        </div>

        {{-- Sourcing Split Chart --}}
        <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm flex flex-col">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-xs"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Category Allocation</h3>
            </div>
            <div class="flex-1 flex flex-col items-center justify-center relative">
                <div class="h-[250px] w-full">
                    <canvas id="categorySplitChart"></canvas>
                </div>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none -mt-4">
                    <div class="text-2xl font-black text-gray-900">₱{{ number_format($totalSpent / 1000, 1) }}k</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Spent</div>
                </div>
            </div>
            <div class="mt-8 space-y-3">
                @foreach($productStats as $stat)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full" style="background-color: {{ ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#64748b'][$loop->index] ?? '#cbd5e1' }}"></div>
                            <span class="text-xs font-bold text-gray-600">{{ $stat->product_name }}</span>
                        </div>
                        <span class="text-xs font-black text-gray-900">₱{{ number_format($stat->total_spent, 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Market Intelligence Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        {{-- Regional Demand --}}
        <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-globe-asia text-xs"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Regional Demand Signaling</h3>
            </div>
            <div class="h-[300px]">
                <canvas id="regionalDemandChart"></canvas>
            </div>
        </div>

        {{-- Match Status --}}
        <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-handshake text-xs"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Market Match Efficiency</h3>
            </div>
            <div class="h-[300px]">
                <canvas id="matchStatusChart"></canvas>
            </div>
        </div>

        {{-- Order Status Bar --}}
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-truck-loading text-xs"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Procurement Status Distribution</h3>
            </div>
            <div class="h-[300px]">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>


    {{-- Supplier Performance Table --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-10">
        <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">Strategic Supplier Network</h3>
                <p class="text-sm font-medium text-gray-500">Farmers prioritized by order volume and reliability.</p>
            </div>
            <a href="{{ route('buyer.orders') }}" class="px-8 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-100 transition-all">
                Export Ledger
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Sourcing Partner</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Order Density</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Financial Inflow</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Trust Maturity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($topSuppliers as $index => $supplier)
                        <tr class="hover:bg-blue-50/30 transition-all group">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl object-cover bg-gray-100 border border-gray-100 flex items-center justify-center font-black text-gray-400 group-hover:scale-110 transition-transform duration-500 overflow-hidden">
                                        {{ substr($supplier->first_name, 0, 1) }}{{ substr($supplier->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 group-hover:text-blue-600 transition-colors">{{ $supplier->first_name }} {{ $supplier->last_name }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 tracking-widest">ID: AGR-{{ str_pad($supplier->farmer_id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <div class="space-y-1">
                                    <div class="text-sm font-black text-gray-900">{{ $supplier->transaction_count }} Orders</div>
                                    <div class="flex gap-1">
                                        @for($i = 0; $i < min(5, $supplier->transaction_count); $i++)
                                            <div class="w-2 h-1 bg-blue-500 rounded-full"></div>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <div class="text-lg font-black text-gray-900 tracking-tight">₱{{ number_format($supplier->total_spent, 2) }}</div>
                                <div class="text-[10px] font-bold text-emerald-500 uppercase">Settled Payment</div>
                            </td>
                            <td class="px-10 py-6">
                                @php
                                    $badges = [
                                        0 => ['label' => 'Tier 1 Partner', 'class' => 'bg-blue-50 text-blue-600 border-blue-100'],
                                        1 => ['label' => 'Strategic Grow', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                        'default' => ['label' => 'Rising Producer', 'class' => 'bg-gray-50 text-gray-500 border-gray-200']
                                    ];
                                    $badge = $badges[$index] ?? $badges['default'];
                                @endphp
                                <span class="px-3 py-1.5 rounded-xl border {{ $badge['class'] }} text-[10px] font-black uppercase tracking-widest">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-10 py-20 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-200">
                                    <i class="fas fa-ghost text-3xl"></i>
                                </div>
                                <h4 class="font-black text-gray-900">No active supplier network yet</h4>
                                <p class="text-xs text-gray-400 font-medium max-w-xs mx-auto mt-1">Start accepting crops from farmers to build your procurement history.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js and Initialization --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.buyerAnalyticsData = {
            monthlySpending: {
                labels: @json($monthlySpending->pluck('month')),
                spending: @json($monthlySpending->pluck('total')),
                volume: @json($monthlySpending->pluck('volume'))
            },
            productStats: {
                labels: @json($productStats->pluck('product_name')),
                spent: @json($productStats->pluck('total_spent'))
            },
            regionalDemand: {
                labels: {!! json_encode($regionalDemand->pluck('province')) !!},
                datasets: {!! json_encode($regionalDemand->pluck('demand_count')) !!}
            },
            matchStatus: {
                labels: {!! json_encode($matchStatusDistribution->pluck('status')) !!},
                datasets: {!! json_encode($matchStatusDistribution->pluck('count')) !!}
            },
            orderStatus: {
                labels: {!! json_encode($orderStatusDistribution->pluck('status')) !!},
                datasets: {!! json_encode($orderStatusDistribution->pluck('count')) !!}
            }
        };
    </script>
    @vite('resources/js/buyer/buyer-analytics.js')

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
