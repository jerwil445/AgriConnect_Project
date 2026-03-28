@extends('layouts.farmers_page')

@section('content')
<div class="ml-64">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Product Matches</h1>
        <a href="{{ route('farmer.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">
            &larr; Back to Dashboard
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

    @if($products->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-gray-600">You don't have any products listed yet.</p>
            <a href="{{ route('farmer.products.create') }}" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Add Your First Product
            </a>
        </div>
    @else
        <!-- Grid layout for products -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">
                                                                {{ $product->product_name ?: $product->egg_type ?: 'N/A' }}
                                                            </h2>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if($product->variety_size)
                                    <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">
                                        {{ $product->variety_size }}
                                    </span>
                                    @endif
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                        {{ $product->quantity }} {{ $product->unit }} available
                                    </span>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                        ₱{{ number_format($product->price, 2) }}/{{ $product->unit }}
                                    </span>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">
                                        Harvest: {{ $product->harvest_date->format('M d, Y') }}
                                    </span>
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                                        Status: {{ $product->status }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        
                        <div class="mt-2">
                            <div class="text-left pt-2">
                                            <a href="{{ route('farmer.product.matches', $product) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                View All {{ $product->matches->count() }} Matches &rarr;
                                            </a>
                                        </div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-bold text-lg text-gray-800">Buyer Demands</h3>
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 text-xs font-bold">
                                    {{ $product->matches->count() }}
                                </span>
                            </div>
                            
                            @if($product->matches->isEmpty())
                                <div class="text-center py-4 bg-gray-50 rounded">
                                    <p class="text-gray-600">No buyer demands match this product yet.</p>
                                </div>
                            @else
                                <!-- Improved matches display -->
                                <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                    @foreach($product->matches->take(3) as $match)
                                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                                            <div class="flex justify-between">
                                                <span class="font-medium text-sm">
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
                                                                                            </span>
                                                <span class="text-xs px-2 py-1 rounded 
                                                    @if($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($match->status == 'Matched') bg-green-100 text-green-800
                                                    @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                                    @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $match->status }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-600 mt-1">
                                                <div>
                                                    <div>{{ $match->demand->quantity }} {{ $match->demand->unit }}</div>
                                                    <!-- Egg Size Information -->
                                                    @if($match->demand->egg_size)
                                                        <div class="text-xs">
                                                            Size: {{ $match->demand->egg_size }}
                                                        </div>
                                                    @endif
                                                    <!-- Address Information -->
                                                    @if($match->demand->purok_street || $match->demand->barangay || $match->demand->municipality_city || $match->demand->province)
                                                        <div class="truncate max-w-[100px] text-xs">
                                                            @if($match->demand->purok_street)
                                                                {{ $match->demand->purok_street }}
                                                            @endif
                                                            @if($match->demand->barangay)
                                                                {{ $match->demand->barangay }}
                                                            @endif
                                                            @if($match->demand->municipality_city)
                                                                {{ $match->demand->municipality_city }}
                                                            @endif
                                                            @if($match->demand->province)
                                                                {{ $match->demand->province }}
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div>{{ $match->demand->location }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($product->matches->count() > 3)
                                        <div class="text-center pt-2">
                                            <a href="{{ route('farmer.product.matches', $product) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
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
        
        <!-- Pagination or load more button if needed -->
        @if($products->count() > 9)
            <div class="mt-6 text-center">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Load More Products
                </button>
            </div>
        @endif
    @endif
</div>
@endsection
