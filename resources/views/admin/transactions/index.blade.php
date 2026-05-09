@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 pb-6 border-b border-gray-100 gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 text-2xl">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Financial Ledger</h2>
                        <p class="text-sm text-gray-500 font-medium mt-1">Audit platform transactions and logistics state</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="hidden lg:flex items-center gap-6 px-6 py-2.5 bg-white/50 backdrop-blur-md rounded-2xl border border-white shadow-sm mr-4">
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total Value Flow</p>
                            <p class="text-lg font-black text-emerald-600 mt-1 uppercase tracking-tighter">Market Validated</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toolbar / Filters -->
            <div class="relative z-[60] bg-white/60 backdrop-blur-lg rounded-[2rem] border border-white shadow-sm p-6 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Per Page Select -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('per-page-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Show:</span> {{ request('per_page', 10) }}
                            <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <div id="per-page-dropdown" class="hidden absolute left-0 mt-2 w-32 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            @foreach([10, 25, 50, 100] as $count)
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => $count, 'page' => 1]) }}" 
                                   class="block px-4 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 transition-colors {{ request('per_page') == $count ? 'bg-blue-50/50 text-blue-700' : '' }}">
                                    {{ $count }} rows
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('payment-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Payment:</span> {{ request('payment_status') ? ucfirst(request('payment_status')) : 'All' }}
                            <i class="fas fa-money-bill-wave text-[10px] text-gray-400"></i>
                        </button>
                        <div id="payment-filter-dropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <a href="{{ request()->fullUrlWithoutQuery(['payment_status', 'page']) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50">All Statuses</a>
                            @foreach($paymentStatuses as $status)
                                <a href="{{ request()->fullUrlWithQuery(['payment_status' => $status, 'page' => 1]) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50">
                                    <span class="w-2 h-2 rounded-full inline-block mr-2 @if($status=='Paid') bg-emerald-500 @elseif($status=='Pending') bg-amber-500 @else bg-red-500 @endif"></span>
                                    {{ ucfirst($status) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Delivery Status -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('delivery-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Delivery:</span> {{ request('delivery_status') ? ucfirst(request('delivery_status')) : 'All' }}
                            <i class="fas fa-truck text-[10px] text-gray-400"></i>
                        </button>
                        <div id="delivery-filter-dropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <a href="{{ request()->fullUrlWithoutQuery(['delivery_status', 'page']) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50">All Statuses</a>
                            @foreach($deliveryStatuses as $status)
                                <a href="{{ request()->fullUrlWithQuery(['delivery_status' => $status, 'page' => 1]) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50">
                                    <span class="w-2 h-2 rounded-full inline-block mr-2 @if($status=='Delivered') bg-emerald-500 @elseif($status=='Scheduled') bg-blue-500 @elseif($status=='In Transit') bg-purple-500 @else bg-red-500 @endif"></span>
                                    {{ ucfirst($status) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <form action="{{ route('admin.transactions.index') }}" method="GET" class="relative lg:w-80 group">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
                    <input type="hidden" name="delivery_status" value="{{ request('delivery_status') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ledger identity..."
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all shadow-sm text-sm font-medium">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="bg-white/40 backdrop-blur-xl border border-white rounded-[2.5rem] shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Settlement ID</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Product Info</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Agreement Stakeholders</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Settlement Value</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Status States</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-white/60 transition-all group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-xs font-black text-gray-300 tracking-widest">#TXN_{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase">{{ $transaction->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-800">{{ $transaction->product->product_name ?? 'N/A' }}</div>
                                        <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mt-1">{{ $transaction->product->category ?? 'General' }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                                <div class="text-[11px] font-black text-gray-800 leading-none">
                                                    {{ $transaction->farmer->first_name ?? 'N/A' }} {{ $transaction->farmer->last_name ?? '' }} (S)
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                                <div class="text-[11px] font-black text-gray-800 leading-none">
                                                    {{ $transaction->buyer->first_name ?? 'N/A' }} {{ $transaction->buyer->last_name ?? '' }} (B)
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-emerald-600">₱{{ number_format($transaction->total_amount ?? 0, 2) }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-tighter">{{ $transaction->final_quantity ?? '0' }} Units Registered</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex flex-col gap-1.5">
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded-full border shadow-sm w-fit
                                                @if($transaction->payment_status == 'Paid') bg-emerald-50 text-emerald-700 border-emerald-100
                                                @elseif($transaction->payment_status == 'Pending') bg-amber-50 text-amber-700 border-amber-100
                                                @elseif($transaction->payment_status == 'Failed') bg-red-50 text-red-700 border-red-100
                                                @else bg-gray-50 text-gray-700 border-gray-100 @endif">
                                                Payment: {{ $transaction->payment_status ?? 'N/A' }}
                                            </span>
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded-full border shadow-sm w-fit
                                                @if($transaction->delivery_status == 'Delivered') bg-blue-50 text-blue-700 border-blue-100
                                                @elseif($transaction->delivery_status == 'Scheduled') bg-indigo-50 text-indigo-700 border-indigo-100
                                                @elseif($transaction->delivery_status == 'In Transit') bg-purple-50 text-purple-700 border-purple-100
                                                @else bg-gray-50 text-gray-700 border-gray-100 @endif">
                                                Logistics: {{ $transaction->delivery_status ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <a href="{{ route('admin.transactions.view', $transaction) }}" class="inline-flex w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 items-center justify-center border border-blue-100 shadow-sm" title="Detailed Audit">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 mb-4 border-2 border-dashed border-gray-100">
                                                <i class="fas fa-receipt text-3xl"></i>
                                            </div>
                                            <h4 class="text-lg font-black text-gray-300 uppercase tracking-widest">No Marketplace Settlements</h4>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">No transaction records have been generated for this filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->hasPages())
                    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>
</div>

@vite('resources/js/admin/transactions/admin-transactions-index.js')
@endsection
