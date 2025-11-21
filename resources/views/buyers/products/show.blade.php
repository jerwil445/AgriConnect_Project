@extends('layouts.buyers_page')

@section('content')
    
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Product Details</h2>
                <div class="flex gap-2">
                    <a href="{{ route('buyer.dashboard') }}" 
                       class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Products
                    </a>
                </div>
            </div> -->
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Product Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('buyer.dashboard') }}" 
               class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-arrow-left mr-1"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <!-- Main Image Display -->
                <div class="relative mb-4">
                    @if($product->images->count() > 0)
                        <img id="mainImage" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->product_name }}" 
                             class="w-full h-64 object-contain rounded-lg border border-gray-200">
                    @elseif($product->image)
                        <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" 
                             class="w-full h-64 object-contain rounded-lg border border-gray-200">
                    @else
                        <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Thumbnails -->
                @if($product->images->count() > 1)
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->images as $image)
                            <div class="w-16 h-16 border-2 border-transparent hover:border-green-500 rounded cursor-pointer thumbnail" 
                                 onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->product_name }}" 
                                     class="w-full h-full object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                @elseif($product->image && $product->images->count() == 0)
                    <!-- For backward compatibility with single image -->
                    <div class="flex flex-wrap gap-2">
                        <div class="w-16 h-16 border-2 border-green-500 rounded">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" 
                                 class="w-full h-full object-cover rounded">
                        </div>
                    </div>
                @endif
            </div>

            <div class="md:col-span-2">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $product->product_name }}</h3>
                    <div class="mt-2 flex items-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($product->status == 'available') bg-green-100 text-green-800
                            @elseif($product->status == 'sold_out') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Quantity</p>
                        <p class="text-lg font-semibold">{{ $product->quantity }} {{ $product->unit }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="text-lg font-semibold">${{ number_format($product->price, 2) }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Harvest Date</p>
                        <p class="text-lg font-semibold">{{ $product->harvest_date?->format('F d, Y') ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Posted On</p>
                        <p class="text-lg font-semibold">{{ $product->created_at->format('F d, Y') }}</p>
                    </div>
                </div>

                <!-- Farmer Information -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Farmer Information</h4>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $product->farmer->user->first_name ?? '' }} {{ $product->farmer->user->last_name ?? '' }}</p>
                            <p class="text-sm text-gray-500">{{ $product->farmer->farm_name ?? 'Farm Name' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="text-sm font-medium text-gray-900">{{ $product->farmer->user->address ?? 'Address' }}, {{ $product->farmer->user->state ?? 'State' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Contact</p>
                            <p class="text-sm font-medium text-gray-900">{{ $product->farmer->user->phone_number ?? 'Phone Number' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <button class="flex-1 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
                    <button class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-envelope mr-2"></i> Contact Farmer
                    </button>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</body>

<script>
    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = imageSrc;
        }
        
        // Update thumbnail borders to show which is active
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach(thumb => {
            thumb.classList.remove('border-green-500');
            thumb.classList.add('border-transparent');
        });
        
        // Find the clicked thumbnail and highlight it
        event.currentTarget.classList.remove('border-transparent');
        event.currentTarget.classList.add('border-green-500');
    }
</script>

@endsection