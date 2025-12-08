@extends('layouts.buyers_page')

@section('content')

    <div class="container mx-auto px-4 py-2">
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
        

        <!-- Results Header and Controls -->
        <div class="bg-white rounded-xl shadow-sm p-3 mb-2 animate-slide-up">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Showing {{ $products->count() }} results</h2>
                        <p class="text-gray-600 mt-1">From {{ $products->total() }} listings available</p>
                    </div>
                    <button onclick="toggleFilters()" class="lg:hidden bg-primary-500 text-white px-4 py-2 rounded-lg text-sm flex items-center space-x-2">
                        <i class="fas fa-filter"></i>
                        <span>Filters</span>
                    </button>
                </div>
                <div class="flex items-center space-x-2 mt-2 md:mt-0">
                    <form method="GET" action="{{ route('buyer.dashboard') }}" class="flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-lg">
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
                        
                        <span class="text-gray-600 text-sm">Sort by:</span>
                        <select name="sort_by" onchange="this.form.submit()" class="bg-transparent focus:outline-none text-gray-800">
                            <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }} class="text-sm">Newest First</option>
                            <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }} class="text-sm">Price: Low to High</option>
                            <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }} class="text-sm">Price: High to Low</option>
                            <option value="quantity" {{ $sortBy == 'quantity' ? 'selected' : '' }} class="text-sm">Quantity: High to Low</option>
                            <option value="harvest_date" {{ $sortBy == 'harvest_date' ? 'selected' : '' }} class="text-sm">Harvest Date</option>
                        </select>
                    </form>
                    <div class="flex space-x-2">
                        <button
                            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-th-large text-gray-600"></i>
                        </button>
                        <button
                            class="w-10 h-10 flex items-center justify-center border border-primary-500 bg-primary-50 text-primary-700 rounded-lg">
                            <i class="fas fa-list text-primary-700"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Filters Section -->
        <form method="GET" action="{{ route('buyer.dashboard') }}" id="filterForm">
        <div id="filtersContainer" class="hidden lg:block filters-container bg-white rounded-xl shadow-sm p-6 mb-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-semibold text-gray-800">Filter Products</h2>
                <a href="{{ route('buyer.dashboard') }}"
                    class="text-primary-500 hover:text-primary-700 text-sm font-medium flex items-center space-x-1">
                    <i class="fas fa-sync-alt"></i>
                    <span>Reset All</span>
                </a>
            </div>
            

            <div class="flex gap-6  w-full  text-center justify-center ">
                
                <!-- Search -->
                <div class="w-full">
                    <h3 class="text-sm text-gray-700 mb-1">Search</h3>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Egg type..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Location -->
                <div class="w-full">
                    <h3 class="text-sm text-gray-700 mb-1">Location</h3>
                    <div class="relative">
                        <input type="text" name="location" value="{{ $location }}" placeholder="City or region"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category -->
                <div class="w-full">
                    <h3 class="text-sm text-gray-700 mb-1">Egg Type</h3>
                    <select name="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <option value="all">All Egg Types</option>
                        @foreach($eggTypes as $eggType)
                            <option value="{{ $eggType }}" {{ $category == $eggType ? 'selected' : '' }}>{{ $eggType }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-full  justify-center">
                    <h3 class="text-sm text-gray-700 mb-1">Availability</h3>
                    <div class="flex space-x-4">
                        <label
                            class="flex-1 flex items-center justify-center p-2 border border-primary-500 bg-primary-50 text-primary-700 rounded-lg cursor-pointer transition duration-200">
                            <input type="radio" name="status" value="all" class="hidden" {{ !request('status') || request('status') == 'all' ? 'checked' : '' }}>
                            <span class="text-sm">All Products</span>
                        </label>
                        <label
                            class="flex-1 flex items-center justify-center p-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="status" value="Available" class="hidden" {{ request('status') == 'Available' ? 'checked' : '' }}>
                            <span class="text-sm text-gray-600">Available Only</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Quantity Range -->
                <div>
                    <h3 class="text-sm text-gray-700 mb-1">Quantity Range</h3>
                    <div class="flex space-x-2">
                        <input type="number" name="min_quantity" value="{{ $minQuantity }}" placeholder="Min" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <input type="number" name="max_quantity" value="{{ $maxQuantity }}" placeholder="Max" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="text-sm text-gray-700 mb-1">Price Range ($)</h3>
                    <div class="flex space-x-2">
                        <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <!-- Certification -->
                <div>
                    <h3 class="text-sm text-gray-700 mb-1">Certification</h3>
                    <div class="space-y-2">
                        <label
                            class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                            <input type="checkbox" name="certification[]" value="Organic" 
                                {{ in_array('Organic', $certification ?? []) ? 'checked' : '' }}
                                class="rounded text-primary-500 mr-3 focus:ring-primary-500">
                            <span class="text-gray-600">Organic</span>
                        </label>
                        <label
                            class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                            <input type="checkbox" name="certification[]" value="Non-GMO" 
                                {{ in_array('Non-GMO', $certification ?? []) ? 'checked' : '' }}
                                class="rounded text-primary-500 mr-3 focus:ring-primary-500">
                            <span class="text-gray-600">Non-GMO</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-2 pt-6 border-t border-gray-200">
                <button type="submit"
                    class="w-full bg-primary-500 text-white py-2 rounded-lg hover:bg-primary-600 transition duration-200 text-sm flex items-center justify-center space-x-2">
                    <i class="fas fa-filter"></i>
                    <span>Apply Filters</span>
                </button>
            </div>
        </div>
        </form>

        <!-- Product Listings -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover animate-fade-in">
                <div class="h-40 relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                            <i class="fas fa-image text-white text-4xl"></i>
                        </div>
                    @endif
                    <span
                        class="absolute top-3 left-3 bg-white text-primary-700 text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="fas fa-leaf mr-1"></i> Organic
                    </span>
                    <span
                        class="absolute top-3 right-3 bg-white text-gray-700 text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="fas fa-star text-yellow-400 mr-1"></i> 4.8
                    </span>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                            @if($product->egg_type)
                                <div class="mt-1">
                                    <span class="inline-flex items-center .5 py-0.5 rounded-full text-xl font-medium  text-black mb-5">
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
                            <div class="mt-1">
                                <span class="px-2 py-1 text-xs rounded-full font-medium 
                                    @if($product->status == 'Available') bg-green-100 text-green-800
                                    @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm flex items-center mt-1">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> {{ $product->farmer->user->address ?? 'Farm Location' }}, {{ $product->farmer->user->state ?? 'State' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-primary-700 font-bold text-xl">${{ number_format($product->price, 2) }} <span
                                    class="text-gray-500 text-sm font-normal">/ {{ $product->unit }}</span></p>
                            <p class="text-gray-600 text-sm">{{ $product->quantity }} {{ $product->unit }} available</p>
                            
                            <!-- Egg Sizes -->
                            @if($product->sizes && $product->sizes->count() > 0)
                            <div class="mt-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->sizes as $size)
                                        @php
                                            $sizeLabels = [
                                                'small' => 'Small',
                                                'medium' => 'Medium',
                                                'large' => 'Large',
                                                'extra_large' => 'Extra Large',
                                                'jumbo' => 'Jumbo'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $sizeLabels[$size->size_name] ?? ucfirst(str_replace('_', ' ', $size->size_name)) }}: {{ $size->tray_count }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Harvest Date</p>
                            <p class="text-gray-700 font-medium">{{ $product->harvest_date?->format('d M Y') ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @if($product->status == 'Sold Out')
                    <button
                        class="w-full bg-gray-300 text-gray-500 py-2.5 rounded-lg cursor-not-allowed font-medium flex items-center justify-center space-x-2" disabled>
                        <i class="fas fa-shopping-cart"></i>
                        <span>Sold Out</span>
                    </button>
                    @else
                    <button
                        class="w-full bg-green-500 text-white py-2.5 rounded-lg hover:bg-primary-600 transition duration-200 font-medium flex items-center justify-center space-x-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span><a href="{{ route('buyer.products.show', $product) }}" class="text-white hover:text-white">View Details</a></span>
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No products found</h3>
                <p class="text-gray-600">Check back later for new listings.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
        @endif

        <!-- Load More Button -->
        <div class="mt-12 text-center">
            <button
                class="bg-white text-primary-500 border border-primary-500 px-8 py-3 rounded-lg hover:bg-primary-50 transition duration-200 font-medium">
                Load More Listings
            </button>
        </div>
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
    // Toggle filters visibility
    function toggleFilters() {
        const filtersContainer = document.getElementById('filtersContainer');
        filtersContainer.classList.toggle('hidden');
        filtersContainer.classList.toggle('lg:block');
    }

    // Auto-submit form when filter inputs change
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const inputs = filterForm.querySelectorAll('input[type="text"], input[type="number"], select');
        
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Add a small delay for better UX
                setTimeout(() => {
                    filterForm.submit();
                }, 300);
            });
        });

        // Handle checkbox changes
        const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                setTimeout(() => {
                    filterForm.submit();
                }, 300);
            });
        });

        // Handle radio button changes
        const radios = filterForm.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                setTimeout(() => {
                    filterForm.submit();
                }, 300);
            });
        });
    });

    // Mobile sidebar functionality (if needed)
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (mobileSidebarToggle && mobileSidebar) {
        function openMobileSidebar() {
            mobileSidebar.classList.add('open');
            if (sidebarOverlay) sidebarOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            mobileSidebar.classList.remove('open');
            if (sidebarOverlay) sidebarOverlay.classList.remove('open');
            document.body.style.overflow = 'auto';
        }

        mobileSidebarToggle.addEventListener('click', openMobileSidebar);
        if (closeSidebar) closeSidebar.addEventListener('click', closeMobileSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeMobileSidebar);

        // Close sidebar on window resize if it becomes desktop view
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeMobileSidebar();
            }
        });
    }
</script>
@endsection
