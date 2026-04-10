@extends('layouts.buyers_page')

@section('content')
    <div class=" mx-auto px-4  buyer-content">
        <div class="">
            <div class="flex justify-between items-center mb-6 pb-6 border-b-2 border-green-200">
                <h1 class="text-3xl font-bold text-gray-800">Demand Details</h1>
                <a href="{{ route('demands.index') }}" class="text-indigo-600 hover:text-indigo-800">
                    &larr; Back to Demands
                </a>
            </div>

            {{-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-4">
                    <!-- Demand Info -->
                    <div class="lg:col-span-3 p-6 border-b lg:border-b-0 lg:border-r border-gray-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-box text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">
                                    {{ $demand->product_name ?: ($demand->egg_type ? ucfirst(str_replace('_', ' ', $demand->egg_type)) : 'Product') }}
                                </h2>
                                <p class="text-sm text-gray-500">Posted {{ $demand->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <!-- Variety/Size -->
                            <div class="bg-slate-50 p-4 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-tag text-gray-400"></i>
                                    <p class="text-sm text-gray-500">Variety/Size</p>
                                </div>
                                <p class="font-semibold text-gray-800 text-lg">
                                    @if ($demand->egg_size)
                                        {{ ucfirst(str_replace('_', ' ', $demand->egg_size)) }}
                                    @elseif ($demand->egg_category)
                                        {{ ucfirst(str_replace('_', ' ', $demand->egg_category)) }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>

                            <!-- Quantity -->
                            <div class="bg-slate-50 p-4 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-cubes text-gray-400"></i>
                                    <p class="text-sm text-gray-500">Quantity</p>
                                </div>
                                <p class="font-semibold text-gray-800 text-lg">{{ $demand->quantity }}
                                    {{ $demand->unit ?? 'units' }}</p>
                            </div>

                            <!-- Delivery Address -->
                            <div class="bg-slate-50 p-4 rounded-xl sm:col-span-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    <p class="text-sm text-gray-500">Delivery Address</p>
                                </div>
                                <p class="font-semibold text-gray-800">
                                    {{ collect([$demand->purok_street, $demand->barangay, $demand->municipality_city, $demand->province])->filter()->implode(', ') ?:'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Match Summary -->
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-pie text-emerald-600"></i> Match Summary
                        </h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Total</span>
                                <span class="text-2xl font-bold text-emerald-600">{{ $demand->matches->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">New</span>
                                <span
                                    class="text-xl font-bold text-blue-600">{{ $demand->matches->where('status', 'New')->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Matched</span>
                                <span
                                    class="text-xl font-bold text-green-600">{{ $demand->matches->where('status', 'Matched')->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Transaction</span>
                                <span
                                    class="text-xl font-bold text-indigo-600">{{ $demand->matches->where('status', 'Transaction Started')->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Sold Out</span>
                                <span
                                    class="text-xl font-bold text-red-600">{{ $demand->matches->where('status', 'Sold Out')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-6 items-start ">
                <div class=" flex flex-col bg-white border border-green-200 rounded-xl shadow-sm">
                    <!-- Match Summary -->
                    <div class=" p-6">
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-green-100">
                            <h2 class="text-2xl font-bold text-green-950">Matches Summary</h2>
                            {{-- <span class="bg-green-950 text-green-50 text-xs font-semibold px-3 py-1.5 rounded-full tracking-wide">
                        {{ $product->matches->count() }} Matches
                    </span> --}}
                        </div>
                        <div class=" flex items-center gap-8 justify-space-between w-full ">
                            <div
                                class="bg-green-50 border border-green-100 rounded-lg p-3 flex justify-between flex-col gap-2 items-center shadow-sm w-full text-center hover:-translate-y-0.5 transition-transform">
                                <span class="text-sm text-gray-400 uppercase">Total</span>
                                <span class="text-2xl font-bold text-emerald-600">{{ $demand->matches->count() }}</span>
                            </div>
                            <div
                                class="bg-sky-50 border border-sky-100 rounded-lg p-3 flex justify-between flex-col gap-2 items-center shadow-sm w-full text-center hover:-translate-y-0.5 transition-transform">
                                <span class="text-sm text-gray-400 uppercase">New</span>
                                <span
                                    class="text-2xl font-bold text-blue-600">{{ $demand->matches->where('status', 'New')->count() }}</span>
                            </div>
                            <div
                                class="bg-green-50 border border-green-100 rounded-lg p-3 flex justify-between flex-col gap-2 items-center shadow-sm  w-full text-center hover:-translate-y-0.5 transition-transform">
                                <span class="text-sm text-gray-400 uppercase">Matched</span>
                                <span
                                    class="text-2xl font-bold text-green-600">{{ $demand->matches->where('status', 'Matched')->count() }}</span>
                            </div>
                            <div
                                class="bg-violet-50 border border-violet-100 rounded-lg p-3 flex justify-between flex-col gap-2 items-center shadow-sm w-full text-center hover:-translate-y-0.5 transition-transform">
                                <span class="text-sm text-gray-400 uppercase">Transaction</span>
                                <span
                                    class="text-2xl font-bold text-indigo-600">{{ $demand->matches->where('status', 'Transaction Started')->count() }}</span>
                            </div>
                            <div
                                class="bg-purple-50 border border-purple-100 rounded-lg p-3 flex justify-between flex-col gap-2 items-center shadow-sm w-full text-center hover:-translate-y-0.5 transition-transform">
                                <span class="text-sm text-gray-400 uppercase">Ordered</span>
                                <span
                                    class="text-2xl font-bold text-purple-600">{{ $demand->matches->where('status', 'Ordered')->count() }}</span>
                            </div>
                            <div
                                class="bg-red-50 border border-red-100 rounded-lg p-3 flex justify-between flex-col gap-2 items-center shadow-sm w-full text-center hover:-translate-y-0.5 transition-transform">
                                <span class="text-sm text-gray-400 uppercase">Sold Out</span>
                                <span
                                    class="text-2xl font-bold text-red-600">{{ $demand->matches->where('status', 'Sold Out')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class=" p-6 ">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-green-100">
                            <h2 class="text-2xl font-bold text-gray-800">Product Matches</h2>
                            <span
                                class="bg-green-950 text-green-50 text-xs font-semibold px-3 py-1.5 rounded-full tracking-wide">
                                {{ $demand->matches->count() }} Matches Found
                            </span>
                        </div>

                        @if ($demand->matches->isEmpty())
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="mt-4 text-gray-600 font-medium">No matches found for this demand yet.</p>
                                <p class="text-gray-500 text-sm mt-2">The system will automatically find matches for your
                                    demand.
                                </p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 ">
                                @foreach ($demand->matches as $match)
                                    @if ($match->product && $match->product->farmer && $match->product->farmer->user)
                                        <div
                                            class="border border-green-400 bg-green-50/40 rounded-lg overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-200 relative">
                                            @php
                                                $matchProductImage =
                                                    $match->product->images->first()?->image_path ?:
                                                    $match->product->image;
                                            @endphp
                                            @if ($matchProductImage)
                                                <img src="{{ asset('storage/' . $matchProductImage) }}"
                                                    alt="{{ $match->product->product_name }}"
                                                    class="h-40 w-full object-cover">
                                            @else
                                                <div class="h-40 w-full flex items-center justify-center bg-emerald-50">
                                                    <i class="fas fa-image text-gray-300 text-3xl"></i>
                                                </div>
                                            @endif

                                            {{-- Status Badge - Top Right --}}
                                            <div class="absolute top-2 right-2">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full font-semibold
                                                @if ($match->status == 'Matched') bg-green-100 text-green-800
                                                @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($match->status == 'New') bg-blue-100 text-blue-800
                                                @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                                @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                                @else bg-red-100 text-red-800 @endif">
                                                    {{ $match->status }}
                                                </span>
                                            </div>

                                            <div class="p-4 ">
                                                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-green-200">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($match->product->farmer->user->first_name . ' ' . $match->product->farmer->user->last_name) }}&background=dcfce7&color=14532d&size=40"
                                                        alt="Farmer"
                                                        class="w-10 h-10 rounded-full object-cover border-2 border-green-300">
                                                    <div>
                                                        <p class="font-semibold text-gray-900 text-sm">
                                                            {{ $match->product->farmer->user->first_name ?? '' }}
                                                            {{ $match->product->farmer->user->last_name ?? '' }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">Farmer</p>
                                                    </div>
                                                </div>

                                                <h3 class="font-bold text-gray-900 mb-3">
                                                    {{ $match->product->product_name ?? 'N/A' }}</h3>

                                                <div class="space-y-2 text-sm divide-y divide-green-100">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-950">Price:</span>
                                                        <span
                                                            class="font-medium text-emerald-600">₱{{ number_format($match->product->price, 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-950">Unit:</span>
                                                        <span class="font-medium">{{ $match->product->unit }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-950">Harvest Date:</span>
                                                        <span
                                                            class="font-medium">{{ optional($match->product->harvest_date)->format('M d, Y') ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="flex flex-col justify-start items-start">
                                                        <span class="text-gray-950">Address:</span>
                                                        <span class="font-medium text-right">
                                                            {{ collect([$match->product->barangay, $match->product->municipality_city, $match->product->province])->filter()->implode(', ') ?:'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
                                                    <a href="{{ route('buyer.products.show', $match->product) }}"
                                                        class="flex-1 text-center text-xs font-medium py-2 rounded-lg bg-white border border-green-200 text-stone-600 hover:bg-green-50 transition-colors">
                                                        View Product
                                                    </a>
                                                    <button type="button"
                                                        onclick="openFarmerModal('{{ addslashes($match->product->farmer->user->first_name ?? '') }}', '{{ addslashes($match->product->farmer->user->last_name ?? '') }}', '{{ addslashes($match->product->farmer->user->email ?? '') }}', '{{ addslashes($match->product->farmer->user->phone_number ?? 'N/A') }}', '{{ addslashes($match->product->farmer->farm_name ?? 'N/A') }}', '{{ addslashes($match->product->farmer->user->business_type ?? 'N/A') }}', '{{ addslashes($match->product->farmer->user->address ?? 'N/A') }}')"
                                                        class="flex-1 text-center text-xs font-medium py-2 rounded-lg bg-sky-50 border border-sky-200 text-sky-700 hover:bg-sky-100 transition-colors">
                                                        Profile
                                                    </button>
                                                    {{-- @if ($match->product->status != 'Sold Out')
                                                        <form action="{{ route('matches.startConversation', $match) }}"
                                                            method="POST" class="flex-1">
                                                            @csrf
                                                            <button type="submit"
                                                                class="w-full text-center px-3 py-2 bg-emerald-600 text-white text-sm font-medium rounded hover:bg-emerald-700">
                                                                Message
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button disabled
                                                            class="flex-1 px-3 py-2 bg-gray-100 text-gray-400 text-sm font-medium rounded cursor-not-allowed">
                                                            Sold Out
                                                        </button>
                                                    @endif --}}
                                                </div>
                                                @if ($match->product->status != 'Sold Out')
                                                    <form action="{{ route('matches.startConversation', $match) }}"
                                                        method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit"
                                                            class="w-full text-center px-3 py-2 bg-green-950 text-white text-sm font-medium mt-2 rounded hover:bg-green-600">
                                                            Message
                                                        </button>
                                                    </form>
                                                @else
                                                    <button disabled
                                                        class="flex-1 px-3 py-2 bg-red-950 w-full text-white text-sm font-medium mt-2 rounded cursor-not-allowed hover:bg-red-600">
                                                        Sold Out
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>


                <div class=" rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="grid grid-cols-1 ">
                        <!-- Demand Info -->
                        <div class=" p-6 ">
                            <h1 class="text-2xl font-bold text-gray-900 pb-2 border-b-2 mb-2 border-green-200">Demand
                                Details
                            </h1>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-box text-emerald-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">
                                        {{ $demand->product_name ?: ($demand->egg_type ? ucfirst(str_replace('_', ' ', $demand->egg_type)) : 'Product') }}
                                    </h2>
                                    <p class="text-sm text-gray-500">Posted {{ $demand->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col space-y-0 divide-y divide-green-200 ">
                                <!-- Variety/Size -->
                                <div class="bg-slate-50 py-2  flex justify-between items-center">
                                    @php
                                        $varietyValue =
                                            $demand->variety_size ?: ($demand->egg_size ?: $demand->egg_category);
                                        $sizeOptions = ['Small', 'Medium', 'Large', 'Extra-Large', 'Jumbo'];
                                        $label = in_array($varietyValue, $sizeOptions) ? 'Size' : 'Variety';
                                    @endphp
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-tag text-green-700"></i>
                                        <p class="text-sm text-gray-500">{{ $label }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-800 text-xs">
                                        @if ($demand->egg_size)
                                            {{ ucfirst(str_replace('_', ' ', $demand->egg_size)) }}
                                        @elseif ($demand->egg_category)
                                            {{ ucfirst(str_replace('_', ' ', $demand->egg_category)) }}
                                        @elseif ($demand->variety_size)
                                            {{ $demand->variety_size }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>

                                <!-- Quantity -->
                                <div class="bg-slate-50 py-2  flex justify-between items-center">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-cubes text-green-700"></i>
                                        <p class="text-sm text-gray-500">Quantity</p>
                                    </div>
                                    <p class="font-semibold text-gray-800 text-xs">{{ $demand->quantity }}
                                        {{ $demand->unit ?? 'units' }}</p>
                                </div>

                                <!-- Delivery Date -->
                                <div class="bg-slate-50 py-2  flex justify-between items-center">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-calendar text-green-700"></i>
                                        <p class="text-sm text-gray-500">Delivery Date</p>
                                    </div>
                                    <p class="font-semibold text-gray-800 text-xs">
                                        {{ optional($demand->delivery_date)->format('M d, Y') ?? 'N/A' }}
                                    </p>
                                </div>

                                <!-- Deadline -->
                                <div class="bg-slate-50 py-2  flex justify-between items-center">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-hourglass-end text-green-700"></i>
                                        <p class="text-sm text-gray-500">Deadline</p>
                                    </div>
                                    <p class="font-semibold text-gray-800 text-xs">
                                        {{ $demand->deadline ?? 'N/A' }}
                                    </p>
                                </div>

                                <!-- Delivery Address -->
                                <div class="bg-slate-50 py-2  flex flex-col justify-start  ">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-map-marker-alt text-green-700"></i>
                                        <p class="text-sm text-gray-500">Delivery Address</p>
                                    </div>
                                    <p class="font-semibold text-gray-800 text-xs text-left">
                                        {{ collect([$demand->purok_street, $demand->barangay, $demand->municipality_city, $demand->province])->filter()->implode(', ') ?:'N/A' }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        {{-- <!-- Match Summary -->
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-pie text-emerald-600"></i> Match Summary
                        </h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Total</span>
                                <span class="text-2xl font-bold text-emerald-600">{{ $demand->matches->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">New</span>
                                <span
                                    class="text-xl font-bold text-blue-600">{{ $demand->matches->where('status', 'New')->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Matched</span>
                                <span
                                    class="text-xl font-bold text-green-600">{{ $demand->matches->where('status', 'Matched')->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Transaction</span>
                                <span
                                    class="text-xl font-bold text-indigo-600">{{ $demand->matches->where('status', 'Transaction Started')->count() }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 flex justify-between items-center shadow-sm">
                                <span class="text-sm text-gray-600">Sold Out</span>
                                <span
                                    class="text-xl font-bold text-red-600">{{ $demand->matches->where('status', 'Sold Out')->count() }}</span>
                            </div>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>        <!-- Farmer Profile Modal -->
        <div id="farmerProfileModal"
            class="fixed inset-0 bg-gray-900/40 backdrop-blur-md hidden items-center justify-center z-[100] p-4 transition-all duration-300 opacity-0">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden transform scale-95 transition-all duration-300 relative border border-white/20">
                
                {{-- Close Button --}}
                <button id="closeFarmerModalBtn"
                    class="absolute top-5 right-5 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-black/10 hover:bg-black/20 text-white backdrop-blur-md transition-all duration-200 group">
                    <i class="fas fa-times group-hover:rotate-90 transition-transform duration-300"></i>
                </button>

                {{-- Profile Cover --}}
                <div class="h-32 bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 relative overflow-hidden text-white">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                            <path d="M0 100 C 20 0 50 0 100 100 Z" />
                        </svg>
                    </div>
                </div>

                <div class="px-8 pb-10 -mt-12 relative">
                    {{-- Avatar --}}
                    <div class="mb-6 relative inline-block">
                        <div class="w-24 h-24 rounded-3xl bg-white p-1.5 shadow-2xl relative z-10 overflow-hidden ring-4 ring-white/10">
                            <img id="modal-avatar" src="https://ui-avatars.com/api/?name=Farmer&background=dcfce7&color=14532d&size=200&bold=true" 
                                alt="Avatar"
                                class="w-full h-full rounded-2xl object-cover border border-gray-100 bg-emerald-50">
                        </div>
                    </div>

                    <div class="mb-8 text-left">
                        <h3 id="modal-farmer-name" class="text-3xl font-black text-gray-900 tracking-tight leading-none mb-2"></h3>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black uppercase tracking-widest">
                                <i class="fas fa-tractor mr-1 animate-bounce"></i>
                                Verified Producer
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Personal Info Card --}}
                        <div class="p-5 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 group text-left">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-200 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                    <i class="fas fa-address-card text-xs"></i>
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

                        {{-- Farm Info Card --}}
                        <div class="p-5 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 group text-left">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-200 flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                    <i class="fas fa-seedling text-xs"></i>
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Agricultural Data</p>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Farm Enterprise</label>
                                    <p id="modal-farm-name" class="text-xs font-bold text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Product Category</label>
                                    <p id="modal-product-type" class="text-xs font-bold text-gray-900"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Address Card --}}
                        <div class="col-span-1 md:col-span-2 p-5 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 group text-left">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-200 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                    <i class="fas fa-map-location-dot text-xs"></i>
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Facility Location</p>
                            </div>
                            <div>
                                <p id="modal-farm-address" class="text-xs font-bold text-gray-900"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @vite('resources/js/buyer/demands/buyer-demands-show.js')
@endsection
