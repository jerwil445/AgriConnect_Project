@extends('layouts.buyers_page')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Product Details</h2>
            <a href="{{ route('buyer.dashboard') }}"
                class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-1"></i> Back to Products
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
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

                    @if($product->images->count() > 1)
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->images as $image)
                                <div class="w-16 h-16 border-2 border-transparent hover:border-green-500 rounded cursor-pointer"
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->product_name }}"
                                        class="w-full h-full object-cover rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $product->product_name }}</h3>
                        <p class="mt-1 text-lg text-gray-600">{{ $product->variety_size ?: 'No variety/size specified' }}</p>
                        <div class="mt-3">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($product->status == 'Available') bg-green-100 text-green-800
                                @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $product->status }}
                            </span>
                        </div>
                        @if($product->description)
                            <p class="mt-4 text-gray-600">{{ $product->description }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Original Quantity</p>
                            <p class="text-lg font-semibold">{{ $product->quantity }} {{ $product->unit }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Remaining Quantity</p>
                            <p class="text-lg font-semibold text-green-600">{{ $product->remainingInventory?->remaining_quantity ?? $product->quantity }} {{ $product->unit }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Price per Unit</p>
                            <p class="text-lg font-semibold">₱{{ number_format($product->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="text-lg font-semibold">₱{{ number_format($product->total_amount ?? ($product->quantity * $product->price), 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Remaining Total Amount</p>
                            <p class="text-lg font-semibold text-green-600">₱{{ number_format($product->remainingInventory?->remaining_price ?? ($product->total_amount ?? ($product->quantity * $product->price)), 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Harvest Date</p>
                            <p class="text-lg font-semibold">{{ optional($product->harvest_date)->format('F d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Product Location</p>
                            <p class="text-lg font-semibold">
                                {{ collect([$product->purok_street, $product->barangay, $product->municipality_city, $product->province])->filter()->implode(', ') ?: 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
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
                                <p class="text-sm text-gray-500">Contact</p>
                                <p class="text-sm font-medium text-gray-900">{{ $product->farmer->user->phone_number ?? 'Phone Number not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Farm Address</p>
                                <p class="text-sm font-medium text-gray-900">{{ $product->farmer->farm_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($product->status == 'Sold Out')
                            <button disabled
                                class="flex-1 rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-500 cursor-not-allowed text-center">
                                <i class="fas fa-envelope mr-2"></i> Product Sold Out
                            </button>
                        @else
                            <form action="{{ route('buyer.message-farmer', $product) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 text-center">
                                    <i class="fas fa-envelope mr-2"></i> Message Farmer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = imageSrc;
        }
    }
</script>
@endsection
