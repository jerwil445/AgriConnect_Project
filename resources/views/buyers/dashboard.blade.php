@extends('layouts.buyers_page')

@section('content')
<div class="container mx-auto px-4 py-6">
    @php
        $hasFilters = $search !== '' || $varietySize !== '' || $location !== '' || $statusFilter !== '' || $unitFilter !== '' || $sort !== 'latest' || $perPage !== 12;
    @endphp
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Available Products</h1>
                <p class="text-sm text-gray-500">Browse product listings from farmers.</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $products->total() }} listing{{ $products->total() === 1 ? '' : 's' }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('buyer.dashboard') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-7">
                <div>
                    <label for="search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search Products</label>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                        placeholder="Product or farmer"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label for="variety_size" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Variety/Size</label>
                    <input type="text" name="variety_size" id="variety_size" value="{{ $varietySize }}"
                        placeholder="Lakatan, large, grade A"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label for="location" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Location</label>
                    <input type="text" name="location" id="location" value="{{ $location }}"
                        placeholder="Barangay, city, or province"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label for="status" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
                    <select name="status" id="status" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">All Statuses</option>
                        @foreach(['Available', 'Sold Out'] as $statusOption)
                            <option value="{{ $statusOption }}" {{ $statusFilter === $statusOption ? 'selected' : '' }}>
                                {{ $statusOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="unit" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Unit</label>
                    <select name="unit" id="unit" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">All Units</option>
                        @foreach(['pieces', 'trays', 'dozen', 'kilos', 'boxes', 'bunches', 'sacks'] as $unitOption)
                            <option value="{{ $unitOption }}" {{ $unitFilter === $unitOption ? 'selected' : '' }}>
                                {{ ucfirst($unitOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Sort By</label>
                    <select name="sort" id="sort" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest First</option>
                        <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price Low-High</option>
                        <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price High-Low</option>
                        <option value="harvest_soon" {{ $sort === 'harvest_soon' ? 'selected' : '' }}>Harvest Soonest</option>
                    </select>
                </div>

                <div>
                    <label for="per_page" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Show</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                        @foreach([6, 12, 24, 48] as $entries)
                            <option value="{{ $entries }}" {{ $perPage === $entries ? 'selected' : '' }}>
                                {{ $entries }} per page
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="text-sm text-gray-500">
                    Showing <span class="font-semibold text-gray-700">{{ $products->firstItem() ?? 0 }}</span>
                    to <span class="font-semibold text-gray-700">{{ $products->lastItem() ?? 0 }}</span>
                    of <span class="font-semibold text-gray-700">{{ $products->total() }}</span> products
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                        Search
                    </button>
                    @if($hasFilters)
                        <a href="{{ route('buyer.dashboard') }}"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($products as $product)
            @php
                $remainingQuantity = $product->remainingInventory?->remaining_quantity ?? $product->quantity;
                $remainingAmount = $product->remainingInventory?->remaining_price ?? ($product->total_amount ?? ($product->quantity * $product->price));
            @endphp
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="h-48 bg-gray-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                            <i class="fas fa-image text-4xl text-green-500"></i>
                        </div>
                    @endif
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $product->product_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $product->variety_size ?: 'No variety/size specified' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full font-medium
                                @if($product->status == 'Available') bg-green-100 text-green-800
                                @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $product->status }}
                            </span>
                        </div>

                        @if($product->description)
                            <p class="text-sm text-gray-600 mt-3 line-clamp-2">{{ $product->description }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Quantity</p>
                            <p class="font-semibold text-gray-900">{{ $product->quantity }} {{ $product->unit }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Remaining</p>
                            <p class="font-semibold text-green-700">{{ $remainingQuantity }} {{ $product->unit }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Price per Unit</p>
                            <p class="font-semibold text-gray-900">₱{{ number_format($product->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Total Amount</p>
                            <p class="font-semibold text-gray-900">₱{{ number_format($product->total_amount ?? ($product->quantity * $product->price), 2) }}</p>
                        </div>
                    </div>

                    <div class="text-sm text-gray-500">
                        Remaining Total Amount: <span class="font-semibold text-green-700">₱{{ number_format($remainingAmount, 2) }}</span>
                    </div>

                    <div class="text-sm text-gray-500">
                        <p>{{ collect([$product->barangay, $product->municipality_city, $product->province])->filter()->implode(', ') ?: 'Location not provided' }}</p>
                        <p>Harvest Date: {{ optional($product->harvest_date)->format('M d, Y') ?? 'N/A' }}</p>
                    </div>

                    <a href="{{ route('buyer.products.show', $product) }}"
                        class="block w-full rounded-lg bg-green-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-green-500">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900">No products found</h3>
                @if($hasFilters)
                    <p class="text-sm text-gray-500 mt-1">No products matched your current search or filters.</p>
                    <a href="{{ route('buyer.dashboard') }}"
                        class="mt-4 inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Clear Filters
                    </a>
                @else
                    <p class="text-sm text-gray-500 mt-1">Check back later for new listings.</p>
                @endif
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
