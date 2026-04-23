@extends('layouts.buyers_page')

@section('content')
    <div class="container mx-auto px-4 py-6">
        @php
            $hasFilters =
                $search !== '' ||
                $varietySize !== '' ||
                $location !== '' ||
                $statusFilter !== '' ||
                $unitFilter !== '' ||
                $sort !== 'latest' ||
                $perPage !== 12;
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

        <!-- Mobile Filter Toggle Button -->
        <div class="md:hidden mb-4">
            <button id="filter-toggle-btn" type="button"
                class="w-full flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-filter"></i>
                Filters
                @if ($hasFilters)
                    <span
                        class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-green-600 rounded-full">
                        {{ collect([$search, $varietySize, $location, $statusFilter, $unitFilter])->filter()->count() + ($sort !== 'latest' ? 1 : 0) + ($perPage !== 12 ? 1 : 0) }}
                    </span>
                @endif
            </button>
        </div>

        <!-- Active Filters Chips -->
        @if ($hasFilters)
            <div class="flex flex-wrap gap-2 mb-4">
                @if ($search)
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                        Search: {{ $search }}
                        <a href="{{ request()->fullUrlWithQuery(array_diff_key(request()->query(), ['search' => ''])) }}"
                            class="ml-1 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if ($varietySize)
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                        Variety: {{ $varietySize }}
                        <a href="{{ request()->fullUrlWithQuery(array_diff_key(request()->query(), ['variety_size' => ''])) }}"
                            class="ml-1 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if ($location)
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                        Location: {{ $location }}
                        <a href="{{ request()->fullUrlWithQuery(array_diff_key(request()->query(), ['location' => ''])) }}"
                            class="ml-1 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if ($statusFilter)
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                        Status: {{ $statusFilter }}
                        <a href="{{ request()->fullUrlWithQuery(array_diff_key(request()->query(), ['status' => ''])) }}"
                            class="ml-1 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if ($unitFilter)
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                        Unit: {{ $unitFilter }}
                        <a href="{{ request()->fullUrlWithQuery(array_diff_key(request()->query(), ['unit' => ''])) }}"
                            class="ml-1 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                <a href="{{ route('buyer.dashboard') }}" class="text-sm text-gray-500 underline hover:text-gray-700">
                    Clear all
                </a>
            </div>
        @endif

        <!-- Filter Form -->
        <div id="filter-panel" class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <form id="filter-form" method="GET" action="{{ route('buyer.dashboard') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-7">
                    <div>
                        <label for="search" class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Search
                            Products</label>
                        <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Product or farmer"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="variety_size"
                            class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Variety/Size</label>
                        <input type="text" name="variety_size" id="variety_size" value="{{ $varietySize }}"
                            placeholder="Lakatan, large, grade A"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="location"
                            class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Location</label>
                        <input type="text" name="location" id="location" value="{{ $location }}"
                            placeholder="Barangay, city, or province"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="status"
                            class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
                        <select name="status" id="status"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">All Statuses</option>
                            @foreach (['Available'] as $statusOption)
                                <option value="{{ $statusOption }}" {{ $statusFilter === $statusOption ? 'selected' : '' }}>
                                    {{ $statusOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="unit"
                            class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Unit</label>
                        <select name="unit" id="unit"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">All Units</option>
                            @foreach (['pieces', 'trays', 'dozen', 'kilos', 'boxes', 'bunches', 'sacks'] as $unitOption)
                                <option value="{{ $unitOption }}" {{ $unitFilter === $unitOption ? 'selected' : '' }}>
                                    {{ ucfirst($unitOption) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Sort
                            By</label>
                        <select name="sort" id="sort"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price Low-High</option>
                            <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price High-Low
                            </option>
                            <option value="harvest_soon" {{ $sort === 'harvest_soon' ? 'selected' : '' }}>Harvest Soonest
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="per_page"
                            class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Show</label>
                        <select name="per_page" id="per_page"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            @foreach ([6, 12, 24, 48] as $entries)
                                <option value="{{ $entries }}" {{ $perPage === $entries ? 'selected' : '' }}>
                                    {{ $entries }} per page
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-end">
                    <div class="flex gap-2">
                        <button type="submit"
                            class="rounded-lg bg-green-600 px-6 py-2 text-sm font-bold text-white shadow-md shadow-green-100 hover:bg-green-700 transition-all duration-200">
                            Apply Filters
                        </button>
                        @if ($hasFilters)
                            <a href="{{ route('buyer.dashboard') }}"
                                class="rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition-all duration-200">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Dynamic Product List Container --}}
        <div id="product-list-container" class="relative transition-opacity duration-300">
            @include('buyers.partials.product_grid')
        </div>
    </div>
    </div>

    @vite('resources/js/buyer/buyer-dashboard.js')
@endsection