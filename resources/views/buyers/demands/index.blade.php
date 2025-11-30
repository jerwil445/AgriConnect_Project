@extends('layouts.buyers_page')

@section('content')
<div class="container mx-auto px-4  ">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Demands</h1>
        <button id="openDemandModal" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Post New Demand
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($demands->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-gray-600">You haven't posted any demands yet.</p>
            <button id="openDemandModalEmpty" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Post Your First Demand
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($demands as $demand)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-bold text-gray-800">
                                                        @php
                                                            $eggTypes = [
                                                                'chicken' => 'Chicken',
                                                                'duck' => 'Duck',
                                                                'quail' => 'Quail',
                                                                'native_chicken' => 'Native Chicken',
                                                                'brown' => 'Brown Egg',
                                                                'white' => 'White Egg'
                                                            ];
                                                        @endphp
                                                        {{ $eggTypes[$demand->egg_type] ?? ucfirst(str_replace('_', ' ', $demand->egg_type)) }}
                                                    </h2>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $demand->quantity }} {{ $demand->unit ?? 'units' }}
                            </span>
                        </div>
                        
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $demand->location }}
                            </div>
                            
                            <!-- Egg-specific information -->
                            @if($demand->egg_type || $demand->egg_size)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                <span>
                                    @if($demand->egg_type)
                                        {{ ucfirst(str_replace('_', ' ', $demand->egg_type)) }}
                                        @if($demand->egg_size)
                                            <span class="mx-1">•</span>
                                        @endif
                                    @endif
                                    @if($demand->egg_size)
                                        {{ ucfirst(str_replace('_', ' ', $demand->egg_size)) }}
                                    @endif
                                </span>
                            </div>
                            @endif
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Delivery by: {{ $demand->delivery_date->format('M d, Y') }}
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h3 class="font-medium text-gray-700">Matches Found: {{ $demand->matches->count() }}</h3>
                            <div class="mt-2 space-y-2">
                                @foreach($demand->matches->take(3) as $match)
                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                        <div>
                                            <p class="text-sm font-medium">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $match->product->quantity }} {{ $match->product->unit }} available</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded 
                                            @if($match->status == 'Matched') bg-green-100 text-green-800
                                            @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($match->status == 'New') bg-blue-100 text-blue-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $match->status }}
                                        </span>
                                    </div>
                                @endforeach
                                @if($demand->matches->count() > 3)
                                    <p class="text-sm text-gray-500 text-center">+{{ $demand->matches->count() - 3 }} more matches</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-6 flex space-x-2">
                            <a href="{{ route('demands.show', $demand) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                View Details
                            </a>
                            <form action="{{ route('demands.destroy', $demand) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button p-2 border border-transparent rounded-md text-white bg-red-200 hover:bg-red-700" title="Delete Demand">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Demand Creation Modal -->
