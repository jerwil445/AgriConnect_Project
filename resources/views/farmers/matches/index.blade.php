@extends('layouts.farmers_page')

@section('content')
    <div class="ml-64">
        <div
            class="flex flex-col sm:flex-row mt-4 sm:mt-5 justify-between items-start sm:items-center gap-3 mb-4 sm:mb-6 pb-4 sm:pb-6 border-b-2 border-green-200">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Product Matches</h1>
            <a href="{{ route('farmer.dashboard') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-green-800 border border-green-300 px-4 py-2 rounded-full hover:bg-green-200 transition-colors duration-200 whitespace-nowrap">
                &larr; Back to Dashboard
            </a>
        </div>

        <!-- Display notifications -->
        @if (auth()->user()->unreadNotifications->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 lg:p-4 mb-4 lg:mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 ">
                        <p class="text-sm text-yellow-700">
                            <strong>{{ auth()->user()->unreadNotifications->count() }}</strong> new notification(s)
                            <a href="{{ route('farmer.notifications') }}" class="font-medium underline">View all
                                notifications</a>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search, Filter, and Show Entries Controls --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
            <div class="px-4 lg:px-6 py-4 border-b border-gray-100 bg-gray-50">
                <form method="GET" action="{{ route('farmer.matches') }}" class="space-y-4">
                    <div class="flex flex-wrap items-end gap-2 lg:gap-4">
                        {{-- Search Box --}}
                        <div class="flex-1 min-w-[200px]">
                            <label for="search"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Search
                                Products or Buyers</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" id="search"
                                    placeholder="Search by name, variety, or buyer..." value="{{ request('search') }}"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div class="w-40">
                            <label for="status"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Product
                                Status</label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                <option value="">All Status</option>
                                <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available
                                </option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Sold Out" {{ request('status') == 'Sold Out' ? 'selected' : '' }}>Sold Out
                                </option>
                            </select>
                        </div>

                        {{-- Show Entries --}}
                        <div class="w-32">
                            <label for="per_page"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Show</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="9" {{ request('per_page') == '9' || !request('per_page') ? 'selected' : '' }}>9
                                    per page</option>
                                <option value="18" {{ request('per_page') == '18' ? 'selected' : '' }}>18 per page</option>
                                <option value="27" {{ request('per_page') == '27' ? 'selected' : '' }}>27 per page</option>
                                <option value="45" {{ request('per_page') == '45' ? 'selected' : '' }}>45 per page</option>
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 shadow-md shadow-green-200 transition-all duration-200">
                                <i class="fas fa-filter"></i>
                                <span class="hidden lg:inline">Filter</span>
                            </button>
                            <a href="{{ route('farmer.matches') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                                <i class="fas fa-undo"></i>
                                <span class="hidden lg:inline">Reset</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Results Summary --}}
            <div class="px-4 lg:px-6 py-3 bg-white border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest leading-loose">
                    @if ($products->total() > 0)
                        Showing <span class="text-gray-900">{{ $products->firstItem() }}</span> to <span
                            class="text-gray-900">{{ $products->lastItem() }}</span> of <span
                            class="text-gray-900">{{ $products->total() }}</span> Matched Products
                    @else
                        Found <span class="text-gray-900">0</span> products
                    @endif
                </p>
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center">
                <p class="text-gray-600">You don't have any products listed yet.</p>
                <a href="{{ route('farmer.products.create') }}"
                    class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Add Your First Product
                </a>
            </div>
        @else
            <!-- Grid layout for products -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div
                        class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                        <div class="relative bg-gray-100">
                            @if (optional($product->images)->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                    alt="{{ $product->product_name ?: $product->egg_type ?: 'Product image' }}"
                                    class="h-52 w-full object-cover">
                            @elseif($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->product_name ?: $product->egg_type ?: 'Product image' }}"
                                    class="h-52 w-full object-cover">
                            @else
                                <div class="h-52 w-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                                    <i class="fas fa-seedling text-green-300 text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute inset-x-0 top-0 flex justify-between items-center p-4">
                                <span class="font-bold text-xs uppercase tracking-wide rounded">
                                    @if ($product->status == 'Available')
                                        <span class="text-green-100 bg-green-600 px-3 py-1 rounded-md shadow-sm">Available</span>
                                    @elseif ($product->status == 'Pending')
                                        <span class="text-yellow-100 bg-yellow-600 px-3 py-1 rounded-md shadow-sm">Pending</span>
                                    @elseif ($product->status == 'Sold Out')
                                        <span class="text-red-100 bg-red-600 px-3 py-1 rounded-md shadow-sm">Sold Out</span>
                                    @else
                                        <span class="text-gray-100 bg-gray-600 px-3 py-1 rounded-md shadow-sm">{{ $product->status }}</span>
                                    @endif
                                </span>
                                <a href="{{ route('farmer.products.edit', $product) }}"
                                    class="rounded-full bg-white/90 px-3 py-2 text-xs font-semibold text-indigo-700 shadow-sm hover:bg-white">
                                    <i class="fas fa-edit mr-1"></i>Edit</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start gap-4">
                                <div class="min-w-0">
                                    <h2 class="text-xl font-bold text-gray-800 truncate">
                                        {{ $product->product_name ?: $product->egg_type ?: 'N/A' }}
                                    </h2>
                                    @if ($product->variety_size)
                                        <p class="mt-2 text-sm text-gray-500">Variety/Size: {{ $product->variety_size }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4 grid gap-2 sm:grid-cols-2">
                                <span
                                    class="inline-flex items-center justify-between rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-800">
                                    <span>Available</span>
                                    <span>{{ $product->quantity }} {{ $product->unit }}</span>
                                </span>
                                <span
                                    class="inline-flex items-center justify-between rounded-lg bg-green-50 px-3 py-2 text-xs font-semibold text-green-800">
                                    <span>Price</span>
                                    <span>₱{{ number_format($product->price, 2) }}/{{ $product->unit }}</span>
                                </span>
                                <span
                                    class="inline-flex items-center justify-between rounded-lg bg-yellow-50 px-3 py-2 text-xs font-semibold text-yellow-800">
                                    <span>Harvest</span>
                                    <span>{{ $product->harvest_date->format('M d, Y') }}</span>
                                </span>
                                <span
                                    class="inline-flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-700">
                                    <span>Matches</span>
                                    <span>{{ $product->matches->count() }}</span>
                                </span>
                            </div>
                            <div class="mt-5">
                                <div
                                    class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-400 hover:to-indigo-500 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex justify-center py-2 mb-2 items-center rounded-md">
                                    <a href="{{ route('farmer.product.matches', $product) }}"
                                        class="text-white  text-sm font-medium">
                                        View All {{ $product->matches->count() }} Matches &rarr;
                                    </a>
                                </div>
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-bold text-lg text-gray-800">Buyer Demands</h3>
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-xs font-bold">
                                        {{ $product->matches->count() }}
                                    </span>
                                </div>

                                @if ($product->matches->isEmpty())
                                    <div class="text-center py-4 bg-gray-50 rounded">
                                        <p class="text-gray-600">No buyer demands match this product yet.</p>
                                    </div>
                                @else
                                    <!-- Improved matches display -->
                                    <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                        @foreach ($product->matches->take(3) as $match)
                                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition">
                                                {{-- Buyer Name with Picture - Top --}}
                                                <div class="flex items-center gap-2 mb-2">
                                                    <img alt="{{ $match->demand->buyer->first_name ?? 'Buyer' }}"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($match->demand->buyer->first_name . ' ' . $match->demand->buyer->last_name) }}&background=e0e7ff&color=4f46e5&size=32"
                                                        class="w-7 h-7 rounded-full border border-gray-200">
                                                    <span
                                                        class="font-semibold text-sm text-gray-900">{{ $match->demand->buyer->first_name ?? '' }}
                                                        {{ $match->demand->buyer->last_name ?? '' }}</span>
                                                </div>

                                                {{-- Product Name with Status --}}
                                                <div class="flex items-start justify-between gap-3 mb-2">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            Product name: <span
                                                                class="font-bold text-indigo-700">{{ $match->demand->product_name ?? 'N/A' }}</span>
                                                        </p>
                                                    </div>
                                                    {{-- Status Badge --}}
                                                    <span
                                                        class="text-xs px-2 py-1 rounded shrink-0 whitespace-nowrap
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            @if ($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            @elseif($match->status == 'Matched') bg-green-100 text-green-800
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            @else bg-red-100 text-red-800 @endif">
                                                        {{ $match->status }}
                                                    </span>
                                                </div>

                                                {{-- Details --}}
                                                <div class="space-y-1 text-xs text-gray-600">
                                                    {{-- Variety/Size --}}
                                                    @if ($match->demand->variety_size)
                                                        <div>
                                                            <span class="text-gray-500">Variety/Size:</span>
                                                            <span class="font-medium text-gray-900">{{ $match->demand->variety_size }}</span>
                                                        </div>
                                                    @endif

                                                    {{-- Quantity --}}
                                                    <div>
                                                        <span class="text-gray-500">Quantity:</span>
                                                        <span class="font-medium text-gray-900">{{ $match->demand->quantity }}
                                                            {{ $match->demand->unit }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if ($product->matches->count() > 3)
                                            <div class="text-center pt-2">
                                                <a href="{{ route('farmer.product.matches', $product) }}"
                                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                    View All {{ $product->matches->count() }} Matches &rarr;
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Footer --}}
            @if ($products->hasPages())
                <div class="mt-8 mb-12">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection