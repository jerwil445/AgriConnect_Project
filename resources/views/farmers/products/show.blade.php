@extends('layouts.farmers_page')

@section('title', 'Product Details • AgriConnect')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Product Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}"
                class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700 shadow-sm hover:bg-red-100">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
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
                    @if($product->variety_size)
                        <p class="mt-1 text-lg text-gray-600">{{ $product->variety_size }}</p>
                    @endif
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
                        <p class="text-lg font-semibold">{{ $product->remainingInventory?->remaining_quantity ?? $product->quantity }} {{ $product->unit }}</p>
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
                    <div>
                        <p class="text-sm text-gray-500">Posted On</p>
                        <p class="text-lg font-semibold">{{ $product->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="text-lg font-semibold">
                            {{ collect([$product->purok_street, $product->barangay, $product->municipality_city, $product->province])->filter()->implode(', ') ?: 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fixed bottom-6 right-6">
    <a href="{{ route('farmer.messages') }}"
        class="flex items-center gap-2 rounded-full bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
        <i class="fas fa-comments"></i> Messages
        @if($unreadMessageCount > 0)
            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $unreadMessageCount }}
            </span>
        @endif
    </a>
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
