@extends('layouts.farmers_page')

@section('content')
<div class=" mx-auto px-4 py-4 buyer-content">
    <div class="shadow-sm  ml-64">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Matches for 
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
                                        {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}
                                    </h1>
            <a href="{{ route('farmer.matches') }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Back to All Matches
            </a>
        </div>

        <!-- Display notifications -->
        @if(auth()->user()->unreadNotifications->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>{{ auth()->user()->unreadNotifications->count() }}</strong> new notification(s)
                            <a href="{{ route('farmer.notifications') }}" class="font-medium underline">View all notifications</a>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-md rounded-lg p-5 sticky top-6">
                        <div class="flex justify-between items-start mb-4">
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
                                                        {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}
                                                    </h2>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                @if($product->status == 'Available') bg-green-100 text-green-800
                                @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-gray-700">Available Quantity: <span class="font-medium">{{ $product->quantity }} {{ $product->unit }}</span></span>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-700">Price: <span class="font-medium">₱{{ number_format($product->price, 2) }}/{{ $product->unit }}</span></span>
                            </div>
                            
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
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-700">Harvest Date: <span class="font-medium">{{ $product->harvest_date->format('M d, Y') }}</span></span>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-700">Posted: <span class="font-medium">{{ $product->created_at->format('M d, Y H:i') }}</span></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mt-6">
                        <h3 class="font-bold text-lg text-gray-800 mb-3">Match Summary</h3>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-white p-3 rounded shadow">
                                <div class="text-2xl font-bold text-indigo-600">{{ $product->matches->count() }}</div>
                                <div class="text-sm text-gray-600">Total Matches</div>
                            </div>
                            <div class="bg-white p-3 rounded shadow">
                                <div class="text-2xl font-bold text-green-600">{{ $product->matches->where('status', 'Matched')->count() }}</div>
                                <div class="text-sm text-gray-600">Accepted</div>
                            </div>
                            <div class="bg-white p-3 rounded shadow">
                                <div class="text-2xl font-bold text-yellow-600">{{ $product->matches->where('status', 'Pending')->count() }}</div>
                                <div class="text-sm text-gray-600">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Buyer Demands</h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ $product->matches->count() }} Matches Found
                </span>
            </div>

            @if($product->matches->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-4 text-gray-600 font-medium">No matches found for this product yet.</p>
                    <p class="text-gray-500 text-sm mt-2">The system will automatically find matches for your product.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($product->matches as $match)
                        @if($match->demand && $match->demand->buyer)
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
                                    <h3 class="font-bold text-lg text-gray-900">
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
                                                                {{ $eggTypes[$match->demand->egg_type] ?? ucfirst(str_replace('_', ' ', $match->demand->egg_type)) }}
                                                            </h3>
                                    <p class="text-gray-600 text-sm">{{ $match->demand->buyer->first_name ?? '' }} {{ $match->demand->buyer->last_name ?? '' }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    @if($match->status == 'Matched') bg-green-100 text-green-800
                                    @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($match->status == 'New') bg-blue-100 text-blue-800
                                    @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                    @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $match->status }}
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-5">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Required Quantity:</span>
                                    <span class="font-medium">{{ $match->demand->quantity }} {{ $match->demand->unit ?? 'units' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Delivery Date:</span>
                                    <span class="font-medium">{{ $match->demand->delivery_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Delivery Location:</span>
                                    <span class="font-medium">{{ $match->demand->location }}</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2 mb-3">
                                <a href="{{ route('products.show', $match->product) }}" class="flex-1 text-center px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                                    View Product
                                </a>
                                
                                <!-- Button to view buyer profile -->
                                <button type="button" 
                                        class="flex-1 text-center px-3 py-1 bg-blue-100 text-blue-700 text-sm rounded hover:bg-blue-200"
                                        onclick="openBuyerModal({{ $match->demand->buyer->id ?? 0 }}, '{{ $match->demand->buyer->first_name ?? '' }}', '{{ $match->demand->buyer->last_name ?? '' }}', '{{ $match->demand->buyer->email ?? '' }}', '{{ $match->demand->buyer->phone_number ?? 'N/A' }}', '{{ $match->demand->buyer->company_name ?? 'N/A' }}', '{{ $match->demand->buyer->business_type ?? 'N/A' }}', '{{ $match->demand->buyer->address ?? 'N/A' }}')">
                                    View Profile
                                </button>
                            </div>
                            
                            @if($product->status == 'Sold Out')
                                <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    Product Sold Out
                                </button>
                            @elseif($match->status == 'New')
                                <div class="flex gap-2">
                                    <form action="{{ route('matches.accept', $match) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('matches.reject', $match) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2"
                                                onclick="return confirm('Are you sure you want to decline this match?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            @elseif($match->status == 'Matched' || $match->status == 'Transaction Started')
                                <form action="{{ route('matches.startTransaction', $match) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Message Buyer
                                    </button>
                                </form>
                            @elseif($match->status == 'Rejected')
                                <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    Match Declined
                                </button>
                            @else
                                <form action="{{ route('matches.startTransaction', $match) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Message
                                    </button>
                                </form>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Buyer Profile Modal -->
<div id="buyerProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full max-w-2xl mx-4">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Buyer Profile</h2>
            <button onclick="closeBuyerModal()" class="text-gray-500 hover:text-gray-700">
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
                        <h3 id="modal-buyer-name" class="text-lg font-medium text-gray-900 mt-4"></h3>
                        <p class="text-gray-500 text-sm">Buyer</p>
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
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Buyer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                                    <p id="modal-company" class="text-gray-900"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                                    <p id="modal-business-type" class="text-gray-900"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <p id="modal-address" class="text-gray-900"></p>
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
    function openBuyerModal(buyerId, firstName, lastName, email, phone, company, businessType, address) {
        // Set the modal content
        document.getElementById('modal-buyer-name').textContent = firstName + ' ' + lastName;
        document.getElementById('modal-first-name').textContent = firstName;
        document.getElementById('modal-last-name').textContent = lastName;
        document.getElementById('modal-email').textContent = email;
        document.getElementById('modal-phone').textContent = phone;
        document.getElementById('modal-company').textContent = company;
        document.getElementById('modal-business-type').textContent = businessType;
        document.getElementById('modal-address').textContent = address;
        
        // Show the modal
        document.getElementById('buyerProfileModal').classList.remove('hidden');
        document.getElementById('buyerProfileModal').classList.add('flex');
    }
    
    function closeBuyerModal() {
        // Hide the modal
        document.getElementById('buyerProfileModal').classList.add('hidden');
        document.getElementById('buyerProfileModal').classList.remove('flex');
    }
    
    // Close modal when clicking outside
    document.getElementById('buyerProfileModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeBuyerModal();
        }
    });
</script>
@endsection