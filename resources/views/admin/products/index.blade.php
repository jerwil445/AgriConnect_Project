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
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20 text-2xl">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Product Inventory</h2>
                        <p class="text-sm text-gray-500 font-medium mt-1">Monitor and verify agricultural supply listings</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="hidden lg:flex items-center gap-6 px-6 py-2.5 bg-white/50 backdrop-blur-md rounded-2xl border border-white shadow-sm">
                        <div class="text-center border-r border-gray-100 pr-6">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Registered Listings</p>
                            <p class="text-lg font-black text-gray-800 mt-1">{{ $products->total() }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest leading-none">Live Access</p>
                            <p class="text-xs font-black text-emerald-600 mt-1 uppercase tracking-tighter">Verified System</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toolbar / Filters -->
            <div class="relative z-[60] bg-white/60 backdrop-blur-lg rounded-[2rem] border border-white shadow-sm p-6 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Farmer Filter -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('farmer-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Farmer:</span> {{ request('farmer') ? 'Selected' : 'All Suppliers' }}
                            <i class="fas fa-tractor text-[10px] text-gray-400"></i>
                        </button>
                        <div id="farmer-filter-dropdown" class="hidden absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="p-2 bg-gray-50 border-b border-gray-100 text-[9px] font-black uppercase text-gray-400 tracking-widest pl-4">Supplier Directory</div>
                            <div class="max-h-64 overflow-y-auto">
                                <a href="{{ request()->fullUrlWithoutQuery(['farmer', 'page']) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-emerald-50 {{ !request('farmer') ? 'bg-emerald-50/50 text-emerald-700 border-l-2 border-emerald-500' : '' }}">All Suppliers</a>
                                @foreach($farmers as $farmer)
                                    <a href="{{ request()->fullUrlWithQuery(['farmer' => $farmer->id, 'page' => 1]) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-emerald-50 {{ request('farmer') == $farmer->id ? 'bg-emerald-50/50 text-emerald-700 border-l-2 border-emerald-500' : '' }}">
                                        {{ $farmer->first_name }} {{ $farmer->last_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('status-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Status:</span> {{ request('status') ?: 'Any State' }}
                            <i class="fas fa-filter text-[10px] text-gray-400"></i>
                        </button>
                        <div id="status-filter-dropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <a href="{{ request()->fullUrlWithoutQuery(['status', 'page']) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-emerald-50">All Statuses</a>
                            @foreach($statuses as $status)
                                <a href="{{ request()->fullUrlWithQuery(['status' => $status, 'page' => 1]) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-emerald-50">
                                    <span class="w-2 h-2 rounded-full inline-block mr-2 @if($status=='Available') bg-emerald-500 @elseif($status=='Sold Out') bg-red-500 @else bg-amber-500 @endif"></span>
                                    {{ $status }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <form action="{{ route('admin.products.index') }}" method="GET" class="relative lg:w-80 group">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <input type="hidden" name="farmer" value="{{ request('farmer') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search inventory detail..."
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm text-sm font-medium">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
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
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Listing Context</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Proprietor</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Volume Metrics</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Finances</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Market State</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($products as $product)
                                <tr class="hover:bg-white/60 transition-all group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-center text-gray-300 font-black overflow-hidden bg-cover bg-center transition-all group-hover:scale-105"
                                                 style="background-image: url('{{ $product->product_image ? asset('storage/' . $product->product_image) : '' }}')">
                                                @if(!$product->product_image) <i class="fas fa-image text-gray-200"></i> @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-800">{{ $product->product_name }}</div>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase tracking-tighter">{{ $product->category ?? 'General' }}</div>
                                                    <div class="text-[10px] font-bold text-gray-400 border border-gray-100 px-1.5 py-0.5 rounded uppercase tracking-tighter">{{ $product->variety_size ?: 'Standard' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700 leading-none">{{ $product->farmer->user->first_name }} {{ $product->farmer->user->last_name }}</div>
                                        <div class="text-[10px] font-medium text-gray-400 mt-1.5 uppercase tracking-widest"><i class="fas fa-map-marker-alt text-[9px] mr-1"></i> Supplier #{{ str_pad($product->farmer->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-800">{{ $product->quantity }} <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $product->unit }}</span></div>
                                        <div class="text-[10px] font-medium text-gray-400 mt-1.5 leading-none">Harvest: {{ optional($product->harvest_date)->format('M d, Y') ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-indigo-700">₱{{ number_format($product->price, 2) }} <span class="text-[9px] text-gray-400 font-medium">/{{ $product->unit }}</span></div>
                                        <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-tighter">Valuation: ₱{{ number_format($product->total_amount ?? ($product->quantity * $product->price), 2) }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full border shadow-sm
                                            @if($product->status == 'Available') bg-emerald-50 text-emerald-700 border-emerald-100
                                            @elseif($product->status == 'Sold Out') bg-red-50 text-red-700 border-red-100
                                            @else bg-amber-50 text-amber-700 border-amber-100 @endif">
                                            <i class="fas fa-circle text-[6px] mr-1.5 align-middle"></i> {{ $product->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.products.view', $product) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-blue-100 shadow-sm">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="w-9 h-9 rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-green-100 shadow-sm">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            
                                            <div class="relative">
                                                <button type="button" onclick="toggleDropdownById('actions-menu-{{ $product->id }}')" 
                                                        class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all duration-300 flex items-center justify-center border border-gray-200 shadow-sm">
                                                    <i class="fas fa-ellipsis-v text-xs"></i>
                                                </button>
                                                <div id="actions-menu-{{ $product->id }}" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200 text-left">
                                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Auth & Visibility</p>
                                                    </div>
                                                    <a href="{{ route('admin.products.approve', $product) }}" class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 border-b border-gray-50">
                                                        <i class="fas fa-check-circle text-emerald-500"></i> Verify Listing
                                                    </a>
                                                    <a href="{{ route('admin.products.reject', $product) }}" class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-amber-50 hover:text-amber-700 border-b border-gray-50">
                                                        <i class="fas fa-history text-amber-500"></i> Revert to Pending
                                                    </a>
                                                    <form action="{{ route('admin.products.delete', $product) }}" method="POST" class="delete-form" data-product-name="{{ $product->product_name }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
                                                            <i class="fas fa-trash-alt"></i> Purge Record
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
                                                <i class="fas fa-box-open text-3xl"></i>
                                            </div>
                                            <h4 class="text-lg font-black text-gray-300 uppercase tracking-widest">Inventory Empty</h4>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">No agricultural listings have been registered for this filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($products->hasPages())
                    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>
</div>

@vite('resources/js/admin/products/admin-products-index.js')
@endsection
