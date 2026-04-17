@extends('layouts.farmers_page')

@section('title', 'Product Listings • AgriConnect')

@section('content')
    <div
        class="ml-64 mr-5 mt-5 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">

        <!-- Subtle Background Elements -->
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none">
        </div>

        <main class="relative z-10 flex-1 p-8 lg:p-10">
            <div class="max-w-7xl mx-auto">

                <!-- Header -->
                <div
                    class="flex flex-col md:flex-row md:justify-between md:items-end mb-5 pb-6 border-b border-gray-100 gap-6">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20 text-2xl">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Product Inventory</h2>
                            <p class="text-sm text-gray-500 font-medium mt-1">Manage and track your agricultural supply
                                listings</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('farmer.products.create') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 text-white px-6 py-3 text-sm font-black shadow-lg shadow-emerald-600/20 hover:bg-emerald-500 transition-all active:scale-95 group">
                            <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                            <span>Register New Product</span>
                        </a>
                    </div>
                </div>



                <!-- Toolbar / Filters -->
                <div
                    class="relative z-[60] bg-white/60 backdrop-blur-lg rounded-[2rem] border border-white shadow-sm p-6 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <form method="GET" action="{{ route('farmer.products.index') }}"
                        class="flex flex-wrap items-center gap-4 w-full">
                        <div class="flex items-center gap-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Show</label>
                            <select name="per_page" onchange="this.form.submit()"
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold text-gray-600 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all shadow-sm">
                                <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</label>
                            <select name="status" onchange="this.form.submit()"
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold text-gray-600 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all shadow-sm min-w-[140px]">
                                <option value="">Any State</option>
                                <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available
                                </option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Sold Out" {{ request('status') == 'Sold Out' ? 'selected' : '' }}>Sold Out
                                </option>
                            </select>
                        </div>

                        <div class="relative flex-1 lg:max-w-md group ml-auto">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search inventory detail..."
                                class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm text-sm font-medium">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                                Search
                            </button>
                            <a href="{{ route('farmer.products.index') }}"
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-xl transition-all active:scale-95">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Data Table -->
                <div class="bg-white/40 backdrop-blur-xl border border-white rounded-[2.5rem] shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th
                                        class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">
                                        Listing Context</th>
                                    <th
                                        class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">
                                        Volume Metrics</th>
                                    <th
                                        class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">
                                        Finances</th>
                                    <th
                                        class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">
                                        Cycle Info</th>
                                    <th
                                        class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">
                                        Market State</th>
                                    <th
                                        class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($products as $product)
                                    <tr class="hover:bg-white/60 transition-all group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-center text-gray-300 font-black overflow-hidden bg-cover bg-center transition-all group-hover:scale-105"
                                                    style="background-image: url('{{ $product->image ? asset('storage/' . $product->image) : '' }}')">
                                                    @if(!$product->image) <i class="fas fa-image text-gray-200"></i> @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-gray-800">
                                                        @php
                                                            $eggTypes = [
                                                                'chicken' => 'Chicken',
                                                                'duck' => 'Duck',
                                                                'quail' => 'Quail',
                                                                'native_chicken' => 'Native Chicken',
                                                                'brown' => 'Brown Egg',
                                                                'white' => 'White Egg',
                                                            ];
                                                        @endphp
                                                        {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}
                                                    </div>
                                                    <div
                                                        class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded inline-block mt-0.5 uppercase tracking-tighter">
                                                        {{ $product->product_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <div class="text-sm font-black text-gray-800">{{ $product->quantity }} <span
                                                            class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $product->unit }}</span>
                                                    </div>
                                                    <div class="text-[10px] font-medium text-gray-400 mt-1.5 uppercase tracking-widest">
                                                        Total Supply</div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-sm font-black text-emerald-600">{{ $product->remainingInventory ? $product->remainingInventory->remaining_quantity : $product->quantity }} <span
                                                            class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest">{{ $product->unit }}</span>
                                                    </div>
                                                    <div class="text-[10px] font-medium text-emerald-600/70 mt-1.5 uppercase tracking-widest">
                                                        Remaining</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-black text-indigo-700">
                                                ₱{{ number_format($product->price, 2) }} <span
                                                    class="text-[9px] text-gray-400 font-medium">/{{ $product->unit }}</span>
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-tighter">
                                                Valuation: ₱{{ number_format($product->quantity * $product->price, 2) }}</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-700 leading-none">
                                                {{ \Carbon\Carbon::parse($product->harvest_date)->format('M d, Y') }}
                                            </div>
                                            <div class="text-[10px] font-medium text-gray-400 mt-1.5 uppercase tracking-widest">
                                                Harvest Date</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full border shadow-sm
                                                                                @if($product->status == 'Available') bg-emerald-50 text-emerald-700 border-emerald-100
                                                                                @elseif($product->status == 'Sold Out') bg-red-50 text-red-700 border-red-100
                                                                                @else bg-amber-50 text-amber-700 border-amber-100 @endif">
                                                <i class="fas fa-circle text-[6px] mr-1.5 align-middle"></i>
                                                {{ $product->status }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('farmer.products.show', $product) }}"
                                                    class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-blue-100 shadow-sm"
                                                    title="View Listing">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                                <a href="{{ route('farmer.products.edit', $product) }}"
                                                    class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-orange-100 shadow-sm"
                                                    title="Edit Inventory">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>

                                                <div class="relative">
                                                    <button type="button"
                                                        onclick="toggleDropdownById('dropdown-menu-{{ $product->id }}')"
                                                        class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all duration-300 flex items-center justify-center border border-gray-200 shadow-sm">
                                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                                    </button>
                                                    <div id="dropdown-menu-{{ $product->id }}"
                                                        class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200 text-left">
                                                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                                            <p
                                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                                                Lifecycle Actions</p>
                                                        </div>
                                                        <a href="{{ route('farmer.products.orders', $product) }}"
                                                            class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 border-b border-gray-50">
                                                            <i class="fas fa-users text-emerald-500"></i> Active Orders
                                                        </a>
                                                        <form action="{{ route('farmer.products.destroy', $product) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full text-left flex items-center gap-2 px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50 border-b border-gray-50">
                                                                <i class="fas fa-trash"></i> Purge Record
                                                            </button>
                                                        </form>
                                                        <div
                                                            class="bg-gray-50/50 px-4 py-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                                            State Switching</div>
                                                        <button onclick="changeProductStatus({{ $product->id }}, 'Available', {{ $product->remainingInventory ? $product->remainingInventory->remaining_quantity : $product->quantity }})"
                                                            class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-[11px] font-bold text-gray-600 hover:bg-emerald-50 hover:text-emerald-700">
                                                            <i class="fas fa-check-circle text-emerald-500"></i> Mark Available
                                                        </button>
                                                        <button onclick="changeProductStatus({{ $product->id }}, 'Pending', {{ $product->remainingInventory ? $product->remainingInventory->remaining_quantity : $product->quantity }})"
                                                            class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-[11px] font-bold text-gray-600 hover:bg-amber-50 hover:text-amber-700">
                                                            <i class="fas fa-clock text-amber-500"></i> Mark Pending
                                                        </button>
                                                        <button onclick="changeProductStatus({{ $product->id }}, 'Sold Out', {{ $product->remainingInventory ? $product->remainingInventory->remaining_quantity : $product->quantity }})"
                                                            class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-[11px] font-bold text-gray-600 hover:bg-red-50 hover:text-red-700">
                                                            <i class="fas fa-times-circle text-red-500"></i> Mark Sold Out
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-8 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 mb-4 border-2 border-dashed border-gray-100">
                                                    <i class="fas fa-box-open text-3xl"></i>
                                                </div>
                                                <h4 class="text-lg font-black text-gray-300 uppercase tracking-widest">Inventory
                                                    Empty</h4>
                                                <p class="text-xs text-gray-400 mt-2 font-medium">You haven't listed any
                                                    agricultural products yet.</p>
                                                <a href="{{ route('farmer.products.create') }}"
                                                    class="mt-6 px-6 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20">
                                                    Seed First Listing
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($products->hasPages())
                        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                            <div class="bg-white p-1 rounded-2xl border border-gray-100 shadow-sm w-fit mx-auto lg:mx-0">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @vite('resources/js/farmer/products/farmer-products-index.js')

@endsection