@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 pb-6 border-b border-gray-100 gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-600/20 text-2xl">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Audit Demand Matches</h2>
                        <p class="text-sm text-gray-500 font-medium mt-1">Reviewing results for: <span class="text-purple-600 font-bold">"{{ $demand->product_name ?: $demand->egg_type }}"</span></p>
                    </div>
                </div>
                
                <a href="{{ route('admin.demands.index') }}" 
                   class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                    <i class="fas fa-arrow-left mr-2 text-gray-400 group-hover:text-gray-600"></i> Back to Demands
                </a>
            </div>

            <!-- Demand Summary Card -->
            <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-sm p-6 mb-8 relative overflow-hidden">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-6 flex items-center gap-2">
                    <i class="fas fa-file-invoice text-green-500"></i> Request Specification
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Product Type</p>
                        <p class="text-sm font-black text-gray-800 capitalize">{{ $demand->product_name ?: $demand->egg_type }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Volume Required</p>
                        <p class="text-sm font-black text-gray-800">{{ $demand->quantity }} <span class="text-[10px] text-gray-500">Trays</span></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Budget Cap</p>
                        <p class="text-sm font-black text-indigo-600">₱{{ number_format((float)($demand->target_price ?? 0), 2) }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Requester (Buyer)</p>
                        <p class="text-sm font-black text-gray-800">{{ $demand->buyer->first_name }} {{ $demand->buyer->last_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Matches Table Section -->
            <div class="bg-white/40 backdrop-blur-xl border border-white rounded-[2rem] shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-white/60">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-link text-purple-600"></i> Algorithmic Connections
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Active matches</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Match ID</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Supplier Base</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Product / Quality</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Stock Capability</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit Pricing</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Auth Status</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/20 divide-y divide-gray-100 italic-text-none">
                            @forelse($demand->matches as $match)
                                <tr class="hover:bg-white/60 transition-colors group">
                                    <td class="px-6 py-5 whitespace-nowrap text-[11px] font-mono font-bold text-gray-400">#MCH_{{ str_pad($match->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-700 font-bold text-xs">
                                                {{ substr($match->product->farmer->user->first_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</div>
                                                <div class="text-[10px] font-medium text-gray-400">{{ $match->product->farmer->farm_name ?? 'Local Farm' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-xs font-bold text-gray-700">{{ $match->product->product_name ?: $match->product->egg_type }}</div>
                                        @if ($match->product->variety_size)
                                            <div class="mt-1"><span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[9px] font-bold border border-gray-200 uppercase">{{ $match->product->variety_size }}</span></div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-800">{{ $match->product->quantity }} <span class="text-[10px] text-gray-400 uppercase font-medium">{{ $match->product->unit }}</span></div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm font-black text-indigo-600">₱{{ number_format((float) $match->product->price, 2) }} <span class="text-[9px] text-gray-400 font-medium">/{{ $match->product->unit }}</span></div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full shadow-sm border
                                            @if ($match->status == 'Pending') bg-amber-50 text-amber-700 border-amber-100
                                            @elseif($match->status == 'Accepted') bg-emerald-50 text-emerald-700 border-emerald-100
                                            @elseif($match->status == 'Rejected') bg-red-50 text-red-700 border-red-100
                                            @elseif($match->status == 'Transaction Started') bg-blue-50 text-blue-700 border-blue-100
                                            @elseif($match->status == 'Ordered') bg-purple-50 text-purple-700 border-purple-100
                                            @else bg-gray-50 text-gray-600 border-gray-100 @endif">
                                            @if($match->status == 'Pending') <i class="fas fa-hourglass-half mr-1"></i>
                                            @elseif($match->status == 'Accepted') <i class="fas fa-check-double mr-1"></i>
                                            @endif
                                            {{ $match->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            @if ($match->status == 'Pending')
                                                <form action="{{ route('admin.matches.accept', $match) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" title="Accept Connection" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-300 shadow-sm flex items-center justify-center border border-green-100">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.matches.reject', $match) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" title="Reject Connection" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm flex items-center justify-center border border-red-100">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.matches.view', $match) }}" title="View Profile" 
                                               class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm flex items-center justify-center border border-blue-100">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-4 border border-gray-100 border-dashed">
                                                <i class="fas fa-unlink text-2xl"></i>
                                            </div>
                                            <h4 class="text-sm font-bold text-gray-400 tracking-tight">Zero Network Connections FOUND</h4>
                                            <p class="text-[10px] text-gray-300 mt-1">No verified farmer supply currently fits this market demand.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
