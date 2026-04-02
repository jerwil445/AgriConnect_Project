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
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-600/20 text-3xl">
                        <i class="fas fa-handshake rotate-[-12deg]"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Match Verification</h2>
                        <div class="flex flex-wrap items-center mt-3 gap-2">
                            <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border
                                @if($match->status == 'Accepted') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($match->status == 'Pending') bg-amber-50 text-amber-700 border-amber-200
                                @elseif($match->status == 'Rejected') bg-red-50 text-red-700 border-red-200
                                @elseif($match->status == 'Transaction Started') bg-blue-50 text-blue-700 border-blue-200
                                @elseif($match->status == 'Ordered') bg-purple-50 text-purple-700 border-purple-200
                                @else bg-gray-50 text-gray-700 border-gray-200 @endif">
                                {{ $match->status }}
                            </span>
                            <span class="text-sm text-gray-500 font-medium ml-2">Match ID: #MCH_{{ str_pad($match->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="javascript:history.back()" 
                       class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                        <i class="fas fa-arrow-left mr-2 text-gray-400 group-hover:text-gray-600"></i> Back
                    </a>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-20">
                
                <!-- Match & Product Summary -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-purple-700 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i> Connection Intelligence
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-gray-200/50 text-5xl pointer-events-none"><i class="fas fa-calendar-alt"></i></div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Match Date</p>
                                <p class="text-sm font-bold text-gray-900 mt-1 relative z-10">{{ $match->matched_date ? $match->matched_date->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            
                            <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-indigo-200/50 text-5xl pointer-events-none"><i class="fas fa-history"></i></div>
                                <p class="text-xs font-medium text-indigo-700 uppercase">System Identity</p>
                                <p class="text-sm font-bold text-indigo-900 mt-1 relative z-10">{{ $match->created_at->format('M d, Y') }}</p>
                            </div>

                            <div class="bg-emerald-50/50 rounded-2xl p-4 border border-emerald-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-emerald-200/50 text-5xl pointer-events-none"><i class="fas fa-sync"></i></div>
                                <p class="text-xs font-medium text-emerald-700 uppercase">Last Interaction</p>
                                <p class="text-sm font-bold text-emerald-900 mt-1 relative z-10">{{ $match->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="p-6 bg-gradient-to-br from-green-50/50 to-emerald-50/50 rounded-3xl border border-green-100/50">
                            <h4 class="text-xs font-bold text-green-800 uppercase mb-4 flex items-center gap-2"><i class="fas fa-leaf"></i> Matching Inventory</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-green-700/60 uppercase">Product Name</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $match->product->product_name ?: $match->product->egg_type ?: 'N/A' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-green-700/60 uppercase">Grade / Variety</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $match->product->variety_size ?: 'Standard' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-green-700/60 uppercase">Available Quantity</p>
                                    <p class="text-sm font-black text-gray-800">{{ $match->product->quantity }} {{ $match->product->unit }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-green-700/60 uppercase">Farmer Pricing</p>
                                    <p class="text-sm font-black text-indigo-600">₱{{ number_format((float) $match->product->price, 2) }} <span class="text-[9px] text-gray-400 font-medium">/{{ $match->product->unit }}</span></p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-green-700/60 uppercase">Harvest Date</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $match->product->harvest_date ? $match->product->harvest_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-green-700/60 uppercase">Product Status</p>
                                    <span class="px-2 py-0.5 text-[9px] font-black rounded-full border 
                                        @if($match->product->status == 'Available') bg-emerald-100 text-emerald-700 border-emerald-200
                                        @else bg-red-100 text-red-700 border-red-200 @endif capitalize">
                                        {{ $match->product->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Demand Requirements Mirror -->
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-blue-700 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                            <i class="fas fa-bullseye"></i> Targeted Marketplace Demand
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100/50">
                                    <p class="text-[10px] font-bold text-blue-700/70 uppercase mb-2">Requested Specification</p>
                                    <div class="flex items-center justify-between">
                                        <p class="text-base font-black text-gray-800">
                                            @php
                                                $eggTypes = ['chicken' => 'Chicken', 'duck' => 'Duck', 'quail' => 'Quail', 'native_chicken' => 'Native Chicken', 'brown' => 'Brown Egg', 'white' => 'White Egg'];
                                            @endphp
                                            {{ $match->demand->product_name ?: ($eggTypes[$match->demand->egg_type] ?? ucfirst(str_replace('_', ' ', $match->demand->egg_type))) }}
                                        </p>
                                        <span class="bg-blue-600 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-sm">QTY: {{ $match->demand->quantity }}</span>
                                    </div>
                                    @if($match->demand->egg_size)
                                        <p class="mt-2 text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100 inline-block capitalize">{{ str_replace('_', ' ', $match->demand->egg_size) }}</p>
                                    @endif
                                </div>

                                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100/50">
                                    <p class="text-[10px] font-bold text-indigo-700/70 uppercase mb-1">Target Procurement Price</p>
                                    <p class="text-lg font-black text-gray-900">₱{{ number_format((float) ($match->demand->target_price ?? 0), 2) }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100/50">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase mb-2 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-red-400"></i> Desired Logistics</p>
                                    <p class="text-xs font-bold text-gray-800 leading-relaxed">
                                        @if($match->demand->purok_street || $match->demand->barangay || $match->demand->municipality_city || $match->demand->province)
                                            {{ $match->demand->purok_street ? $match->demand->purok_street . ', ' : '' }}
                                            {{ $match->demand->barangay ? $match->demand->barangay . ', ' : '' }}
                                            {{ $match->demand->municipality_city ? $match->demand->municipality_city . ', ' : '' }}
                                            {{ $match->demand->province }}
                                        @else
                                            {{ $match->demand->location ?: 'Contact buyer for specifics' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Stakeholder Profile Column -->
                <div class="space-y-8">
                    
                    <!-- Farmer Card -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100 rounded-[2.5rem] shadow-sm relative overflow-hidden p-8">
                        <div class="absolute -right-6 -top-6 text-green-200/40 transform rotate-12 pointer-events-none">
                            <i class="fas fa-tractor text-[10rem]"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-green-700/70 mb-8 border-b border-green-200 pb-2 flex items-center gap-2">
                                <i class="fas fa-user-circle"></i> Origin Stakeholder
                            </h3>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-green-100 flex items-center justify-center text-green-600 font-black text-2xl">
                                    {{ substr($match->product->farmer->user->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-lg font-black text-gray-800 leading-tight">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</p>
                                    <p class="text-xs font-bold text-green-600 mt-1">{{ $match->product->farmer->farm_name ?? 'Local Farm Supplier' }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white/60 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-green-500 transition-colors"><i class="fas fa-envelope text-xs"></i></div>
                                    <p class="text-xs font-bold text-gray-600 truncate">{{ $match->product->farmer->user->email }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white/60 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-green-500 transition-colors"><i class="fas fa-phone text-xs"></i></div>
                                    <p class="text-xs font-bold text-gray-600">{{ $match->product->farmer->user->phone_number ?? 'No Phone' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buyer Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-[2.5rem] shadow-sm relative overflow-hidden p-8">
                        <div class="absolute -right-6 -top-6 text-blue-200/40 transform rotate-12 pointer-events-none">
                            <i class="fas fa-store text-[10rem]"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-700/70 mb-8 border-b border-blue-200 pb-2 flex items-center gap-2">
                                <i class="fas fa-building"></i> Request Stakeholder
                            </h3>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-blue-100 flex items-center justify-center text-blue-600 font-black text-2xl">
                                    {{ substr($match->demand->buyer->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-lg font-black text-gray-800 leading-tight">{{ $match->demand->buyer->first_name }} {{ $match->demand->buyer->last_name }}</p>
                                    <p class="text-xs font-bold text-blue-600 mt-1">{{ $match->demand->buyer->buyer->company_name ?? 'Independent Retailer' }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white/60 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-blue-500 transition-colors"><i class="fas fa-envelope text-xs"></i></div>
                                    <p class="text-xs font-bold text-gray-600 truncate">{{ $match->demand->buyer->email }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white/60 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-blue-500 transition-colors"><i class="fas fa-phone text-xs"></i></div>
                                    <p class="text-xs font-bold text-gray-600">{{ $match->demand->buyer->phone_number ?? 'No Phone' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
@endsection
