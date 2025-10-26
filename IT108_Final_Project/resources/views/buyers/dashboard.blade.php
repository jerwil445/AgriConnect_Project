<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriConnect - Modern Agricultural Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9f0',
                            100: '#dcf2dc',
                            500: '#4caf50',
                            600: '#3d8b40',
                            700: '#2e6b31',
                            800: '#1f4a21',
                            900: '#0f2a11',
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'slide-left': 'slideLeft 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                        slideDown: {
                            '0%': {
                                transform: 'translateY(-10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                        slideLeft: {
                            '0%': {
                                transform: 'translateX(-100%)'
                            },
                            '100%': {
                                transform: 'translateX(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f0f9f0 0%, #e8f5e9 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .range-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            border-radius: 5px;
            background: #e2e8f0;
            outline: none;
        }

        .range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #4caf50;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .filters-container {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
        }

        .filters-container.open {
            max-height: 2000px;
        }

        @media (min-width: 1024px) {
            .filters-container {
                max-height: none !important;
            }
        }

        /* Mobile Sidebar Styles */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 320px;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .mobile-sidebar.open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .sidebar-overlay.open {
            display: block;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen font-inter">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="mobile-sidebar bg-white shadow-xl">
        <div class="p-6 h-full flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Filter Products</h2>
                <button id="closeSidebar"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-times text-gray-700"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <!-- Search -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Search</h3>
                    <div class="relative">
                        <input type="text" placeholder="Product name..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Location</h3>
                    <div class="relative">
                        <input type="text" placeholder="City or region"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Category</h3>
                    <select
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <option>All Categories</option>
                        <option>Grains</option>
                        <option>Fruits</option>
                        <option>Vegetables</option>
                        <option>Dairy</option>
                        <option>Livestock</option>
                    </select>
                </div>

                <!-- Listing Type -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Listing Type</h3>
                    <div class="flex space-x-4">
                        <label
                            class="flex-1 flex items-center justify-center p-3 border border-primary-500 bg-primary-50 text-primary-700 rounded-lg cursor-pointer transition duration-200">
                            <input type="radio" name="listingType" class="hidden" checked>
                            <span class="font-medium">For Sale</span>
                        </label>
                        <label
                            class="flex-1 flex items-center justify-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="listingType" class="hidden">
                            <span class="font-medium text-gray-600">To Buy</span>
                        </label>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Quantity (in Tons)</h3>
                    <div class="px-2">
                        <input type="range" min="0" max="100" value="50" class="range-slider">
                        <div class="flex justify-between text-sm text-gray-500 mt-2">
                            <span>0</span>
                            <span>50</span>
                            <span>100+</span>
                        </div>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Price Range</h3>
                    <div class="px-2">
                        <input type="range" min="0" max="500" value="250" class="range-slider">
                        <div class="flex justify-between text-sm text-gray-500 mt-2">
                            <span>$0</span>
                            <span>$250</span>
                            <span>$500+</span>
                        </div>
                    </div>
                </div>

                <!-- Certification -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-3">Certification</h3>
                    <div class="space-y-2">
                        <label
                            class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                            <input type="checkbox" class="rounded text-primary-500 mr-3 focus:ring-primary-500">
                            <span class="text-gray-600">Organic Certified</span>
                        </label>
                        <label
                            class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                            <input type="checkbox" class="rounded text-primary-500 mr-3 focus:ring-primary-500">
                            <span class="text-gray-600">Fair Trade</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <button
                    class="w-full bg-primary-500 text-white py-3 rounded-lg hover:bg-primary-600 transition duration-200 font-medium flex items-center justify-center space-x-2">
                    <i class="fas fa-filter"></i>
                    <span>Apply Filters</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <button id="mobileSidebarToggle"
                        class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition duration-200">
                        <i class="fas fa-bars text-gray-700"></i>
                    </button>
                    <h1 class="flex items-center gap-1 text-2xl font-bold text-green-700">
                        <svg class="w-9 h-9 text-green-500 " xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.83892 12.4543s1.24988-3.08822-.21626-5.29004C8.15656 4.96245 4.58671 4.10885 4.39794 4.2436c-.18877.13476-1.11807 3.32546.34803 5.52727 1.4661 2.20183 5.09295 2.68343 5.09295 2.68343Zm0 0C10.3389 13.4543 12 15 12 18v2c0-2-.4304-3.4188 2.0696-5.9188m0 0s-.4894-2.7888 1.1206-4.35788c1.6101-1.56907 4.4903-1.54682 4.6701-1.28428.1798.26254.4317 2.84376-1.0809 4.31786-1.61 1.5691-4.7098 1.3243-4.7098 1.3243Z" />
                        </svg>
                        <span>ArgiConnect</span>
                    </h1>

                </div>

                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="text-primary-700 font-medium border-b-2 border-primary-500 pb-1">Home</a>
                    <a href="#"
                        class="text-gray-600 hover:text-primary-700 font-medium transition duration-200">My
                        Listings</a>
                    <a href="#"
                        class="text-gray-600 hover:text-primary-700 font-medium transition duration-200">Messages</a>
                    <a href="#"
                        class="text-gray-600 hover:text-primary-700 font-medium transition duration-200">Analytics</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <button
                        class="hidden md:flex bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600 transition duration-200 font-medium items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>New Listing</span>
                    </button>
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center cursor-pointer">
                        <i class="fas fa-user text-gray-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-2">
        <!-- Page Header -->
        <div class="mb-8 flex  flex-col  text-center ">
            <h1 class="text-xl font-bold text-gray-800 mb-2">Agricultural Products</h1>
            <!-- Search -->
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
        </div>

        <!-- Results Header and Controls -->
        <div class="bg-white rounded-xl shadow-sm p-3 mb-2 animate-slide-up">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Showing 12 results</h2>
                    <p class="text-gray-600 mt-1">From 125 listings available</p>
                </div>
                <div class="flex items-center space-x-2 mt-2 md:mt-0">
                    <div class="flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-lg">
                        <span class="text-gray-600 text-sm">Sort by:</span>
                        <select class="bg-transparent focus:outline-none text-gray-800  ">
                            <option class="text-sm">Newest First</option>
                            <option class="text-sm">Price: Low to High</option>
                            <option class="text-sm">Price: High to Low</option>
                            <option class="text-sm">Most Popular</option>
                        </select>
                    </div>
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
        <div id="filtersContainer" class="hidden lg:block filters-container bg-white rounded-xl shadow-sm p-6 mb-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-semibold text-gray-800">Filter Products</h2>
                <button
                    class="text-primary-500 hover:text-primary-700 text-sm font-medium flex items-center space-x-1">
                    <i class="fas fa-sync-alt"></i>
                    <span>Reset All</span>
                </button>
            </div>

            <div class="flex gap-6  w-full  text-center justify-center ">
                <!-- Search -->
                {{--  <div>
                    <h3 class="font-medium text-gray-700 mb-3">Search</h3>
                    <div class="relative">
                        <input type="text" placeholder="Product name..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div> --}}

                <!-- Location -->
                <div class="w-full">
                    <h3 class="text-sm text-gray-700 mb-1">Location</h3>
                    <div class="relative">
                        <input type="text" placeholder="City or region"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category -->
                <div class="w-full">
                    <h3 class="text-sm text-gray-700 mb-1">Category</h3>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-200">
                        <option>All Categories</option>
                        <option>Grains</option>
                        <option>Fruits</option>
                        <option>Vegetables</option>
                        <option>Dairy</option>
                        <option>Livestock</option>
                    </select>
                </div>

                <!-- Listing Type -->
                <div class="w-full  justify-center">
                    <h3 class="text-sm text-gray-700 mb-1">Listing Type</h3>
                    <div class="flex space-x-4">
                        <label
                            class="flex-1 flex items-center justify-center p-2 border border-primary-500 bg-primary-50 text-primary-700 rounded-lg cursor-pointer transition duration-200">
                            <input type="radio" name="listingType" class="hidden" checked>
                            <span class="text-sm">For Sale</span>
                        </label>
                        <label
                            class="flex-1 flex items-center justify-center p-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="listingType" class="hidden">
                            <span class="text-sm text-gray-600">To Buy</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Quantity -->
                <div>
                    <h3 class="text-sm text-gray-700 mb-1">Quantity (in Tons)</h3>
                    <div class="px-2">
                        <input type="range" min="0" max="100" value="50" class="range-slider ">
                        <div class="flex justify-between text-sm text-gray-500 mt-2">
                            <span>0</span>
                            <span>50</span>
                            <span>100+</span>
                        </div>
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="text-sm text-gray-700 mb-1">Price Range</h3>
                    <div class="px-2">
                        <input type="range" min="0" max="500" value="250" class="range-slider">
                        <div class="flex justify-between text-sm text-gray-500 mt-2">
                            <span>$0</span>
                            <span>$250</span>
                            <span>$500+</span>
                        </div>
                    </div>
                </div>

                <!-- Certification -->
                <div>
                    <h3 class="text-sm text-gray-700 mb-1">Certification</h3>
                    <div class="space-y-2">
                        <label
                            class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                            <input type="checkbox" class="rounded text-primary-500 mr-3 focus:ring-primary-500">
                            <span class="text-gray-600">Organic Certified</span>
                        </label>
                        <label
                            class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                            <input type="checkbox" class="rounded text-primary-500 mr-3 focus:ring-primary-500">
                            <span class="text-gray-600">Fair Trade</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-2 pt-6 border-t border-gray-200">
                <button
                    class="w-full bg-primary-500 text-white py-2 rounded-lg hover:bg-primary-600 transition duration-200 text-sm flex items-center justify-center space-x-2">
                    <i class="fas fa-filter"></i>
                    <span>Apply Filters</span>
                </button>
            </div>
        </div>

        <!-- Product Listings -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Listing 1 -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover animate-fade-in">
                <div class="h-40 bg-gradient-to-r from-green-400 to-green-600 relative">
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
                            <h3 class="text-lg font-semibold text-gray-800">Organic Hass Avocados</h3>
                            <p class="text-gray-600 text-sm flex items-center mt-1">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> Green Valley Farms, CA
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-primary-700 font-bold text-xl">$2.50 <span
                                    class="text-gray-500 text-sm font-normal">/ kg</span></p>
                            <p class="text-gray-600 text-sm">1.5 Tons available</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Harvest Date</p>
                            <p class="text-gray-700 font-medium">15 Oct 2023</p>
                        </div>
                    </div>
                    <button
                        class="w-full bg-primary-500 text-white py-2.5 rounded-lg hover:bg-primary-600 transition duration-200 font-medium flex items-center justify-center space-x-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span>View Details</span>
                    </button>
                </div>
            </div>

            <!-- Listing 2 -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover animate-fade-in"
                style="animation-delay: 0.1s">
                <div class="h-40 bg-gradient-to-r from-amber-400 to-amber-600 relative">
                    <span
                        class="absolute top-3 left-3 bg-white text-amber-700 text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="fas fa-certificate mr-1"></i> Premium
                    </span>
                    <span
                        class="absolute top-3 right-3 bg-white text-gray-700 text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="fas fa-star text-yellow-400 mr-1"></i> 4.9
                    </span>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Hard Red Wheat</h3>
                            <p class="text-gray-600 text-sm flex items-center mt-1">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> Plains Grain Co., KS
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-primary-700 font-bold text-xl">$220 <span
                                    class="text-gray-500 text-sm font-normal">/ ton</span></p>
                            <p class="text-gray-600 text-sm">50 Tons available</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Ready in</p>
                            <p class="text-gray-700 font-medium">3 days</p>
                        </div>
                    </div>
                    <button
                        class="w-full bg-primary-500 text-white py-2.5 rounded-lg hover:bg-primary-600 transition duration-200 font-medium flex items-center justify-center space-x-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span>View Details</span>
                    </button>
                </div>
            </div>

            <!-- Listing 3 -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover animate-fade-in"
                style="animation-delay: 0.2s">
                <div class="h-40 bg-gradient-to-r from-red-400 to-red-600 relative">
                    <span
                        class="absolute top-3 left-3 bg-white text-red-700 text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="fas fa-fire mr-1"></i> Fresh
                    </span>
                    <span
                        class="absolute top-3 right-3 bg-white text-gray-700 text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="fas fa-star text-yellow-400 mr-1"></i> 4.7
                    </span>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Vine Ripened Tomatoes</h3>
                            <p class="text-gray-600 text-sm flex items-center mt-1">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> Sunny Slope Farms, FL
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-primary-700 font-bold text-xl">$1.80 <span
                                    class="text-gray-500 text-sm font-normal">/ kg</span></p>
                            <p class="text-gray-600 text-sm">8 Tons available</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Picked</p>
                            <p class="text-gray-700 font-medium">Today</p>
                        </div>
                    </div>
                    <button
                        class="w-full bg-primary-500 text-white py-2.5 rounded-lg hover:bg-primary-600 transition duration-200 font-medium flex items-center justify-center space-x-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span>View Details</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="mt-12 text-center">
            <button
                class="bg-white text-primary-500 border border-primary-500 px-8 py-3 rounded-lg hover:bg-primary-50 transition duration-200 font-medium">
                Load More Listings
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary-800 text-white mt-16">
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

    <script>
        // Mobile sidebar functionality
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openMobileSidebar() {
            mobileSidebar.classList.add('open');
            sidebarOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            mobileSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
            document.body.style.overflow = 'auto';
        }

        mobileSidebarToggle.addEventListener('click', openMobileSidebar);
        closeSidebar.addEventListener('click', closeMobileSidebar);
        sidebarOverlay.addEventListener('click', closeMobileSidebar);

        // Close sidebar when clicking on a filter option (for better UX)
        const filterLabels = document.querySelectorAll('.mobile-sidebar label');
        filterLabels.forEach(label => {
            label.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    setTimeout(closeMobileSidebar, 300);
                }
            });
        });

        // Close sidebar on window resize if it becomes desktop view
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeMobileSidebar();
            }
        });
    </script>
</body>

</html>
