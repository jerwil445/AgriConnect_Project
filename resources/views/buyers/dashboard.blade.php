@extends('layouts.buyers_page')

@section('content')

    <div class="container mx-auto px-4 py-6">
        <!-- Profile Button -->
        <!-- <div class="mb-6 flex justify-end">
            <a href="{{ route('buyer.profile') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                View Profile
            </a>
        </div> -->

        <!-- Page Header -->
        <!-- <div class="mb-8 flex  flex-col  text-center ">
            <h1 class="text-xl font-bold text-gray-800 mb-2">Agricultural Products</h1>
            
            <div>
                {{-- <h3 class="font-medium text-gray-700 mb-3">Search</h3> --}}
                <div class="relative">
                    <input type="text" placeholder="Product name..."
                        class="w-full pl-10 pr-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                    <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>
            <p class="text-gray-600 text-sm mt-1">Find the best agricultural products from trusted farmers and
                suppliers</p>
        </div> -->
        

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Browse Products</h1>
            <p class="text-gray-600">Discover fresh agricultural products from trusted farmers</p>
        </div>

        <!-- Results Header and Controls -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-box-open text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $products->count() }} <span class="text-lg font-normal text-gray-600">Products</span></h2>
                        <p class="text-gray-500 text-sm">{{ $products->total() }} total listings available</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="toggleFilters()" class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center space-x-2 hover:bg-green-700 transition-all shadow-sm hover:shadow-md">
                        <i class="fas fa-filter"></i>
                        <span>Filters</span>
                    </button>
                    <form method="GET" action="{{ route('buyer.dashboard') }}" class="flex items-center space-x-2">
                        <!-- Preserve existing filters -->
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="location" value="{{ $location }}">
                        <input type="hidden" name="category" value="{{ $category }}">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="min_price" value="{{ $minPrice }}">
                        <input type="hidden" name="max_price" value="{{ $maxPrice }}">
                        <input type="hidden" name="min_quantity" value="{{ $minQuantity }}">
                        <input type="hidden" name="max_quantity" value="{{ $maxQuantity }}">
                        @if($certification)
                            @foreach($certification as $cert)
                                <input type="hidden" name="certification[]" value="{{ $cert }}">
                            @endforeach
                        @endif
                        
                        <label class="text-gray-700 text-sm font-medium hidden md:block">Sort by:</label>
                        <select name="sort_by" onchange="this.form.submit()" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium bg-white hover:border-gray-400 transition">
                            <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="quantity" {{ $sortBy == 'quantity' ? 'selected' : '' }}>Quantity: High to Low</option>
                            <option value="harvest_date" {{ $sortBy == 'harvest_date' ? 'selected' : '' }}>Harvest Date</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <form method="GET" action="{{ route('buyer.dashboard') }}" id="filterForm">
        <div id="filtersContainer" class="hidden filters-container bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-sliders-h text-green-600 mr-2"></i>
                        Filter Products
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Refine your search to find exactly what you need</p>
                </div>
                <a href="{{ route('buyer.dashboard') }}"
                    class="text-green-600 hover:text-green-700 text-sm font-semibold flex items-center space-x-2 transition px-4 py-2 rounded-lg hover:bg-green-50">
                    <i class="fas fa-redo"></i>
                    <span>Clear All</span>
                </a>
            </div>
            
            <!-- Main Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search text-gray-400 mr-1"></i> Search Product
                    </label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by name..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                </div>

                <!-- Egg Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-egg text-gray-400 mr-1"></i> Egg Type
                    </label>
                    <select name="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="all">All Types</option>
                        @foreach($eggTypes as $eggType)
                            <option value="{{ $eggType }}" {{ $category == $eggType ? 'selected' : '' }}>{{ $eggType }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> Location
                    </label>
                    <input type="text" name="location" value="{{ $location }}" placeholder="City or region"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                </div>

                <!-- Availability -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle text-gray-400 mr-1"></i> Availability
                    </label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="all" {{ !request('status') || request('status') == 'all' ? 'selected' : '' }}>All Products</option>
                        <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available Only</option>
                    </select>
                </div>
            </div>

            <!-- Advanced Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                
                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-peso-sign text-gray-400 mr-1"></i> Price Range (₱)
                    </label>
                    <div class="flex space-x-2">
                        <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <span class="flex items-center text-gray-500">-</span>
                        <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    </div>
                </div>

                <!-- Quantity Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-boxes text-gray-400 mr-1"></i> Quantity Range
                    </label>
                    <div class="flex space-x-2">
                        <input type="number" name="min_quantity" value="{{ $minQuantity }}" placeholder="Min" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <span class="flex items-center text-gray-500">-</span>
                        <input type="number" name="max_quantity" value="{{ $maxQuantity }}" placeholder="Max" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    </div>
                </div>

                <!-- Certification -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-certificate text-gray-400 mr-1"></i> Certification
                    </label>
                    <div class="flex space-x-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="certification[]" value="Organic" 
                                {{ in_array('Organic', $certification ?? []) ? 'checked' : '' }}
                                class="rounded text-primary-500 mr-2 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Organic</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="certification[]" value="Non-GMO" 
                                {{ in_array('Non-GMO', $certification ?? []) ? 'checked' : '' }}
                                class="rounded text-primary-500 mr-2 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Non-GMO</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="submit"
                    class="flex-1 bg-primary-500 text-white py-3 rounded-lg hover:bg-primary-600 transition duration-200 font-medium flex items-center justify-center space-x-2">
                    <i class="fas fa-search"></i>
                    <span>Apply Filters</span>
                </button>
                <a href="{{ route('buyer.dashboard') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </div>
        </form>

        <!-- Product Listings -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="h-48 relative overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                            <i class="fas fa-image text-white text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3 right-3 flex justify-between items-start">
                        <span class="bg-white/95 backdrop-blur-sm text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                            <i class="fas fa-leaf mr-1"></i> Organic
                        </span>
                        <span class="bg-white/95 backdrop-blur-sm text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                            <i class="fas fa-star text-yellow-400 mr-1"></i> 4.8
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">{{ $product->product_name }}</h3>
                        @if($product->egg_type)
                            <div class="mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        @php
                                            $eggTypes = [
                                                'chicken' => 'Chicken Eggs',
                                                'duck' => 'Duck Eggs',
                                                'quail' => 'Quail Eggs',
                                                'native_chicken' => 'Native Chicken Eggs',
                                                'brown' => 'Brown Eggs',
                                                'white' => 'White Eggs'
                                            ];
                                        @endphp
                                        {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}
                                    </span>
                                </div>
                            @endif
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold 
                                @if($product->status == 'Available') bg-green-100 text-green-800 border border-green-200
                                @elseif($product->status == 'Sold Out') bg-red-100 text-red-800 border border-red-200
                                @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                                <i class="fas fa-circle text-[8px] mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                            </span>
                        </div>
                        <div class="flex items-start text-gray-600 text-sm">
                            <i class="fas fa-map-marker-alt text-green-600 mr-2 mt-0.5"></i>
                            <span class="line-clamp-1">{{ $product->farmer->user->address ?? 'Farm Location' }}, {{ $product->farmer->user->state ?? 'State' }}</span>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-1">Price</p>
                                <p class="text-green-600 font-bold text-2xl">₱{{ number_format($product->price, 2) }}</p>
                                <p class="text-gray-500 text-sm">per {{ $product->unit }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-1">Available</p>
                                <p class="text-gray-900 font-bold text-xl">{{ $product->quantity }}</p>
                                <p class="text-gray-500 text-sm">{{ $product->unit }}</p>
                            </div>
                        </div>
                            
                        <!-- Egg Sizes -->
                        @if($product->sizes && $product->sizes->count() > 0)
                        <div class="mb-3">
                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-2">Available Sizes</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($product->sizes as $size)
                                    @php
                                        $sizeLabels = [
                                            'small' => 'S',
                                            'medium' => 'M',
                                            'large' => 'L',
                                            'extra_large' => 'XL',
                                            'jumbo' => 'XXL'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $sizeLabels[$size->size_name] ?? ucfirst(str_replace('_', ' ', $size->size_name)) }}: {{ $size->tray_count }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                    Harvest Date
                                </span>
                                <span class="text-gray-900 font-semibold text-sm">{{ $product->harvest_date?->format('M d, Y') ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($product->status == 'Sold Out')
                    <button
                        class="w-full bg-gray-200 text-gray-500 py-3 rounded-lg cursor-not-allowed font-semibold flex items-center justify-center space-x-2 border border-gray-300" disabled>
                        <i class="fas fa-times-circle"></i>
                        <span>Sold Out</span>
                    </button>
                    @else
                    <a href="{{ route('buyer.products.show', $product) }}"
                        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-all duration-200 font-semibold flex items-center justify-center space-x-2 shadow-sm hover:shadow-md">
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="bg-gray-50 rounded-2xl p-12 border-2 border-dashed border-gray-300">
                    <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No products found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or check back later for new listings.</p>
                    <a href="{{ route('buyer.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-redo mr-2"></i>
                        Clear Filters
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Modern Pagination -->
        @if($products->hasPages())
        <div class="mt-8 mb-8 pagination-container">
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Pagination Info -->
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-semibold text-gray-800">{{ $products->firstItem() }}</span> 
                        to <span class="font-semibold text-gray-800">{{ $products->lastItem() }}</span> 
                        of <span class="font-semibold text-gray-800">{{ $products->total() }}</span> products
                    </div>
                    
                    <!-- Pagination Links -->
                    <nav class="flex items-center space-x-2" aria-label="Pagination">
                        {{-- Previous Button --}}
                        @if ($products->onFirstPage())
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" 
                               class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max($products->currentPage() - 2, 1);
                            $end = min($start + 4, $products->lastPage());
                            $start = max($end - 4, 1);
                        @endphp

                        @if($start > 1)
                            <a href="{{ $products->url(1) }}" 
                               class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                                1
                            </a>
                            @if($start > 2)
                                <span class="px-2 text-gray-500">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $products->currentPage())
                                <span class="px-4 py-2 text-white bg-primary-500 rounded-lg font-semibold shadow-md">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ $products->url($i) }}" 
                                   class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 transition duration-200">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor

                        @if($end < $products->lastPage())
                            @if($end < $products->lastPage() - 1)
                                <span class="px-2 text-gray-500">...</span>
                            @endif
                            <a href="{{ $products->url($products->lastPage()) }}" 
                               class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                                {{ $products->lastPage() }}
                            </a>
                        @endif

                        {{-- Next Button --}}
                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" 
                               class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </nav>

                    <!-- Quick Jump (Desktop only) -->
                    <div class="hidden lg:flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Go to:</span>
                        <form method="GET" action="{{ route('buyer.dashboard') }}" class="flex items-center space-x-2">
                            @foreach(request()->except('page') as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $item)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <input type="number" 
                                   name="page" 
                                   min="1" 
                                   max="{{ $products->lastPage() }}" 
                                   value="{{ $products->currentPage() }}"
                                   class="w-16 px-2 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <button type="submit" 
                                    class="px-3 py-1 text-sm bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition duration-200">
                                Go
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-green-700 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-leaf text-primary-800 text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold">AgriConnect</h3>
                    </div>
                    <p class="text-primary-200 text-sm">Connecting farmers and buyers in a modern agricultural
                        marketplace.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Marketplace</h4>
                    <ul class="space-y-2 text-primary-200 text-sm">
                        <li><a href="#" class="hover:text-white transition duration-200">All Products</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Farm Directory</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Seasonal Offers</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition duration-200">Premium Listings</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2 text-primary-200 text-sm">
                        <li><a href="#" class="hover:text-white transition duration-200">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Farmers Guide</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Buyer Resources</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Subscribe</h4>
                    <p class="text-primary-200 text-sm mb-3">Get the latest updates on new products and offers.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email"
                            class="px-3 py-2 rounded-l-lg text-gray-800 w-full focus:outline-none">
                        <button
                            class="bg-primary-500 px-4 py-2 rounded-r-lg hover:bg-primary-600 transition duration-200">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-primary-700 mt-8 pt-6 text-center text-primary-300 text-sm">
                <p>© 2023 AgriConnect. All rights reserved.</p>
            </div>
        </div>
    </footer>
@endsection

@section('scripts')
<script>
    // Toggle filters visibility with smooth animation
    function toggleFilters() {
        const filtersContainer = document.getElementById('filtersContainer');
        const isHidden = filtersContainer.classList.contains('hidden');
        
        if (isHidden) {
            filtersContainer.classList.remove('hidden');
            // Add smooth slide down animation
            setTimeout(() => {
                filtersContainer.style.opacity = '1';
                filtersContainer.style.transform = 'translateY(0)';
            }, 10);
        } else {
            filtersContainer.style.opacity = '0';
            filtersContainer.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                filtersContainer.classList.add('hidden');
            }, 200);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const filtersContainer = document.getElementById('filtersContainer');
        
        // Add transition styles
        filtersContainer.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        filtersContainer.style.opacity = '0';
        filtersContainer.style.transform = 'translateY(-10px)';
        
        // Show filters if any filter is active
        const hasActiveFilters = {{ 
            ($search || $location || $category != 'all' || $status != 'all' || 
             $minPrice || $maxPrice || $minQuantity || $maxQuantity || 
             !empty($certification)) ? 'true' : 'false' 
        }};
        
        if (hasActiveFilters) {
            toggleFilters();
        }
    });
</script>
@endsection
