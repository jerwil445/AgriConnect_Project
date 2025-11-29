@extends('layouts.buyers_page')

@section('content')
<div class=" mx-auto px-4  buyer-content">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Demand Details</h1>
            <a href="{{ route('demands.index') }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Back to Demands
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $demand->product_name }}</h2>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-gray-700">Quantity: <span class="font-medium">{{ $demand->quantity }} {{ $demand->unit ?? 'units' }}</span></span>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-700">Location: <span class="font-medium">{{ $demand->location }}</span></span>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-700">Delivery Date: <span class="font-medium">{{ $demand->delivery_date->format('M d, Y') }}</span></span>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-700">Posted: <span class="font-medium">{{ $demand->created_at->format('M d, Y H:i') }}</span></span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Match Summary</h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-white p-3 rounded shadow">
                            <div class="text-2xl font-bold text-indigo-600">{{ $demand->matches->count() }}</div>
                            <div class="text-sm text-gray-600">Total Matches</div>
                        </div>
                        <div class="bg-white p-3 rounded shadow">
                            <div class="text-2xl font-bold text-green-600">{{ $demand->matches->where('status', 'Matched')->count() }}</div>
                            <div class="text-sm text-gray-600">Accepted</div>
                        </div>
                        <div class="bg-white p-3 rounded shadow">
                            <div class="text-2xl font-bold text-yellow-600">{{ $demand->matches->where('status', 'Pending')->count() }}</div>
                            <div class="text-sm text-gray-600">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 ">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Product Matches</h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ $demand->matches->count() }} Matches Found
                </span>
            </div>

            @if($demand->matches->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-4 text-gray-600 font-medium">No matches found for this demand yet.</p>
                    <p class="text-gray-500 text-sm mt-2">The system will automatically find matches for your demand.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($demand->matches as $match)
                        <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-300 relative">
                            <!-- Delete Match Icon (X) in top right corner -->
                            <form action="{{ route('matches.destroy', $match) }}" method="POST" class="absolute top-3 right-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                                        onclick="return confirm('Are you sure you want to delete this match? This action cannot be undone.')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                            
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900">{{ $match->product->product_name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    @if($match->status == 'Matched') bg-green-100 text-green-800
                                    @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($match->status == 'New') bg-blue-100 text-blue-800
                                    @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $match->status }}
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-5">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Available Quantity:</span>
                                    <span class="font-medium">{{ $match->product->quantity }} {{ $match->product->unit }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Price:</span>
                                    <span class="font-medium text-green-600">₱{{ number_format($match->product->price, 2) }}/{{ $match->product->unit }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Harvest Date:</span>
                                    <span class="font-medium">{{ $match->product->harvest_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Status:</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        @if($match->product->status == 'Available') bg-green-100 text-green-800
                                        @elseif($match->product->status == 'Sold Out') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $match->product->status)) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-3 mb-3">
                                <a href="{{ route('buyer.products.show', $match->product) }}" class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                    View Product
                                </a>
                                <!-- Button to view farmer profile -->
                                <button type="button" 
                                        class="flex-1 text-center px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors view-profile-btn"
                                        data-first-name="{{ $match->product->farmer->user->first_name }}"
                                        data-last-name="{{ $match->product->farmer->user->last_name }}"
                                        data-email="{{ $match->product->farmer->user->email }}"
                                        data-phone="{{ $match->product->farmer->user->phone_number ?? 'N/A' }}"
                                        data-farm-name="{{ $match->product->farmer->farm_name ?? 'N/A' }}"
                                        data-product-type="{{ $match->product->farmer->product_type ?? 'N/A' }}"
                                        data-farm-address="{{ $match->product->farmer->farm_address ?? 'N/A' }}">
                                    View Profile
                                </button>
                            </div>
                            
                            @if($match->product->status == 'Sold Out')
                                <div class="flex space-x-3 mb-3">
                                    <button disabled class="w-full inline-block text-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                        Product Sold Out
                                    </button>
                                </div>
                            @else
                                <div class="flex space-x-3 mb-3">
                                    <form action="{{ route('matches.startConversation', $match) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full inline-block text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                            Message
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Farmer Profile Modal -->
<div id="farmerProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full max-w-2xl mx-4 relative">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Farmer Profile</h2>
            <button id="closeFarmerModalBtn" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" 
                             alt="Profile" class="w-24 h-24 rounded-full mx-auto object-cover">
                        <h3 id="modal-farmer-name" class="text-lg font-medium text-gray-900 mt-4"></h3>
                        <p class="text-gray-500 text-sm">Farmer</p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <p id="modal-first-name" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <p id="modal-last-name" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <p id="modal-email" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <p id="modal-phone" class="text-gray-900"></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Farmer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Farm Name</label>
                                    <p id="modal-farm-name" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                                    <p id="modal-product-type" class="text-gray-900"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Farm Address</label>
                                    <p id="modal-farm-address" class="text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    // Initialize modal functionality after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure modal is hidden initially
        var modal = document.getElementById('farmerProfileModal');
        if (modal) {
            modal.style.display = 'none';
        }

        // Add event listeners to all "View Profile" buttons
        var viewProfileButtons = document.querySelectorAll('.view-profile-btn');
        viewProfileButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get data from button attributes
                var firstName = this.getAttribute('data-first-name');
                var lastName = this.getAttribute('data-last-name');
                var email = this.getAttribute('data-email');
                var phone = this.getAttribute('data-phone');
                var farmName = this.getAttribute('data-farm-name');
                var productType = this.getAttribute('data-product-type');
                var farmAddress = this.getAttribute('data-farm-address');
                
                // Populate modal with data
                document.getElementById('modal-farmer-name').textContent = firstName + ' ' + lastName;
                document.getElementById('modal-first-name').textContent = firstName;
                document.getElementById('modal-last-name').textContent = lastName;
                document.getElementById('modal-email').textContent = email;
                document.getElementById('modal-phone').textContent = phone;
                document.getElementById('modal-farm-name').textContent = farmName;
                document.getElementById('modal-product-type').textContent = productType;
                document.getElementById('modal-farm-address').textContent = farmAddress;
                
                // Show modal
                if (modal) {
                    modal.style.display = 'flex';
                }
            });
        });

        // Add event listener to close button
        var closeBtn = document.getElementById('closeFarmerModalBtn');
        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }

        // Close modal when clicking outside of it
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal && modal.style.display === 'flex') {
                modal.style.display = 'none';
            }
        });


    });
</script>
@endsection