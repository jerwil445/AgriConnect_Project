@extends('layouts.farmers_page')

@section('title', 'Product Details • AgriConnect')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Product Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}" 
               class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700 shadow-sm hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <!-- Main Image Display -->
                <div class="relative mb-4">
                    @if($product->images->count() > 0)
                        <img id="mainImage" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="
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
                            {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}" 
                             class="w-full h-64 object-contain rounded-lg border border-gray-200">
                    @elseif($product->image)
                        <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="
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
                            {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}" 
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
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="
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
                                {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}" 
                                     class="w-full h-full object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                @elseif($product->image && $product->images->count() == 0)
                    <!-- For backward compatibility with single image -->
                    <div class="flex flex-wrap gap-2">
                        <div class="w-16 h-16 border-2 border-green-500 rounded">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="
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
                            {{ $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type)) }}" 
                                 class="w-full h-full object-cover rounded">
                        </div>
                    </div>
                @endif
            </div>

            <div class="md:col-span-2">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">
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
                                        </h3>
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
                        <p class="text-lg font-semibold">₱{{ number_format($product->price, 2) }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Harvest Date</p>
                        <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($product->harvest_date)->format('F d, Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Posted On</p>
                        <p class="text-lg font-semibold">{{ $product->created_at->format('F d, Y') }}</p>
                    </div>
                    
                    <!-- Display egg-specific attributes if this is an egg product -->
                    @if($product->egg_type)
                        <div>
                            <p class="text-sm text-gray-500">Egg Type</p>
                            <p class="text-lg font-semibold">
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
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Egg Sizes Table -->
                @if($product->sizes->count() > 0)
                <div class="mt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Egg Sizes & Pricing</h4>
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Size</th>
                                    <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Trays</th>
                                    <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Price per Tray</th>
                                    <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($product->sizes as $size)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        @php
                                            $sizeLabels = [
                                                'small' => 'Small',
                                                'medium' => 'Medium',
                                                'large' => 'Large',
                                                'extra_large' => 'Extra Large',
                                                'jumbo' => 'Jumbo'
                                            ];
                                        @endphp
                                        {{ $sizeLabels[$size->size_name] ?? ucfirst(str_replace('_', ' ', $size->size_name)) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $size->tray_count }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱{{ number_format($size->price_per_tray, 2) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱{{ number_format($size->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="py-3 pl-4 pr-3 text-right text-sm font-medium text-gray-900 sm:pl-6">Total:</td>
                                    <td class="px-3 py-3 text-sm font-semibold text-gray-900">₱{{ number_format($product->price, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Message Buyer button -->
<div class="fixed bottom-6 right-6">
    <a href="{{ route('farmer.messages') }}" 
       class="flex items-center gap-2 rounded-full bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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