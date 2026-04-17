@extends('layouts.buyers_page')

@section('content')
    <div class="px-4 py-8 max-w-7xl mx-auto">
        <div class="relative bg-white/70 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white p-8 overflow-visible">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fas fa-shopping-bag text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Purchase History</h1>
                        <p class="text-sm text-gray-500 font-medium">Manage and track your active and past orders</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-center gap-3 px-6 py-3 bg-gray-50/50 rounded-2xl border border-gray-100/50 backdrop-blur-sm">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total Orders</p>
                        <p class="text-lg font-black text-gray-600 mt-1 leading-none">{{ $orders->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <form method="GET" class="mb-8 grid gap-6 md:grid-cols-12 items-end bg-gray-50/50 p-6 rounded-3xl border border-white/50 backdrop-blur-md">
                <div class="md:col-span-2 space-y-2 text-left">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Show</label>
                    <div class="relative">
                        <select name="per_page" onchange="this.form.submit()"
                            class="w-full appearance-none rounded-xl border-0 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-indigo-500 transition-all">
                            @foreach ([10, 25, 50, 100] as $count)
                                <option value="{{ $count }}" {{ ($perPage ?? 10) == $count ? 'selected' : '' }}>
                                    {{ $count }} entries</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3 space-y-2 text-left">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Status</label>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()"
                            class="w-full appearance-none rounded-xl border-0 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">All Transactions</option>
                            @foreach ($orderStatuses as $statusOption)
                                <option value="{{ $statusOption }}"
                                    {{ ($statusFilter ?? '') === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-filter text-[10px] text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-5 space-y-2 text-left">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Search</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="search" name="search" value="{{ $search ?? '' }}"
                            placeholder="Enter order ID, product, or farmer"
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border-0 bg-white text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-indigo-500 transition-all" />
                    </div>
                </div>

                <div class="md:col-span-2 ">
                    <button type="submit"
                        class="px-6 py-2.5 h-[42px] bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                        Apply Filter
                    </button>
                </div>
            </form>

            @if ($orders->count() > 0)
                <div class="overflow-x-auto rounded-[2rem] border border-gray-50 bg-white/50">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Reference</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Product Content</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Value</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Progress</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-indigo-50/30 transition-all group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-900 group-hover:text-indigo-600 transition-colors">#{{ $order->id }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">{{ $order->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="relative group/img">
                                                @if (optional($order->product)->images && $order->product->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}"
                                                        alt="{{ $order->product->product_name ?? 'Product image' }}"
                                                        class="h-14 w-14 rounded-2xl object-cover border-2 border-white shadow-sm ring-1 ring-gray-100 group-hover/img:scale-105 transition-transform duration-300">
                                                @elseif(optional($order->product)->image)
                                                    <img src="{{ asset('storage/' . $order->product->image) }}"
                                                        alt="{{ $order->product->product_name ?? 'Product image' }}"
                                                        class="h-14 w-14 rounded-2xl object-cover border-2 border-white shadow-sm ring-1 ring-gray-100 group-hover/img:scale-105 transition-transform duration-300">
                                                @else
                                                    <div class="h-14 w-14 rounded-2xl bg-gray-50 flex items-center justify-center border-2 border-dashed border-gray-200">
                                                        <i class="fas fa-seedling text-gray-300 text-xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-900 leading-none">
                                                    {{ $order->product->product_name ?? 'N/A' }}</div>
                                                <div class="flex items-center gap-2 mt-1.5">
                                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $order->product->variety_size ?? 'Standard' }}</div>
                                                    <div class="w-1 h-1 rounded-full bg-gray-300"></div>
                                                    <div class="text-[10px] font-black text-indigo-500 uppercase tracking-tighter">{{ $order->final_quantity }} {{ $order->product->unit ?? 'Units' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap ">
                                        <div class="text-sm font-black text-gray-900">₱{{ number_format($order->total_amount, 2) }}</div>
                                        <div class="text-[10px] font-bold {{ $order->payment_status == 'Paid' ? 'text-green-500' : 'text-amber-500' }} mt-1 flex items-center gap-1.5 uppercase tracking-widest">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $order->payment_status == 'Paid' ? 'bg-green-500 ring-4 ring-green-100' : 'bg-amber-500 ring-4 ring-amber-100' }}"></span>
                                            {{ $order->payment_status }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex flex-col gap-1.5">
                                            <span class="px-3 py-1 text-[9px] font-black uppercase tracking-tight rounded-full border shadow-sm w-fit
                                                @if ($order->status == 'Ordered') bg-yellow-50 text-yellow-700 border-yellow-100
                                                @elseif($order->status == 'Accepted' || $order->status == 'Delivered') bg-emerald-50 text-emerald-700 border-emerald-100
                                                @elseif($order->status == 'Rejected') bg-red-50 text-red-700 border-red-100
                                                @elseif($order->status == 'Prepared') bg-blue-50 text-blue-700 border-blue-100
                                                @elseif($order->status == 'In Transit') bg-purple-50 text-purple-700 border-purple-100
                                                @else bg-gray-50 text-gray-700 border-gray-100 @endif">
                                                {{ $order->status }}
                                            </span>
                                            <span class="px-3 py-1 text-[9px] font-black uppercase tracking-tight rounded-full border border-gray-100 bg-gray-50 text-gray-400 shadow-sm w-fit">
                                                Logistics: {{ $order->delivery_status }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right overflow-visible">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('orders.show', ['transaction' => $order->id]) }}" 
                                                class="w-10 h-10 rounded-2xl bg-white text-blue-600 hover:text-white hover:bg-blue-600 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center border border-blue-100 shadow-sm group/btn"
                                                title="View Details">
                                                <i class="fas fa-eye text-sm group-hover/btn:scale-110 transition-transform"></i>
                                            </a>
                                            
                                            <div class="relative">
                                                <button type="button"
                                                    class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all duration-300 flex items-center justify-center border border-gray-200 shadow-sm"
                                                    id="buyer-order-actions-menu-button-{{ $order->id }}"
                                                    onclick="toggleOrderDropdown({{ $order->id }}, 'buyer')">
                                                    <i class="fas fa-ellipsis-h text-sm group-hover/action:scale-110 transition-transform"></i>
                                                </button>

                                                <div id="buyer-order-dropdown-menu-{{ $order->id }}"
                                                    class="hidden absolute right-0 mt-3 w-56 rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-[9999] overflow-hidden backdrop-blur-xl border border-gray-100 animate-in fade-in slide-in-from-top-2 duration-200">
                                                    <div class="py-2" role="none">
                                                        <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Order Operations</p>
                                                        </div>
                                                        <a href="{{ route('orders.show', ['transaction' => $order->id]) }}"
                                                            class="flex items-center gap-3 text-gray-700 px-4 py-3 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-700 transition-colors"
                                                            role="menuitem">
                                                            <div class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center">
                                                                <i class="fas fa-search-plus text-[10px]"></i>
                                                            </div>
                                                            Details Preview
                                                        </a>
                                                        <a href="{{ route('buyer.messages') }}?transaction_id={{ $order->id }}"
                                                            class="flex items-center gap-3 text-gray-700 px-4 py-3 text-xs font-bold hover:bg-blue-50 hover:text-blue-700 transition-colors border-t border-gray-50"
                                                            role="menuitem">
                                                            <div class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center">
                                                                <i class="fas fa-comments text-[10px]"></i>
                                                            </div>
                                                            Direct Channel
                                                        </a>

                                                        @if ($order->status == 'Ordered' && $order->payment_status == 'Pending')
                                                            <button type="button"
                                                                class="w-full flex items-center gap-3 text-gray-700 px-4 py-3 text-xs font-bold hover:bg-emerald-50 hover:text-emerald-700 transition-colors border-t border-gray-50 mark-paid-btn"
                                                                data-transaction-id="{{ $order->id }}">
                                                                <div class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center">
                                                                    <i class="fas fa-check-double text-[10px]"></i>
                                                                </div>
                                                                Signal Payment
                                                            </button>
                                                        @endif

                                                        @if ($order->delivery_status == 'In Transit' || $order->delivery_status == 'Prepared')
                                                            <button type="button"
                                                                class="w-full flex items-center gap-3 text-gray-700 px-4 py-3 text-xs font-bold hover:bg-amber-50 hover:text-amber-700 transition-colors border-t border-gray-50 mark-delivered-btn"
                                                                data-transaction-id="{{ $order->id }}">
                                                                <div class="w-6 h-6 rounded-lg bg-amber-50 flex items-center justify-center">
                                                                    <i class="fas fa-box-open text-[10px]"></i>
                                                                </div>
                                                                Finalize Delivery
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                        Showing <span class="text-gray-900">{{ $orders->firstItem() ?? 0 }}</span> - <span class="text-gray-900">{{ $orders->lastItem() ?? 0 }}</span> of <span class="text-gray-900">{{ $orders->total() }}</span> records
                    </p>
                    <div class="flex-shrink-0">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="relative py-24 px-8 text-center rounded-[3rem] bg-gray-50/50 border border-dashed border-gray-200">
                    <div class="absolute inset-0 bg-white/30 backdrop-blur-sm rounded-[3rem] -z-10"></div>
                    <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto mb-8 border border-gray-100">
                        <i class="fas fa-archive text-gray-200 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">No Active Orders</h3>
                    <p class="text-sm text-gray-500 font-medium max-w-sm mx-auto mb-10">Your purchase history is currently empty. Start sourcing fresh products from our local farmers today.</p>
                    <a href="{{ route('demands.index') }}"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:translate-y-0">
                        Explore Ecosystem
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>


    @vite('resources/js/buyer/orders/buyer-orders-index.js')
@endsection
