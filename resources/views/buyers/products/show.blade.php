@extends('layouts.buyers_page')

@section('content')

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="container mx-auto px-4 py-10 max-w-6xl">
            <div class="flex items-center justify-between text-center mb-5">

                <!-- Title -->
                <h1
                    class="text-4xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent leading-tight">
                    Product Details
                </h1>

                <!-- Back Button -->
                <div class="">
                    <a href="{{ route('buyer.dashboard') }}"
                        class="inline-flex items-center gap-2.5 text-sm font-medium text-gray-500 hover:text-green-700 transition-colors group">

                        <span
                            class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:border-green-400 group-hover:bg-green-50 transition-all duration-200">
                            <i class="fas fa-arrow-left text-xs text-gray-500 group-hover:text-green-600"></i>
                        </span>

                        <span class="group-hover:underline">Back to Marketplace</span>
                    </a>
                </div>

            </div>
            <!-- Main Card -->
            <div class="bg-white rounded-3xl shadow-2xl shadow-gray-200/70 overflow-hidden border border-gray-100">

                <!-- Top gradient accent bar -->
                <div class="h-1.5 w-full bg-gradient-to-r from-green-400 via-emerald-500 to-teal-400"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2">

                    <!-- ══ LEFT: Image Panel ══ -->
                    <div
                        class="p-8 lg:p-10 bg-gradient-to-br from-green-50 via-emerald-50 to-green-100 border-b lg:border-b-0 lg:border-r border-green-100">
                        <div class="lg:sticky lg:top-8">

                            <!-- Main Image -->
                            <div
                                class="relative rounded-2xl overflow-hidden bg-white shadow-lg border border-green-100 aspect-[4/3] cursor-zoom-in group">
                                @if ($product->images->count() > 0)
                                    <img id="mainImage" src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        alt="{{ $product->product_name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        onclick="openImageModal(this.src)">
                                @elseif($product->image)
                                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->product_name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        onclick="openImageModal(this.src)">
                                @else
                                    <div
                                        class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100">
                                        <i class="fas fa-seedling text-green-300 text-7xl mb-4"></i>
                                        <p class="text-green-500 font-medium text-sm">No Image Available</p>
                                    </div>
                                @endif

                                <!-- Zoom hint badge -->
                                <div
                                    class="absolute bottom-3 right-3 bg-black/40 text-white text-xs px-2.5 py-1 rounded-full flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                    <i class="fas fa-search-plus text-[10px]"></i> Zoom
                                </div>
                            </div>

                            <!-- Status & hint row -->
                            <div class="mt-4 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                        @if ($product->status == 'Available') bg-green-100 text-green-700 border border-green-200
                                        @elseif($product->status == 'Sold Out') bg-red-100 text-red-700 border border-red-200
                                        @else bg-amber-100 text-amber-700 border border-amber-200 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full animate-pulse
                                            @if ($product->status == 'Available') bg-green-500
                                            @elseif($product->status == 'Sold Out') bg-red-500
                                            @else bg-amber-500 @endif"></span>
                                    {{ $product->status }}
                                </span>
                                <span class="text-xs text-gray-400 italic">Click image to zoom</span>
                            </div>

                            <!-- Thumbnails -->
                            @if ($product->images->count() > 1)
                                <div class="mt-4 flex gap-2.5 flex-wrap">
                                    @foreach ($product->images as $index => $image)
                                        <button
                                            class="w-20 h-20 rounded-xl overflow-hidden border-2 transition-all duration-200 shadow-sm hover:border-green-500 focus:outline-none {{ $index === 0 ? 'border-green-500' : 'border-transparent' }}"
                                            onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ══ RIGHT: Info Panel ══ -->
                    <div class="p-8 lg:p-10 flex flex-col gap-6">

                        <!-- Label + Name -->
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-green-500 mb-1.5">Fresh from the Farm
                            </p>
                            <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
                                {{ $product->product_name }}
                            </h1>
                            <div class="mt-3 w-10 h-1 bg-gradient-to-r from-green-500 to-emerald-400 rounded-full"></div>
                        </div>

                        <!-- Price Block -->
                        <div
                            class="flex items-center justify-between bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl px-7 py-5 shadow-lg shadow-green-700/20">
                            <div>
                                <p class="text-green-200 text-xs font-semibold uppercase tracking-widest mb-1">Market Price
                                </p>
                                <div class="flex items-baseline gap-2">
                                    <span
                                        class="text-5xl font-black text-white">₱{{ number_format($product->price, 2) }}</span>
                                    <span class="text-green-300 text-sm font-medium">/ {{ $product->unit }}</span>
                                </div>
                            </div>
                            <div
                                class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center border border-white/20 hover:rotate-12 transition-transform   ">
                                <i class="fas fa-tag text-white text-2xl"></i>
                            </div>
                        </div>

                        <!-- Stat Cards -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                            @php
                                $varietySize = $product->variety_size;
                                $sizeOptions = ['small', 'medium', 'large'];
                                $isSize = $varietySize && in_array(strtolower($varietySize), $sizeOptions);
                            @endphp

                            <!-- Category -->
                            <div
                                class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex flex-col gap-2 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-default">
                                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-leaf text-blue-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Category</p>
                                    <p class="text-sm font-bold text-blue-700 mt-0.5 leading-tight">
                                        {{ $product->category ?: '—' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Variety / Size -->
                            <div
                                class="bg-purple-50 border border-purple-100 rounded-2xl p-4 flex flex-col gap-2 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-default">
                                <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-shapes text-purple-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-purple-400">
                                        {{ $isSize ? 'Size' : 'Variety' }}
                                    </p>
                                    <p class="text-sm font-bold text-purple-700 mt-0.5 leading-tight">
                                        {{ $varietySize ? ucfirst($varietySize) : '—' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Harvest Date -->
                            <div
                                class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex flex-col gap-2 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-default">
                                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-amber-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-amber-400">Harvested</p>
                                    <p class="text-sm font-bold text-amber-700 mt-0.5 leading-tight">
                                        {{ optional($product->harvest_date)->format('M d, Y') ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Availability -->
                            <div
                                class="bg-green-50 border border-green-100 rounded-2xl p-4 flex flex-col gap-2 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-default">
                                <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-cubes text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-green-500">Available</p>
                                    <p class="text-sm font-bold text-green-700 mt-0.5 leading-tight">
                                        {{ $product->quantity }} <span class="font-medium">{{ $product->unit }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="flex items-center gap-3 px-4 py-3.5 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="w-9 h-9 bg-gray-200 rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-map-marker-alt text-gray-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Farm Location</p>
                                <p class="text-sm font-semibold text-gray-700 mt-0.5">
                                    {{ collect([$product->purok_street, $product->barangay, $product->municipality_city, $product->province])->filter()->implode(', ') ?: 'Location not specified' }}
                                </p>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto pt-1">
                            @if ($product->status == 'Sold Out')
                                <button disabled
                                    class="w-full py-4 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 text-gray-400 text-base font-bold cursor-not-allowed flex items-center justify-center gap-2.5">
                                    <i class="fas fa-ban text-lg"></i> This Product Is Sold Out
                                </button>
                            @else
                                <form action="{{ route('buyer.message-farmer', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-4 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white text-base font-bold tracking-wide flex items-center justify-center gap-2.5 shadow-lg shadow-green-600/30 hover:shadow-xl hover:shadow-green-600/40 hover:-translate-y-0.5 transition-all duration-200">
                                        <i class="fas fa-comment-dots text-lg"></i>
                                        Message Farmer to Order
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- ══ BOTTOM: Description + Farmer Info ══ -->
                <div
                    class="border-t border-gray-100 px-8 lg:px-10 py-8 bg-gray-50/60 grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- Description -->
                    @if ($product->description)
                        <div>
                            <div class="flex items-center gap-2.5 mb-3">
                                <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-align-left text-green-600 text-xs"></i>
                                </div>
                                <h2 class="text-base font-bold text-gray-800">Product Description</h2>
                            </div>
                            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                                <p class="text-gray-600 leading-relaxed text-sm">{{ $product->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Farmer Info -->
                    <div>
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tractor text-green-600 text-xs"></i>
                            </div>
                            <h2 class="text-base font-bold text-gray-800">About the Farmer</h2>
                        </div>

                        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                            <div class="flex items-center gap-4 mb-5">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center shadow-md shrink-0">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-extrabold text-gray-900 text-base leading-tight">
                                        {{ $product->farmer->user->first_name ?? '' }}
                                        {{ $product->farmer->user->last_name ?? 'Unknown Farmer' }}
                                    </p>
                                    <p class="text-sm text-green-600 font-medium mt-0.5">
                                        {{ $product->farmer->farm_name ?? 'Independent Farm' }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                                    <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                        <i class="fas fa-phone text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Contact
                                        </p>
                                        <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                            {{ $product->farmer->user->phone_number ?? 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-xl border border-purple-100">
                                    <div class="w-8 h-8 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                                        <i class="fas fa-home text-purple-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-purple-400">Farm
                                            Address</p>
                                        <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                            {{ $product->farmer->farm_address ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Main Card -->

        </div>
    </div>

    <!-- ══ IMAGE MODAL ══ -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/85" onclick="closeImageModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <button onclick="closeImageModal()"
                class="absolute top-5 right-5 z-50 w-10 h-10 bg-white/10 hover:bg-white/25 rounded-full flex items-center justify-center text-white text-2xl font-bold transition-colors border border-white/20">
                &times;
            </button>
            <img id="modalImage" src="" alt="Expanded image"
                class="relative z-40 max-w-full max-h-[88vh] object-contain rounded-2xl shadow-2xl border border-white/10">
        </div>
    </div>

    @vite('resources/js/buyer/products/buyer-products-show.js')

@endsection