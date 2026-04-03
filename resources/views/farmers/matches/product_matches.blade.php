@extends('layouts.farmers_page')

@section('content')

    <div class="min-h-screen ml-64 mt-5">

        {{-- ── Header ── --}}
        <div class="mb-8">
            <div class="flex items-end justify-between pb-6 border-b-2 border-green-200">
                <div>
                    {{-- <p class="text-xs font-semibold tracking-widest text-green-600 uppercase mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.488 5.951 1.488a1 1 0 001.169-1.409l-7-14z" />
                        </svg>
                        Farmer Portal
                    </p> --}}
                    <h1 class="text-4xl font-bold text-green-950 leading-tight">
                        Matches for
                        <span class="bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            {{ $product->product_name ?: $product->egg_type ?: 'N/A' }}
                        </span>
                    </h1>
                </div>
                <a href="{{ route('farmer.matches') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-green-700 bg-white border border-green-200 px-5 py-2.5 rounded-full hover:bg-green-50 hover:border-green-300 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 5l-7 7 7 7" />
                    </svg>
                    Back to Matches
                </a>
            </div>
        </div>

        {{-- ── Notifications ── --}}
        @if (auth()->user()->unreadNotifications->count() > 0)
            <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-300 rounded-xl px-5 py-4 mb-5">
                <svg class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
                <p class="text-sm text-yellow-800">
                    <strong>{{ auth()->user()->unreadNotifications->count() }}</strong> unread notification(s).
                    <a href="{{ route('farmer.notifications') }}" class="font-semibold underline ml-1">View all</a>
                </p>
            </div>
        @endif

        @if (session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-300 rounded-xl px-5 py-4 mb-5">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4M22 12a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ── Main Grid ── --}}
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-6 items-start">

            {{-- ── Left: Buyer Demands panel (main content) ── --}}
            <div class="bg-white border border-green-200 rounded-2xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-green-100">
                    <h2 class="text-2xl font-bold text-green-950">Matches Summary</h2>
                    {{-- <span class="bg-green-950 text-green-50 text-xs font-semibold px-3 py-1.5 rounded-full tracking-wide">
                        {{ $product->matches->count() }} Matches
                    </span> --}}
                </div>
                <div class="grid grid-cols-4 gap-3 mb-6">

                    <div
                        class="bg-sky-50 border border-sky-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-sky-600">{{ $product->matches->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">Total</div>
                    </div>
                    <div
                        class="bg-green-50 border border-green-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $product->matches->where('status', 'Matched')->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">Ordered</div>
                    </div>
                    <div
                        class="bg-violet-50 border border-violet-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-violet-500">
                            {{ $product->matches->where('status', 'Transaction Started')->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">In Progress</div>
                    </div>
                    <div
                        class="bg-red-50 border border-red-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-red-400">
                            {{ $product->matches->where('status', 'Sold Out')->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">Sold Out</div>
                    </div>
                </div>
                {{-- Panel header --}}
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-green-100">
                    <h2 class="text-2xl font-bold text-green-950">Buyer Demands</h2>
                    <span class="bg-green-950 text-green-50 text-xs font-semibold px-3 py-1.5 rounded-full tracking-wide">
                        {{ $product->matches->count() }} Matches
                    </span>
                </div>

                {{-- ── Match Summary tiles ── --}}
                {{-- <div class="grid grid-cols-4 gap-3 mb-6">
                    <div
                        class="bg-sky-50 border border-sky-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-sky-600">{{ $product->matches->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">Total</div>
                    </div>
                    <div
                        class="bg-green-50 border border-green-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $product->matches->where('status', 'Matched')->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">Ordered</div>
                    </div>
                    <div
                        class="bg-violet-50 border border-violet-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-violet-500">
                            {{ $product->matches->where('status', 'Transaction Started')->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">In Progress</div>
                    </div>
                    <div
                        class="bg-red-50 border border-red-100 rounded-xl p-3 text-center hover:-translate-y-0.5 transition-transform">
                        <div class="text-2xl font-bold text-red-400">
                            {{ $product->matches->where('status', 'Sold Out')->count() }}</div>
                        <div class="text-xs text-stone-400 uppercase tracking-wider mt-1">Sold Out</div>
                    </div>
                </div> --}}

                {{-- ── Match cards ── --}}
                @if ($product->matches->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div
                            class="w-20 h-20 rounded-full bg-green-50 border-2 border-dashed border-green-300 flex items-center justify-center mb-5 text-green-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="M21 21l-4.35-4.35" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-950 mb-2">No matches yet</h3>
                        <p class="text-sm text-stone-400 max-w-xs">The system will automatically find matches for your
                            product once buyers post demands.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-5">
                        @foreach ($product->matches as $i => $match)
                            @if ($match->demand && $match->demand->buyer)
                                @php
                                    $eggTypes = [
                                        'chicken' => 'Chicken',
                                        'duck' => 'Duck',
                                        'quail' => 'Quail',
                                        'native_chicken' => 'Native Chicken',
                                        'brown' => 'Brown Egg',
                                        'white' => 'White Egg',
                                    ];
                                    $eggLabel =
                                        $eggTypes[$match->demand->egg_type] ??
                                        ucfirst(str_replace('_', ' ', $match->demand->egg_type));
                                    $badgeClass = match ($match->status) {
                                        'Matched' => 'bg-green-100 text-green-800',
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'New' => 'bg-blue-100 text-blue-800',
                                        'Transaction Started' => 'bg-violet-100 text-violet-800',
                                        'Ordered' => 'bg-pink-100 text-pink-800',
                                        default => 'bg-red-100 text-red-800',
                                    };
                                @endphp

                                <div
                                    class="relative border border-green-200 rounded-xl p-5 bg-green-50/40 hover:shadow-md hover:-translate-y-1 transition-all duration-200">

                                    {{-- Delete button --}}
                                    <form action="{{ route('matches.destroy', $match) }}" method="POST"
                                        class="absolute top-3.5 right-3.5">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Delete this match? This cannot be undone.')"
                                            class="text-stone-300 hover:text-red-500 hover:bg-red-50 p-1 rounded-md transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <path d="M18 6L6 18M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- Card header --}}
                                    <div class="flex justify-between items-start mb-4 pr-6">
                                        <div>
                                            <h3 class="font-bold text-base text-green-950">
                                                {{ $match->demand->product_name ?? 'N/A' }}</h3>
                                            <p class="text-xs text-stone-400 mt-0.5">
                                                {{ $match->demand->buyer->first_name ?? '' }}
                                                {{ $match->demand->buyer->last_name ?? '' }}</p>
                                        </div>
                                        <span
                                            class="text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide {{ $badgeClass }}">
                                            {{ $match->status }}
                                        </span>
                                    </div>

                                    {{-- Details --}}
                                    <div class="space-y-0 divide-y divide-green-100 mb-4">
                                        <div class="flex justify-between py-2 text-xs">
                                            <span class="text-stone-400">Product Name</span>
                                            <span
                                                class="font-medium text-green-950">{{ $match->demand->product_name ?? 'N/A' }}</span>
                                        </div>
                                        @if ($match->demand->variety_size)
                                            <div class="flex justify-between py-2 text-xs">
                                                <span class="text-stone-400">Variety/Size</span>
                                                <span
                                                    class="font-medium text-green-950">{{ $match->demand->variety_size }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between py-2 text-xs">
                                            <span class="text-stone-400">Quantity</span>
                                            <span class="font-medium text-green-950">{{ $match->demand->quantity }}
                                                {{ $match->demand->unit ?? 'units' }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 text-xs">
                                            <span class="text-stone-400">Delivery Date</span>
                                            <span
                                                class="font-medium text-green-950">{{ $match->demand->delivery_date->format('M d, Y') }}</span>
                                        </div>
                                        @if ($match->demand->deadline)
                                            <div class="flex justify-between py-2 text-xs">
                                                <span class="text-stone-400">Deadline</span>
                                                <span
                                                    class="font-medium text-green-950">{{ $match->demand->deadline }}</span>
                                            </div>
                                        @endif
                                        @php $addressParts = array_filter([$match->demand->purok_street, $match->demand->barangay, $match->demand->municipality_city, $match->demand->province]); @endphp
                                        @if (count($addressParts))
                                            <div class="flex justify-between py-2 text-xs">
                                                <span class="text-stone-400">Address</span>
                                                <span
                                                    class="font-medium text-green-950 text-right">{{ implode(', ', $addressParts) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Action buttons --}}
                                    <div class="flex gap-2 mb-2">
                                        <a href="{{ route('farmer.products.show', $match->product) }}"
                                            class="flex-1 text-center text-xs font-medium py-2 rounded-lg bg-white border border-green-200 text-stone-600 hover:bg-green-50 transition-colors">
                                            View Product
                                        </a>
                                        <button type="button"
                                            onclick="openBuyerModal({{ $match->demand->buyer->id ?? 0 }}, '{{ addslashes($match->demand->buyer->first_name ?? '') }}', '{{ addslashes($match->demand->buyer->last_name ?? '') }}', '{{ addslashes($match->demand->buyer->email ?? '') }}', '{{ addslashes($match->demand->buyer->phone_number ?? 'N/A') }}', '{{ addslashes($match->demand->buyer->company_name ?? 'N/A') }}', '{{ addslashes($match->demand->buyer->business_type ?? 'N/A') }}', '{{ addslashes($match->demand->buyer->address ?? 'N/A') }}')"
                                            class="flex-1 text-center text-xs font-medium py-2 rounded-lg bg-sky-50 border border-sky-200 text-sky-700 hover:bg-sky-100 transition-colors">
                                            Profile
                                        </button>
                                    </div>

                                    @if ($product->status == 'Sold Out')
                                        <button disabled
                                            class="w-full text-xs font-medium py-2 rounded-lg bg-stone-100 text-stone-400 cursor-not-allowed">
                                            Product Sold Out
                                        </button>
                                    @else
                                        <form action="{{ route('matches.startTransaction', $match) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-xs font-semibold py-2 rounded-lg bg-green-950 text-green-50 hover:bg-green-800 transition-colors duration-200">
                                                Message Buyer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Right sidebar: Product card only ── --}}
            <div class="xl:sticky xl:top-6">
                <div class="bg-white border border-green-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-start mb-5">
                        <h2 class="text-xl font-bold text-green-950 leading-tight">
                            {{ $product->product_name ?: $product->egg_type ?: 'N/A' }}
                        </h2>
                        @php
                            $statusColor = match ($product->status) {
                                'Available' => 'bg-green-100 text-green-800',
                                'Sold Out' => 'bg-red-100 text-red-800',
                                default => 'bg-yellow-100 text-yellow-800',
                            };
                        @endphp
                        <span
                            class="text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide {{ $statusColor }}">
                            {{ $product->status }}
                        </span>
                    </div>

                    {{-- Product Image --}}
                    @if ($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                            alt="{{ $product->product_name ?: 'Product image' }}"
                            class="w-full h-40 object-cover rounded-lg border border-green-100 mb-5">
                    @elseif ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->product_name ?: 'Product image' }}"
                            class="w-full h-40 object-cover rounded-lg border border-green-100 mb-5">
                    @else
                        <div
                            class="w-full h-40 rounded-lg border-2 border-dashed border-green-200 bg-green-50/30 flex items-center justify-center mb-5">
                            <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <div class="space-y-0 divide-y divide-green-100">
                        <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                            <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Quantity: <strong class="text-green-950 font-semibold">{{ $product->quantity }}
                                    {{ $product->unit }}</strong></span>
                        </div>

                        @if ($product->remainingInventory)
                            <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                                <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Remaining: <strong
                                        class="text-green-700 font-semibold">{{ $product->remainingInventory->remaining_quantity }}
                                        {{ $product->unit }}</strong></span>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                            <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Price: <strong
                                    class="text-green-950 font-semibold">₱{{ number_format($product->price, 2) }}/{{ $product->unit }}</strong></span>
                        </div>

                        @if ($product->variety_size)
                            <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                                <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M7 7h10M7 12h10M7 17h6" />
                                </svg>
                                <span>Variety/Size: <strong
                                        class="text-green-950 font-semibold">{{ $product->variety_size }}</strong></span>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                            <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Total: <strong
                                    class="text-green-950 font-semibold">₱{{ number_format((float) ($product->total_amount ?? 0), 2) }}</strong></span>
                        </div>

                        @if ($product->remainingInventory)
                            <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                                <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Remaining Total: <strong
                                        class="text-green-700 font-semibold">₱{{ number_format($product->remainingInventory->remaining_price, 2) }}</strong></span>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                            <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Harvest: <strong
                                    class="text-green-950 font-semibold">{{ $product->harvest_date->format('M d, Y') }}</strong></span>
                        </div>

                        <div class="flex items-center gap-3 py-3 text-sm text-stone-600">
                            <svg class="w-4 h-4 text-green-700 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Posted: <strong
                                    class="text-green-950 font-semibold">{{ $product->created_at->format('M d, Y H:i') }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Buyer Profile Modal ── --}}
    <div id="buyerProfileModal"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-md hidden items-center justify-center z-[100] p-4 transition-all duration-300 opacity-0">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden transform scale-95 transition-all duration-300 relative border border-white/20">
            
            {{-- Close Button --}}
            <button onclick="closeBuyerModal()"
                class="absolute top-5 right-5 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-black/10 hover:bg-black/20 text-white backdrop-blur-md transition-all duration-200 group">
                <i class="fas fa-times group-hover:rotate-90 transition-transform duration-300"></i>
            </button>

            {{-- Profile Cover --}}
            <div class="h-32 bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-600 relative overflow-hidden text-white">
                <div class="absolute inset-0 opacity-20">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                        <path d="M0 100 C 20 0 50 0 100 100 Z" />
                    </svg>
                </div>
            </div>

            <div class="px-8 pb-10 -mt-12 relative text-left">
                {{-- Avatar --}}
                <div class="mb-6 relative inline-block">
                    <div class="w-24 h-24 rounded-3xl bg-white p-1.5 shadow-2xl relative z-10 overflow-hidden ring-4 ring-white/10">
                        <img id="modal-avatar" src="https://ui-avatars.com/api/?name=Buyer&background=e0f2fe&color=0369a1&size=128" 
                            alt="Avatar"
                            class="w-full h-full rounded-2xl object-cover border border-gray-100 bg-sky-50">
                    </div>
                </div>

                <div class="mb-8">
                    <h3 id="modal-buyer-name" class="text-3xl font-black text-gray-900 tracking-tight leading-none mb-2"></h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-black uppercase tracking-widest">
                            <i class="fas fa-check-circle mr-1 animate-pulse"></i>
                            Verified Partner
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Contact Info Card --}}
                    <div class="p-5 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 group">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-200 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                <i class="fas fa-id-badge text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact Identity</p>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Primary Email</label>
                                <p id="modal-email" class="text-xs font-bold text-gray-900 break-all"></p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Mobile Number</label>
                                <p id="modal-phone" class="text-xs font-bold text-gray-900"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Business Info Card --}}
                    <div class="p-5 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 group">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-200 flex items-center justify-center text-sky-500 group-hover:bg-sky-500 group-hover:text-white transition-colors">
                                <i class="fas fa-building text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Business Identity</p>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Company Name</label>
                                <p id="modal-company" class="text-xs font-bold text-gray-900"></p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Business Type</label>
                                <p id="modal-business-type" class="text-xs font-bold text-gray-900"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Address Card --}}
                    <div class="col-span-1 md:col-span-2 p-5 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 group">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-200 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                <i class="fas fa-map-marked-alt text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Operational Location</p>
                        </div>
                        <div>
                            <p id="modal-address" class="text-xs font-bold text-gray-900"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBuyerModal(id, firstName, lastName, email, phone, company, businessType, address) {
            const fullName = firstName + ' ' + lastName;
            document.getElementById('modal-buyer-name').textContent = fullName;
            document.getElementById('modal-email').textContent = email;
            document.getElementById('modal-phone').textContent = phone;
            document.getElementById('modal-company').textContent = company;
            document.getElementById('modal-business-type').textContent = businessType;
            document.getElementById('modal-address').textContent = address;
            document.getElementById('modal-avatar').src =
                `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=e0f2fe&color=0369a1&size=200&bold=true`;

            const modal = document.getElementById('buyerProfileModal');
            const card = modal.querySelector('div');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Intersection Delay
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            });
            
            document.body.style.overflow = 'hidden';
        }

        function closeBuyerModal() {
            const modal = document.getElementById('buyerProfileModal');
            const card = modal.querySelector('div');
            
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        document.getElementById('buyerProfileModal').addEventListener('click', function(e) {
            if (e.target === this) closeBuyerModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeBuyerModal();
        });
    </script>

@endsection