<div id="demandModal" class="fixed inset-0 bg-gray-600 bg-opacity-70 hidden overflow-y-auto h-full w-full z-50" style="z-index: 9999;">
    <div class="relative top-20 mx-auto p-5 border w-3/6 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Post New Demand</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-500 bg-transparent hover:bg-gray-200 rounded-full p-1 transition duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Loading Overlay -->
            <div id="loadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10 rounded-md">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div>
                    <p class="mt-4 text-gray-700">Finding matches for your demand...</p>
                </div>
            </div>
            
            <form id="demandForm" action="{{ route('demands.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <div class="mb-4">
                            <label for="modal_egg_type" class="block text-gray-700 font-medium mb-2">Egg Type / Category</label>
                            <select name="egg_type" id="modal_egg_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                <option value="">Select Egg Type</option>
                                <option value="chicken">Chicken</option>
                                <option value="duck">Duck</option>
                                <option value="quail">Quail</option>
                                <option value="native_chicken">Native Chicken</option>
                                <option value="brown">Brown Egg</option>
                                <option value="white">White Egg</option>
                            </select>
                        </div>
                        
                        <!-- <div class="mb-4">
                            <label for="modal_address" class="block text-gray-700 font-medium mb-2">Address</label>
                            <input type="text" name="address" id="modal_address" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                                   placeholder="Enter delivery address">
                        </div> -->
                        
                        <div class="mb-4">
                            <label for="modal_quantity" class="block text-gray-700 font-medium mb-2">Quantity</label>
                            <input type="number" name="quantity" id="modal_quantity" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                                   min="1" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="modal_location" class="block text-gray-700 font-medium mb-2">Delivery Location</label>
                            <input type="text" name="location" id="modal_location" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                                   required>
                        </div>
                        
                        <div class="mb-6">
                            <label for="modal_delivery_date" class="block text-gray-700 font-medium mb-2">Delivery Date</label>
                            <input type="date" name="delivery_date" id="modal_delivery_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                                   required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <!-- Egg-specific fields -->
                        <div id="egg-demand-fields" class="mb-4">
                            <div class=" ">
                                <h4 class="text-md font-medium text-gray-900 mb-3">Egg-Specific Details</h4>
                                
                                <div class="mb-3">
                                    <label class="block text-gray-700 font-medium mb-2">Size / Grade</label>
                                    <div class="space-y-3">
                                        <div class="border border-gray-200 rounded-lg p-3 hover:border-green-300 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" name="egg_sizes[]" value="small" id="modal_small_checkbox" 
                                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="font-medium text-gray-700 ml-2">Small</span>
                                                    <span class="text-xs text-gray-500 ml-2">Size</span>
                                                </label>
                                                <div id="modal_small_tray_container" class="hidden flex items-center">
                                                    <label for="modal_small_trays" class="text-sm text-gray-600 mr-2">Tray(s):</label>
                                                    <input type="number" name="small_trays" id="modal_small_trays" min="1" 
                                                           class="w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                           placeholder="Qty">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="border border-gray-200 rounded-lg p-3 hover:border-green-300 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" name="egg_sizes[]" value="medium" id="modal_medium_checkbox" 
                                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="font-medium text-gray-700 ml-2">Medium</span>
                                                    <span class="text-xs text-gray-500 ml-2">Size</span>
                                                </label>
                                                <div id="modal_medium_tray_container" class="hidden flex items-center">
                                                    <label for="modal_medium_trays" class="text-sm text-gray-600 mr-2">Tray(s):</label>
                                                    <input type="number" name="medium_trays" id="modal_medium_trays" min="1" 
                                                           class="w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                           placeholder="Qty">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="border border-gray-200 rounded-lg p-3 hover:border-green-300 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" name="egg_sizes[]" value="large" id="modal_large_checkbox" 
                                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="font-medium text-gray-700 ml-2">Large</span>
                                                    <span class="text-xs text-gray-500 ml-2">Size</span>
                                                </label>
                                                <div id="modal_large_tray_container" class="hidden flex items-center">
                                                    <label for="modal_large_trays" class="text-sm text-gray-600 mr-2">Tray(s):</label>
                                                    <input type="number" name="large_trays" id="modal_large_trays" min="1" 
                                                           class="w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                           placeholder="Qty">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="border border-gray-200 rounded-lg p-3 hover:border-green-300 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" name="egg_sizes[]" value="extra_large" id="modal_extra_large_checkbox" 
                                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="font-medium text-gray-700 ml-2">Extra Large</span>
                                                    <span class="text-xs text-gray-500 ml-2">Size</span>
                                                </label>
                                                <div id="modal_extra_large_tray_container" class="hidden flex items-center">
                                                    <label for="modal_extra_large_trays" class="text-sm text-gray-600 mr-2">Tray(s):</label>
                                                    <input type="number" name="extra_large_trays" id="modal_extra_large_trays" min="1" 
                                                           class="w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                           placeholder="Qty">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="border border-gray-200 rounded-lg p-3 hover:border-green-300 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" name="egg_sizes[]" value="jumbo" id="modal_jumbo_checkbox" 
                                                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="font-medium text-gray-700 ml-2">Jumbo</span>
                                                    <span class="text-xs text-gray-500 ml-2">Size</span>
                                                </label>
                                                <div id="modal_jumbo_tray_container" class="hidden flex items-center">
                                                    <label for="modal_jumbo_trays" class="text-sm text-gray-600 mr-2">Tray(s):</label>
                                                    <input type="number" name="jumbo_trays" id="modal_jumbo_trays" min="1" 
                                                           class="w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                           placeholder="Qty">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="egg_size" id="modal_egg_size_hidden">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="submitDemand" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Post Demand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-70 hidden overflow-y-auto h-full w-full z-50" style="z-index: 10000;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Confirm Deletion</h3>
                <button id="closeDeleteModal" class="text-gray-400 hover:text-gray-500 bg-transparent hover:bg-gray-200 rounded-full p-1 transition duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mt-2 px-4 py-3">
                <p class="text-gray-700">Are you sure you want to delete this demand? This action cannot be undone.</p>
            </div>
            
            <div class="mt-4 flex justify-end space-x-4">
                <button id="cancelDelete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Message Modal -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-70 hidden overflow-y-auto h-full w-full z-50" style="z-index: 10001;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Success</h3>
                <button id="closeSuccessModal" class="text-gray-400 hover:text-gray-500 bg-transparent hover:bg-gray-200 rounded-full p-1 transition duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mt-2 px-4 py-3">
                <p id="successMessage" class="text-gray-700"></p>
            </div>
            
            <div class="mt-4 flex justify-end">
                <button id="okSuccess" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Message Modal -->
