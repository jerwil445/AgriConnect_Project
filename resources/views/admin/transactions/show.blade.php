@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 pb-6 border-b border-gray-100 gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 text-3xl">
                        <i class="fas fa-file-invoice-dollar mt-1"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Transaction Ledger</h2>
                        <div class="flex flex-wrap items-center mt-3 gap-2">
                            <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border
                                @if($transaction->payment_status == 'Paid') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($transaction->payment_status == 'Pending') bg-amber-50 text-amber-700 border-amber-200
                                @elseif($transaction->payment_status == 'Failed') bg-red-50 text-red-700 border-red-200
                                @else bg-gray-50 text-gray-700 border-gray-200 @endif">
                                {{ ucfirst($transaction->payment_status ?? 'N/A') }} Payment
                            </span>
                            <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border
                                @if($transaction->delivery_status == 'Delivered') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($transaction->delivery_status == 'Scheduled') bg-blue-50 text-blue-700 border-blue-200
                                @elseif($transaction->delivery_status == 'In Transit') bg-purple-50 text-purple-700 border-purple-200
                                @elseif($transaction->delivery_status == 'Cancelled') bg-red-50 text-red-700 border-red-200
                                @else bg-gray-50 text-gray-700 border-gray-200 @endif">
                                {{ ucfirst($transaction->delivery_status ?? 'N/A') }}
                            </span>
                            <span class="text-sm text-gray-500 font-medium ml-2">TXN: #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.transactions.index') }}" 
                       class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                        <i class="fas fa-arrow-left mr-2 text-gray-400 group-hover:text-gray-600"></i> Back to Ledger
                    </a>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-20">
                
                <!-- Financials & Timeline -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-700/70 mb-8 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-chart-line"></i> Financial Settlement Overview
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                            <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                                <div class="absolute -right-2 -bottom-2 text-gray-100 text-6xl pointer-events-none group-hover:scale-110 transition-transform"><i class="fas fa-box"></i></div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Final Net Volume</p>
                                <p class="text-2xl font-black text-gray-800 mt-2 relative z-10">{{ $transaction->final_quantity ?? '0' }} <span class="text-xs text-gray-500">Units</span></p>
                            </div>
                            
                            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-3xl p-6 border border-indigo-100/50 shadow-sm relative overflow-hidden group">
                                <div class="absolute -right-2 -bottom-2 text-indigo-100/50 text-6xl pointer-events-none group-hover:scale-110 transition-transform"><i class="fas fa-tag"></i></div>
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Agreed Rate</p>
                                <p class="text-2xl font-black text-indigo-700 mt-2 relative z-10">₱{{ number_format($transaction->final_price ?? 0, 2) }}</p>
                            </div>

                            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-3xl p-6 shadow-lg shadow-green-600/20 relative overflow-hidden group">
                                <div class="absolute -right-2 -bottom-2 text-white/10 text-6xl pointer-events-none group-hover:scale-110 transition-transform"><i class="fas fa-money-bill-wave"></i></div>
                                <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Total Valuation</p>
                                <p class="text-2xl font-black text-white mt-2 relative z-10">₱{{ number_format($transaction->total_amount ?? 0, 2) }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="p-6 bg-gray-50/50 rounded-[2rem] border border-gray-100">
                                <h4 class="text-[10px] font-bold text-gray-500 uppercase mb-5 flex items-center gap-2"><i class="fas fa-history text-blue-500"></i> Interaction Log</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-500 uppercase">Settlement Started</span>
                                        <span class="text-xs font-black text-gray-800">{{ $transaction->created_at->format('M d, Y | H:i') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-500 uppercase">Last Activity Hook</span>
                                        <span class="text-xs font-black text-gray-800">{{ $transaction->updated_at->format('M d, Y | H:i') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-500 uppercase">Duration in Ledger</span>
                                        <span class="text-xs font-black text-blue-600">{{ $transaction->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 bg-gradient-to-br from-indigo-50/30 to-blue-50/30 rounded-[2rem] border border-blue-50">
                                <h4 class="text-[10px] font-bold text-blue-600/70 uppercase mb-5 flex items-center gap-2"><i class="fas fa-link text-blue-500"></i> Associated Components</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-500 uppercase">Product Listing</span>
                                        <div class="text-right">
                                            <p class="text-xs font-black text-indigo-700 leading-none">{{ $transaction->product->product_name ?? 'Archived Entry' }}</p>
                                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mt-1">{{ $transaction->product->category ?? 'General' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-500 uppercase">Market Requirement</span>
                                        <span class="text-xs font-black text-indigo-700 capitalize">{{ $transaction->demand->egg_type ?? 'Private' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logistics Visualization -->
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-8 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-map-marked-alt text-red-400"></i> Logistics Fulfillment Data
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="relative pl-10 border-l border-gray-100 profile-timeline-mockup">
                                <div class="absolute left-[-5px] top-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Origin Point</p>
                                <p class="text-sm font-black text-gray-800">{{ $transaction->farmer->address ?? 'Confirmed Supplier Base' }}</p>
                                <p class="text-[10px] font-bold text-emerald-600/70 mt-1 uppercase tracking-tighter">Verification Complete</p>
                                
                                <div class="mt-8 relative">
                                    <div class="absolute left-[-15px] top-0 w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-blue-100"></div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Destination Hub</p>
                                    <p class="text-sm font-black text-gray-800">{{ $transaction->buyer->address ?? 'Target Drop-off point' }}</p>
                                    <p class="text-[10px] font-bold text-blue-600/70 mt-1 uppercase tracking-tighter">Logistics Verified</p>
                                </div>
                            </div>

                            <div class="bg-gray-50/50 rounded-3xl p-6 border border-gray-100 border-dashed flex flex-col items-center justify-center text-center">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-gray-400 mb-4 shadow-sm">
                                    <i class="fas @if($transaction->delivery_status == 'Delivered') fa-check-double text-green-500 @else fa-truck text-gray-300 @endif text-xl animate-bounce"></i>
                                </div>
                                <h4 class="text-xs font-black text-gray-800 uppercase tracking-widest">Tracking Status</h4>
                                <p class="text-xs text-gray-500 mt-2 font-bold px-4 leading-relaxed">Everything is currently marked as <span class="text-indigo-600">{{ $transaction->delivery_status ?? 'Pending' }}</span> in the system.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Stakeholders Column -->
                <div class="space-y-8">
                    
                    <!-- Stakeholder Summary -->
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-8 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-user-friends"></i> Legal Entities Involved
                        </h3>
                        
                        <div class="space-y-8">
                            <!-- Farmer -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-700 font-black text-lg">
                                    {{ substr($transaction->farmer->first_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">The Supplier (Farmer)</p>
                                    <p class="text-sm font-black text-gray-800 leading-tight mt-0.5">
                                        <a href="{{ route('admin.users.show', $transaction->farmer) }}" class="hover:text-emerald-600 transition-colors">{{ $transaction->farmer->first_name ?? 'N/A' }} {{ $transaction->farmer->last_name ?? '' }}</a>
                                    </p>
                                    <p class="text-[10px] font-medium text-gray-500 mt-1">{{ $transaction->farmer->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <!-- Buyer -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-700 font-black text-lg">
                                    {{ substr($transaction->buyer->first_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">The Requester (Buyer)</p>
                                    <p class="text-sm font-black text-gray-800 leading-tight mt-0.5">
                                        <a href="{{ route('admin.users.show', $transaction->buyer) }}" class="hover:text-blue-600 transition-colors">{{ $transaction->buyer->first_name ?? 'N/A' }} {{ $transaction->buyer->last_name ?? '' }}</a>
                                    </p>
                                    <p class="text-[10px] font-medium text-gray-500 mt-1">{{ $transaction->buyer->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Internal Verification Note -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 rounded-[2rem] p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xs">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="text-xs font-black text-amber-800 uppercase tracking-widest leading-none">Internal Compliance</h4>
                        </div>
                        <p class="text-xs font-bold text-amber-700/80 leading-relaxed">This record represents a finalized marketplace agreement. Verified by the system on ledger ID #{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}. Any modifications to financial values must be performed by a System Administrator.</p>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
@endsection
