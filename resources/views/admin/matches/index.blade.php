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
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-600/20 text-2xl">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Supply-Demand Sync</h2>
                        <p class="text-sm text-gray-500 font-medium mt-1">Manage algorithmic connections and match states</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="hidden lg:flex items-center gap-6 px-6 py-2.5 bg-white/50 backdrop-blur-md rounded-2xl border border-white shadow-sm mr-4">
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total Connections</p>
                            <p class="text-lg font-black text-gray-800 mt-1">{{ $matches->total() }}</p>
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
                                   class="block px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-purple-50 transition-colors {{ request('per_page') == $count ? 'bg-purple-50/50 text-purple-700' : '' }}">
                                    {{ $count }} rows
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('status-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Sync Status:</span> {{ request('status') ? ucfirst(request('status')) : 'All' }}
                            <i class="fas fa-filter text-[10px] text-gray-400"></i>
                        </button>
                        <div id="status-filter-dropdown" class="hidden absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="p-2 bg-gray-50 border-b border-gray-100 text-[9px] font-black uppercase text-gray-400 tracking-widest pl-4">Match Lifecycle</div>
                            <a href="{{ request()->fullUrlWithoutQuery(['status', 'page']) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-purple-50 border-b border-gray-50">All Statuses</a>
                            @foreach($statuses as $status)
                                <a href="{{ request()->fullUrlWithQuery(['status' => $status, 'page' => 1]) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-purple-50">
                                    <span class="w-2 h-2 rounded-full inline-block mr-2 
                                        @if($status == 'Accepted') bg-emerald-500 
                                        @elseif($status == 'Pending') bg-amber-500 
                                        @elseif($status == 'Rejected') bg-red-500 
                                        @elseif($status == 'Transaction Started') bg-indigo-500 
                                        @elseif($status == 'Ordered') bg-purple-500 @endif"></span>
                                    {{ ucfirst($status) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <form action="{{ route('admin.matches.index') }}" method="GET" class="relative lg:w-80 group">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sync ID or entities..."
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all shadow-sm text-sm font-medium">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
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
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Components</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Primary Stakeholders</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Verification State</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($matches as $match)
                                <tr class="hover:bg-white/60 transition-all group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-xs font-black text-gray-300 tracking-widest">#MCH_{{ str_pad($match->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase">{{ $match->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span class="text-sm font-black text-gray-800 leading-none">{{ $match->product->product_name ?: $match->product->egg_type }}</span>
                                                @if($match->product->variety_size)
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $match->product->variety_size }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-tight">
                                                    @php
                                                        $eggTypes = ['chicken' => 'Chicken', 'duck' => 'Duck', 'quail' => 'Quail', 'native_chicken' => 'Native Chicken', 'brown' => 'Brown Egg', 'white' => 'White Egg'];
                                                    @endphp
                                                    {{ $eggTypes[$match->demand->egg_type] ?? ucfirst(str_replace('_', ' ', $match->demand->egg_type)) }} Requirement
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex flex-col gap-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-[9px] border border-emerald-100">F</div>
                                                <div class="text-[11px] font-bold text-gray-700 leading-none">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-black text-[9px] border border-blue-100">B</div>
                                                <div class="text-[11px] font-bold text-gray-700 leading-none">{{ $match->demand->buyer->first_name }} {{ $match->demand->buyer->last_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full border shadow-sm
                                            @if($match->status == 'Accepted') bg-emerald-50 text-emerald-700 border-emerald-100
                                            @elseif($match->status == 'Pending') bg-amber-50 text-amber-700 border-amber-100
                                            @elseif($match->status == 'Rejected') bg-red-50 text-red-700 border-red-100
                                            @elseif($match->status == 'Transaction Started') bg-indigo-50 text-indigo-700 border-indigo-100
                                            @elseif($match->status == 'Ordered') bg-purple-50 text-purple-700 border-purple-100
                                            @else bg-gray-50 text-gray-700 border-gray-100 @endif">
                                            {{ ucfirst($match->status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.matches.view', $match) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-blue-100 shadow-sm" title="Detailed Verification">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.matches.delete', $match) }}" method="POST" class="delete-form" data-match-id="{{ $match->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-red-100 shadow-sm" title="Sever Connection">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 mb-4 border-2 border-dashed border-gray-100">
                                                <i class="fas fa-link-slash text-3xl"></i>
                                            </div>
                                            <h4 class="text-lg font-black text-gray-300 uppercase tracking-widest">No Active Connections</h4>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">Platform algorithm has not matched any supply with market requirements.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($matches->hasPages())
                    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                        {{ $matches->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>
</div>

<script>
    function toggleDropdownById(id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = document.querySelectorAll('[id$="-dropdown"]');
        
        allDropdowns.forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    window.addEventListener('click', function(e) {
        if (!e.target.closest('button')) {
            document.querySelectorAll('[id$="-dropdown"]').forEach(d => d.classList.add('hidden'));
        }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const matchId = this.getAttribute('data-match-id');
            if (confirm(`SYSTEM ALERT: Sever match connection #${matchId}?\n\nThis will terminate the algorithmic link between these stakeholders.`)) {
                this.submit();
            }
        });
    });
</script>
@endsection