<div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-70 hidden overflow-y-auto h-full w-full z-50" style="z-index: 10001;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Error</h3>
                <button id="closeErrorModal" class="text-gray-400 hover:text-gray-500 bg-transparent hover:bg-gray-200 rounded-full p-1 transition duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mt-2 px-4 py-3">
                <p id="errorMessage" class="text-gray-700"></p>
            </div>
            
            <div class="mt-4 flex justify-end">
                <button id="okError" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('demandModal');
        const openModalBtn = document.getElementById('openDemandModal');
        const openModalBtnEmpty = document.getElementById('openDemandModalEmpty');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelModalBtn = document.getElementById('cancelModal');
        const demandForm = document.getElementById('demandForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        
        // Open modal
        function openModal() {
            modal.classList.remove('hidden');
            // Prevent scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }
        
        // Close modal
        function closeModal() {
            modal.classList.add('hidden');
            // Re-enable scrolling when modal is closed
            document.body.style.overflow = 'auto';
            if (demandForm) {
                demandForm.reset();
            }
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
        }
        
        // Event listeners
        if (openModalBtn) {
            openModalBtn.addEventListener('click', openModal);
        }
        
        if (openModalBtnEmpty) {
            openModalBtnEmpty.addEventListener('click', openModal);
        }
        
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }
        
        if (cancelModalBtn) {
            cancelModalBtn.addEventListener('click', closeModal);
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (modal && event.target === modal) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
        
        // Handle form submission
        if (demandForm) {
            demandForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading overlay
                if (loadingOverlay) {
                    loadingOverlay.classList.remove('hidden');
                }
                
                // Submit form via AJAX
                const formData = new FormData(demandForm);
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    if (loadingOverlay) {
                        loadingOverlay.classList.add('hidden');
                    }
                    alert('There was an error with the form submission. Please try again.');
                    return;
                }
                
                fetch(demandForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', [...response.headers.entries()]);
                    
                    // Handle validation errors (422)
                    if (response.status === 422) {
                        return response.json().then(data => {
                            throw new Error('Validation failed: ' + JSON.stringify(data.errors || data.message || 'Please check your form data'));
                        });
                    }
                    
                    // Check if response is OK
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    
                    // Check content type
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON: ' + contentType);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data && data.success) {
                        // Redirect to demands index page
                        window.location.href = "{{ route('demands.index') }}";
                    } else {
                        // Handle validation errors or other issues
                        if (loadingOverlay) {
                            loadingOverlay.classList.add('hidden');
                        }
                        const errorMessage = (data && data.message) ? data.message : 'There was an error submitting your demand. Please try again.';
                        console.error('Server error:', errorMessage);
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    if (loadingOverlay) {
                        loadingOverlay.classList.add('hidden');
                    }
                    console.error('Fetch error:', error);
                    alert('There was an error submitting your demand. Please check your connection and try again. Error: ' + error.message);
                });
            });
        }
        
        // Egg-specific fields are always visible now
        
        // Handle egg tray inputs for all sizes
        const modalEggSizeHidden = document.getElementById('modal_egg_size_hidden');
        
        // Function to update the hidden input with egg sizes and tray counts
        function updateModalEggSizeHidden() {
            const checkboxes = document.querySelectorAll('input[name="egg_sizes[]"]');
            const selectedValues = [];
            
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    let trayCount = '';
                    let trayElement = null;
                    
                    switch (checkbox.value) {
                        case 'small':
                            trayElement = document.getElementById('modal_small_trays');
                            break;
                        case 'medium':
                            trayElement = document.getElementById('modal_medium_trays');
                            break;
                        case 'large':
                            trayElement = document.getElementById('modal_large_trays');
                            break;
                        case 'extra_large':
                            trayElement = document.getElementById('modal_extra_large_trays');
                            break;
                        case 'jumbo':
                            trayElement = document.getElementById('modal_jumbo_trays');
                            break;
                    }
                    
                    if (trayElement) {
                        trayCount = trayElement.value;
                    }
                    
                    if (trayCount) {
                        selectedValues.push(`${checkbox.value} (${trayCount} tray${trayCount > 1 ? 's' : ''})`);
                    } else {
                        selectedValues.push(checkbox.value);
                    }
                }
            });
            
            if (modalEggSizeHidden) {
                modalEggSizeHidden.value = selectedValues.join(', ');
            }
            
            // Update the main quantity field based on tray inputs
            updateTotalQuantity();
        }
        
        // Function to calculate and update the total quantity
        function updateTotalQuantity() {
            let totalTrays = 0;
            
            // Get all tray inputs and sum their values
            const trayInputs = [
                document.getElementById('modal_small_trays'),
                document.getElementById('modal_medium_trays'),
                document.getElementById('modal_large_trays'),
                document.getElementById('modal_extra_large_trays'),
                document.getElementById('modal_jumbo_trays')
            ];
            
            trayInputs.forEach(input => {
                if (input && input.value) {
                    totalTrays += parseInt(input.value) || 0;
                }
            });
            
            // Update the main quantity field
            const quantityField = document.getElementById('modal_quantity');
            if (quantityField) {
                quantityField.value = totalTrays;
            }
        }
        
        // Add event listeners to all egg size checkboxes
        document.querySelectorAll('input[name="egg_sizes[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Show/hide tray input for each size
                let trayContainer = null;
                let trayInput = null;
                
                switch (this.value) {
                    case 'small':
                        trayContainer = document.getElementById('modal_small_tray_container');
                        trayInput = document.getElementById('modal_small_trays');
                        break;
                    case 'medium':
                        trayContainer = document.getElementById('modal_medium_tray_container');
                        trayInput = document.getElementById('modal_medium_trays');
                        break;
                    case 'large':
                        trayContainer = document.getElementById('modal_large_tray_container');
                        trayInput = document.getElementById('modal_large_trays');
                        break;
                    case 'extra_large':
                        trayContainer = document.getElementById('modal_extra_large_tray_container');
                        trayInput = document.getElementById('modal_extra_large_trays');
                        break;
                    case 'jumbo':
                        trayContainer = document.getElementById('modal_jumbo_tray_container');
                        trayInput = document.getElementById('modal_jumbo_trays');
                        break;
                }
                
                if (trayContainer) {
                    if (this.checked) {
                        trayContainer.classList.remove('hidden');
                        trayContainer.classList.add('flex');
                    } else {
                        trayContainer.classList.add('hidden');
                        trayContainer.classList.remove('flex');
                        if (trayInput) {
                            trayInput.value = '';
                        }
                    }
                }
                updateModalEggSizeHidden();
            });
        });
        
        // Add event listeners to all tray inputs
        const trayInputs = [
            document.getElementById('modal_small_trays'),
            document.getElementById('modal_medium_trays'),
            document.getElementById('modal_large_trays'),
            document.getElementById('modal_extra_large_trays'),
            document.getElementById('modal_jumbo_trays')
        ];
        
        trayInputs.forEach(input => {
            if (input) {
                input.addEventListener('input', function() {
                    updateModalEggSizeHidden();
                });
            }
        });
        
        // Delete functionality
        let deleteFormToSubmit = null;
        
        // Add event listeners to delete buttons
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                deleteFormToSubmit = this.closest('.delete-form');
                document.getElementById('deleteModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });
        
        // Close delete modal
        document.getElementById('closeDeleteModal').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            deleteFormToSubmit = null;
        });
        
        // Cancel delete
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            deleteFormToSubmit = null;
        });
        
        // Confirm delete
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteFormToSubmit) {
                // Get the form action and CSRF token
                const action = deleteFormToSubmit.getAttribute('action');
                const csrfToken = deleteFormToSubmit.querySelector('input[name="_token"]').getAttribute('value');
                
                // Close the modal
                document.getElementById('deleteModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                
                // Send AJAX request
                fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        '_method': 'DELETE'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success popup
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('successModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    } else {
                        // Show error message
                        document.getElementById('errorMessage').textContent = data.message;
                        document.getElementById('errorModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('errorMessage').textContent = 'An error occurred while deleting the demand.';
                    document.getElementById('errorModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
                
                deleteFormToSubmit = null;
            }
        });
        
        // Success modal event listeners
        document.getElementById('okSuccess').addEventListener('click', function() {
            document.getElementById('successModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            location.reload();
        });
        
        document.getElementById('closeSuccessModal').addEventListener('click', function() {
            document.getElementById('successModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            location.reload();
        });
        
        // Error modal event listeners
        document.getElementById('okError').addEventListener('click', function() {
            document.getElementById('errorModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
        
        document.getElementById('closeErrorModal').addEventListener('click', function() {
            document.getElementById('errorModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
        
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                this.classList.add('hidden');
                document.body.style.overflow = 'auto';
                deleteFormToSubmit = null;
            }
        });
    });
</script>
@endsection