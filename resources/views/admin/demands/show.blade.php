@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 pb-6 border-b border-gray-100 gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-600/20 text-3xl">
                        <i class="fas fa-bullhorn rotate-[-12deg]"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Demand Specification</h2>
                        <div class="flex flex-wrap items-center mt-3 gap-2">
                            <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border
                                @if($demand->status == 'matched') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($demand->status == 'unmatched') bg-amber-50 text-amber-700 border-amber-200
                                @elseif($demand->status == 'in negotiation') bg-blue-50 text-blue-700 border-blue-200
                                @elseif($demand->status == 'completed') bg-purple-50 text-purple-700 border-purple-200
                                @else bg-gray-50 text-gray-700 border-gray-200 @endif">
                                {{ ucfirst($demand->status ?? 'unmatched') }}
                            </span>
                            <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border bg-gray-50 text-gray-600 border-gray-200 capitalize">
                                <i class="fas fa-egg mr-1.5 mt-0.5"></i> {{ $demand->egg_type }}
                            </span>
                            <span class="text-sm text-gray-500 font-medium ml-2">ID: #DMND_{{ str_pad($demand->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.demands.index') }}" 
                       class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                        <i class="fas fa-arrow-left mr-2 text-gray-400 group-hover:text-gray-600"></i> Back
                    </a>
                    <a href="{{ route('admin.demands.audit', $demand) }}" 
                       class="bg-white hover:bg-purple-50 text-purple-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-purple-100 shadow-sm transition-all duration-200 flex items-center group">
                        <i class="fas fa-search-dollar mr-2 text-purple-400 group-hover:text-purple-600"></i> Audit Matches
                    </a>
                    <a href="{{ route('admin.demands.edit', $demand) }}" 
                       class="bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-400 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md transition-all duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-20">
                
                <!-- Main Requirements Card -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-green-700 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                            <i class="fas fa-clipboard-list"></i> Market Requirement Details
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-gray-200/50 text-5xl pointer-events-none"><i class="fas fa-shopping-basket"></i></div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Quantity Requested</p>
                                <p class="text-xl font-black text-gray-900 mt-1 relative z-10">{{ $demand->quantity }} <span class="text-sm font-semibold text-gray-500">Trays</span></p>
                            </div>
                            
                            <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-indigo-200/50 text-5xl pointer-events-none"><i class="fas fa-tag"></i></div>
                                <p class="text-xs font-medium text-indigo-700 uppercase">Target Price</p>
                                <p class="text-xl font-black text-indigo-900 mt-1 relative z-10">₱{{ number_format($demand->target_price ?? 0, 2) }}</p>
                            </div>

                            <div class="bg-amber-50/50 rounded-2xl p-4 border border-amber-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-amber-200/50 text-5xl pointer-events-none"><i class="fas fa-calendar-check"></i></div>
                                <p class="text-xs font-medium text-amber-700 uppercase">Delivery Date</p>
                                <p class="text-xl font-black text-amber-900 mt-1 relative z-10">{{ $demand->delivery_date ? $demand->delivery_date->format('M d, Y') : 'Immediate' }}</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @if($demand->egg_size)
                            <div>
                                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-sort-amount-up text-green-500"></i> Required Egg Sizes</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(', ', $demand->egg_size) as $size)
                                        <span class="bg-white border border-green-100 text-green-700 px-4 py-2 rounded-xl text-sm font-bold shadow-sm flex items-center gap-2 capitalize">
                                            <i class="fas fa-check-circle text-[10px]"></i> {{ str_replace('_', ' ', $size) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div>
                                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-map-marked-alt text-blue-500"></i> Delivery Logistics</h4>
                                <div class="bg-blue-50/30 rounded-2xl p-5 border border-blue-50 text-sm">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-blue-600/70 mb-0.5">Target City/Region</p>
                                            <p class="font-bold text-gray-900 text-base">{{ $demand->location ?: 'Unspecified Location' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-blue-600/70 mb-0.5">Full Drop-off Address</p>
                                            <p class="font-semibold text-gray-700">
                                                {{ $demand->purok_street ? $demand->purok_street . ', ' : '' }}
                                                {{ $demand->barangay ? $demand->barangay . ', ' : '' }}
                                                {{ $demand->municipality_city ? $demand->municipality_city . ', ' : '' }}
                                                {{ $demand->province ?: $demand->address ?: 'Contact buyer for specifics' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Match Summary Table/List -->
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-purple-700 flex items-center gap-2">
                                <i class="fas fa-handshake"></i> Algorithm Matches
                            </h3>
                            <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $demand->matches->count() }} Hits</span>
                        </div>
                        
                        @if($demand->matches->count() > 0)
                            <div class="overflow-hidden border border-gray-100 rounded-2xl">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Farmer</th>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white/50">
                                        @foreach($demand->matches->take(5) as $match)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-800">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</div>
                                                    <div class="text-[10px] text-gray-400 capitalize">{{ $match->product->farm_name ?? 'Poultry Farm' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-full border 
                                                        @if($match->status == 'Accepted') bg-green-50 text-green-700 border-green-100
                                                        @else bg-gray-50 text-gray-500 border-gray-100 @endif">
                                                        {{ $match->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-[10px] font-medium text-gray-500">
                                                    {{ $match->created_at->format('M d') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-10 bg-gray-50/30 rounded-2xl border border-dashed border-gray-200">
                                <i class="fas fa-search text-gray-300 text-3xl mb-3"></i>
                                <p class="text-sm text-gray-500 font-medium">No verified matches found for this demand yet.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Right Column: Buyer Info -->
                <div class="space-y-8">
                    
                    <!-- Analytics Sidebar -->
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-5 pb-2 border-b border-gray-100">Timeline Tracking</h3>
                        <dl class="space-y-4">
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase mb-1 flex items-center gap-1.5"><i class="fas fa-calendar-plus text-green-500"></i> Market Post Date</dt>
                                <dd class="text-sm font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100">{{ $demand->created_at->format('F d, Y') }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase mb-1 flex items-center gap-1.5"><i class="fas fa-sync text-indigo-500"></i> Last Maintenance</dt>
                                <dd class="text-sm font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100">{{ $demand->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Buyer Profile Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 backdrop-blur-md border border-blue-100 rounded-[2rem] shadow-sm relative overflow-hidden p-6 text-blue-900">
                        <div class="absolute -right-4 -top-4 text-blue-200 opacity-40 transform rotate-12 pointer-events-none">
                            <i class="fas fa-store text-8xl"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-800 mb-5 pb-2 border-b border-blue-200/50 flex items-center gap-2">
                                <i class="fas fa-user-tie"></i> Buyer Requester
                            </h3>
                            
                            <dl class="space-y-4">
                                <div class="flex flex-col">
                                    <dt class="text-[10px] uppercase font-bold text-blue-700/70 mb-0.5">Representative</dt>
                                    <dd class="text-sm font-bold text-gray-900">
                                        <a href="{{ route('admin.users.show', $demand->buyer) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                                            {{ $demand->buyer->first_name }} {{ $demand->buyer->last_name }} <i class="fas fa-external-link-alt text-[10px] text-gray-400"></i>
                                        </a>
                                    </dd>
                                </div>
                                
                                <div class="flex flex-col">
                                    <dt class="text-[10px] uppercase font-bold text-blue-700/70 mb-0.5">Business Entity</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $demand->buyer->buyer->company_name ?? 'Independent Retailer' }}</dd>
                                    <dd class="text-[10px] font-medium text-blue-600/80">{{ $demand->buyer->buyer->business_type ?? 'N/A' }}</dd>
                                </div>

                                <div class="flex flex-col">
                                    <dt class="text-[10px] uppercase font-bold text-blue-700/70 mb-0.5">Contact Detail</dt>
                                    <dd class="text-sm font-medium text-gray-700 space-y-1">
                                        <div class="flex items-center gap-2"><i class="fas fa-envelope text-gray-400 w-3"></i> {{ $demand->buyer->email }}</div>
                                        <div class="flex items-center gap-2"><i class="fas fa-phone text-gray-400 w-3"></i> {{ $demand->buyer->phone_number ?? 'No contact' }}</div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Danger Action -->
                    <div class="mt-8 border border-red-100 bg-red-50/50 rounded-2xl p-5">
                        <form action="{{ route('admin.demands.delete', $demand) }}" method="POST" class="delete-form w-full" data-demand-name="{{ $demand->product_name ?: $demand->egg_type }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-white border border-red-200 hover:bg-red-600 hover:text-white hover:border-red-600 text-red-600 font-bold px-4 py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-sm text-sm">
                                <i class="fas fa-trash-alt"></i> Delete Market Demand
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const demandName = this.getAttribute('data-demand-name');
            if (confirm(`⚠️ WARNING: Permanently delete demand for "${demandName}"?\n\nThis will break active match calculations and archival records.`)) {
                this.submit();
            }
        });
    });
</script>
@endsection