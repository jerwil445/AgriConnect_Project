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
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Marketplace Demand</h2>
                        <p class="text-sm text-gray-500 font-medium mt-1">Track and audit active buyer requirements</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="hidden lg:flex items-center gap-6 px-6 py-2.5 bg-white/50 backdrop-blur-md rounded-2xl border border-white shadow-sm mr-4">
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Open Requests</p>
                            <p class="text-lg font-black text-gray-800 mt-1">{{ $demands->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toolbar / Filters -->
            <div class="bg-white/60 backdrop-blur-lg rounded-[2rem] border border-white shadow-sm p-6 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                <div class="flex flex-wrap items-center gap-4">
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
                                   class="block px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-blue-50 transition-colors {{ request('per_page') == $count ? 'bg-blue-50/50 text-blue-700' : '' }}">
                                    {{ $count }} rows
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buyer Filter -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('buyer-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Requester:</span> {{ request('buyer') ? 'Selected' : 'All Buyers' }}
                            <i class="fas fa-store text-[10px] text-gray-400"></i>
                        </button>
                        <div id="buyer-filter-dropdown" class="hidden absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                             <div class="p-2 bg-gray-50 border-b border-gray-100 text-[9px] font-black uppercase text-gray-400 tracking-widest pl-4">Buyer Directory</div>
                             <div class="max-h-64 overflow-y-auto">
                                <a href="{{ request()->fullUrlWithoutQuery(['buyer', 'page']) }}" class="block px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-blue-50 {{ !request('buyer') ? 'bg-blue-50 text-blue-700' : '' }}">All Buyers</a>
                                @foreach($buyers as $buyer)
                                    <a href="{{ request()->fullUrlWithQuery(['buyer' => $buyer->id, 'page' => 1]) }}" class="block px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-blue-50 {{ request('buyer') == $buyer->id ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500' : '' }}">
                                        {{ $buyer->first_name }} {{ $buyer->last_name }}
                                    </a>
                                @endforeach
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <form action="{{ route('admin.demands.index') }}" method="GET" class="relative lg:w-80 group">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <input type="hidden" name="buyer" value="{{ request('buyer') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search demand requirements..."
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
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Registry ID</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Product Requirement</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Volume & Rate</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Market Stakeholder</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Sync Status</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($demands as $demand)
                                <tr class="hover:bg-white/60 transition-all group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-xs font-black text-gray-300 tracking-widest">#DMN_{{ str_pad($demand->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase">{{ $demand->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-800 tracking-tight">{{ $demand->egg_type }}</div>
                                        <div class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded inline-block mt-1 uppercase tracking-tighter shadow-sm border border-blue-100 hover:bg-blue-100 transition-colors cursor-default">Active Request</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-800">{{ $demand->quantity }} <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Units</span></div>
                                        <div class="text-[11px] font-bold text-indigo-700 mt-1 leading-none italic">Targeting ₱{{ number_format($demand->target_price ?? 0, 2) }} <span class="text-[9px] text-gray-400 font-medium">/unit</span></div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs border border-blue-100">
                                                {{ substr($demand->buyer->first_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-700 group-hover:text-blue-700 transition-colors">{{ $demand->buyer->first_name }} {{ $demand->buyer->last_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col items-center justify-center">
                                                <span class="text-sm font-black text-gray-800 leading-none">{{ $demand->matches->count() }}</span>
                                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Matches</span>
                                            </div>
                                            @if($demand->matches->count() > 0)
                                                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.demands.view', $demand) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-blue-100 shadow-sm" title="Detailed Specifications">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.demands.edit', $demand) }}" class="w-9 h-9 rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-green-100 shadow-sm" title="Modify Order">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            
                                            <div class="relative">
                                                <button type="button" onclick="toggleDropdownById('actions-menu-{{ $demand->id }}')" 
                                                        class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all duration-300 flex items-center justify-center border border-gray-200 shadow-sm">
                                                    <i class="fas fa-ellipsis-v text-xs"></i>
                                                </button>
                                                <div id="actions-menu-{{ $demand->id }}" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200 text-left">
                                                    <a href="{{ route('admin.demands.audit', $demand) }}" class="block px-4 py-3 text-xs font-bold text-purple-700 hover:bg-purple-50 transition-colors border-b border-gray-50">
                                                        <i class="fas fa-clipboard-check mr-2"></i> Audit Connection Alg
                                                    </a>
                                                    <form action="{{ route('admin.demands.delete', $demand) }}" method="POST" class="delete-form" data-demand-name="{{ $demand->egg_type }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
                                                            <i class="fas fa-trash-alt mr-2"></i> Delete Request
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 mb-4 border-2 border-dashed border-gray-100">
                                                <i class="fas fa-search-dollar text-3xl"></i>
                                            </div>
                                            <h4 class="text-lg font-black text-gray-300 uppercase tracking-widest">No Active Demands</h4>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">There are currently no marketplace requirements registered.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($demands->hasPages())
                    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                        {{ $demands->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>
</div>

<script>
    function toggleDropdownById(id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = document.querySelectorAll('[id$="-dropdown"], [id^="actions-menu-"]');
        
        allDropdowns.forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    window.addEventListener('click', function(e) {
        if (!e.target.closest('button') && !e.target.closest('form')) {
            document.querySelectorAll('[id$="-dropdown"], [id^="actions-menu-"]').forEach(d => d.classList.add('hidden'));
        }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const demandName = this.getAttribute('data-demand-name');
            if (confirm(`⚠️ PERMANENT DELETE: Remove market demand for "${demandName}"?\n\nThis will also sever any active algorithm matches.`)) {
                this.submit();
            }
        });
    });
</script>
@endsection