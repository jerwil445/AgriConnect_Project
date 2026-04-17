@php
    $isFarmer = auth()->user()->farmer ? true : false;
@endphp

@extends($isFarmer ? 'layouts.farmers_page' : 'layouts.buyers_page')

@section('content')

    <div class="min-h-screen  {{ $isFarmer ? 'ml-64' : '' }}">
        <div class="container mx-auto px-4 py-10 max-w-5xl">

            <!-- Back Nav -->
            <div class="mb-8">
                <a href="{{ $isFarmer ? route('farmer.orders') : route('buyer.orders') }}"
                    class="inline-flex items-center gap-2.5 text-sm font-medium text-gray-500 hover:text-green-700 transition-colors group">
                    <span
                        class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:border-green-400 group-hover:bg-green-50 transition-all duration-200">
                        <i class="fas fa-arrow-left text-xs text-gray-500 group-hover:text-green-600"></i>
                    </span>
                    <span class="group-hover:underline">Back to Orders</span>
                </a>
            </div>

            <!-- Page Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-green-500 mb-1">Transaction</p>
                    <h1 class="text-3xl font-extrabold text-gray-900">Order <span
                            class="text-green-600">#{{ $order->id }}</span></h1>
                </div>
                <!-- Overall Status Badge -->
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold border
                @if ($order->status == 'Accepted') bg-green-100 text-green-700 border-green-200
                @elseif($order->status == 'Ordered') bg-amber-100 text-amber-700 border-amber-200
                @elseif($order->status == 'Rejected') bg-red-100 text-red-700 border-red-200
                @else bg-blue-100 text-blue-700 border-blue-200 @endif">
                    <span
                        class="w-2 h-2 rounded-full animate-pulse
                    @if ($order->status == 'Accepted') bg-green-500
                    @elseif($order->status == 'Ordered') bg-amber-500
                    @elseif($order->status == 'Rejected') bg-red-500
                    @else bg-blue-500 @endif"></span>
                    {{ $order->status }}
                </span>
            </div>

            <!-- Status Strip -->
            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 px-6 py-4 grid grid-cols-3 divide-x divide-gray-100">
                <!-- Payment Status -->
                <div class="pr-6 flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                    @if ($order->payment_status == 'Paid') bg-green-100 @else bg-amber-100 @endif">
                        <i
                            class="fas fa-money-bill-wave text-sm
                        @if ($order->payment_status == 'Paid') text-green-600 @else text-amber-600 @endif"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Payment</p>
                        <p
                            class="text-sm font-bold
                        @if ($order->payment_status == 'Paid') text-green-600 @else text-amber-600 @endif">
                            {{ $order->payment_status }}
                        </p>
                    </div>
                </div>
                <!-- Delivery Status -->
                <div class="px-6 flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                    @if ($order->delivery_status == 'Delivered') bg-green-100
                    @elseif($order->delivery_status == 'In Transit') bg-blue-100
                    @else bg-gray-100 @endif">
                        <i
                            class="fas fa-truck text-sm
                        @if ($order->delivery_status == 'Delivered') text-green-600
                        @elseif($order->delivery_status == 'In Transit') text-blue-600
                        @else text-gray-500 @endif"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Delivery</p>
                        <p
                            class="text-sm font-bold
                        @if ($order->delivery_status == 'Delivered') text-green-600
                        @elseif($order->delivery_status == 'In Transit') text-blue-600
                        @else text-gray-600 @endif">
                            {{ $order->delivery_status ?? 'Pending' }}
                        </p>
                    </div>
                </div>
                <!-- Order Date -->
                <div class="pl-6 flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-calendar-alt text-slate-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Order Date</p>
                        <p class="text-sm font-bold text-gray-700">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

                <!-- Product Info -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:col-span-2">
                    <div class="flex items-center gap-2.5 mb-5">
                        <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-box text-green-600 text-xs"></i>
                        </div>
                        <h2 class="text-base font-bold text-gray-800">Product Information</h2>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start gap-5">
                        <!-- Product Image -->
                        <div
                            class="w-28 h-28 rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 shrink-0 shadow-sm">
                            @if (optional($order->product)->images && $order->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}"
                                    alt="{{ $order->product->product_name }}" class="h-full w-full object-cover">
                            @elseif(optional($order->product)->image)
                                <img src="{{ asset('storage/' . $order->product->image) }}"
                                    alt="{{ $order->product->product_name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-green-50 flex items-center justify-center">
                                    <i class="fas fa-seedling text-green-300 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="flex-1 w-full">
                            <p class="text-xl font-extrabold text-gray-900 mb-1">{{ $order->product->product_name }}</p>
                            <p class="text-sm text-gray-400 mb-4">
                                {{ $order->product->variety_size ?: 'No variety/size specified' }}</p>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Quantity
                                    </p>
                                    <p class="text-base font-bold text-gray-800">{{ $order->final_quantity }} <span
                                            class="text-sm font-medium text-gray-500">{{ $order->product->unit }}</span>
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Unit
                                        Price</p>
                                    <p class="text-base font-bold text-gray-800">
                                        ₱{{ number_format($order->final_price, 2) }}<span
                                            class="text-xs font-medium text-gray-500">/{{ $order->product->unit }}</span>
                                    </p>
                                </div>
                                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-green-200 mb-0.5">Total
                                        Amount</p>
                                    <p class="text-base font-black text-white">
                                        ₱{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buyer Info -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center gap-2.5 mb-5">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xs"></i>
                        </div>
                        <h2 class="text-base font-bold text-gray-800">Buyer Information</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-id-card text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Full Name</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $order->buyer->first_name }}
                                    {{ $order->buyer->last_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-envelope text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Email</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $order->buyer->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-phone text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Phone</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $order->buyer_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-map-marker-alt text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Address</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $order->buyer_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Farmer Info -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center gap-2.5 mb-5">
                        <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-tractor text-green-600 text-xs"></i>
                        </div>
                        <h2 class="text-base font-bold text-gray-800">Farmer Information</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-7 h-7 bg-green-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-id-card text-green-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Full Name</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $order->farmer->first_name }}
                                    {{ $order->farmer->last_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-7 h-7 bg-green-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-envelope text-green-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Email</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $order->farmer->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if (auth()->user()->buyer || auth()->user()->farmer)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center gap-2.5 mb-5">
                        <div class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bolt text-slate-500 text-xs"></i>
                        </div>
                        <h2 class="text-base font-bold text-gray-800">Actions</h2>
                    </div>
                    <div class="flex flex-wrap gap-3">

                        @if (auth()->user()->buyer)
                            <a href="{{ route('buyer.messages') }}?transaction_id={{ $order->id }}"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-400 hover:to-indigo-500 shadow-md shadow-indigo-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-comment-dots"></i> Chat with Farmer
                            </a>
                        @endif

                        @if (auth()->user()->farmer)
                            <a href="{{ route('farmer.messages') }}?transaction_id={{ $order->id }}"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-400 hover:to-indigo-500 shadow-md shadow-indigo-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-comment-dots"></i> Chat with Buyer
                            </a>
                        @endif

                        @if (auth()->user()->farmer && $order->status == 'Ordered')
                            <button type="button"
                                class="accept-order-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 shadow-md shadow-green-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                data-transaction-id="{{ $order->id }}">
                                <i class="fas fa-check"></i> Accept Order
                            </button>
                            <button type="button"
                                class="reject-order-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-400 hover:to-rose-500 shadow-md shadow-red-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                data-transaction-id="{{ $order->id }}">
                                <i class="fas fa-times"></i> Reject Order
                            </button>
                        @endif

                        @if (auth()->user()->buyer && $order->payment_status != 'Paid' && $order->status == 'Accepted')
                            <button type="button"
                                class="mark-paid-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 shadow-md shadow-green-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                data-transaction-id="{{ $order->id }}">
                                <i class="fas fa-money-bill-wave"></i> Mark as Paid
                            </button>
                        @endif

                        @if (auth()->user()->farmer && $order->status == 'Accepted')
                            <button type="button"
                                class="mark-prepared-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-400 hover:to-blue-500 shadow-md shadow-blue-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                data-transaction-id="{{ $order->id }}">
                                <i class="fas fa-box-open"></i> Mark as Prepared
                            </button>
                            <button type="button"
                                class="assign-logistics-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-400 hover:to-purple-500 shadow-md shadow-purple-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                data-transaction-id="{{ $order->id }}">
                                <i class="fas fa-truck"></i> Assign Logistics
                            </button>
                        @endif

                        @if (auth()->user()->buyer && ($order->delivery_status == 'In Transit' || $order->delivery_status == 'Prepared'))
                            <button type="button"
                                class="mark-delivered-by-buyer-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-400 hover:to-teal-500 shadow-md shadow-teal-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                data-transaction-id="{{ $order->id }}">
                                <i class="fas fa-check-circle"></i> Mark as Delivered
                            </button>
                        @endif

                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        document.body.dataset.ordersRedirectUrl = '{{ $isFarmer ? route('farmer.orders') : route('buyer.orders') }}';
    </script>
    @vite('resources/js/orders/orders-show.js')


@endsection
