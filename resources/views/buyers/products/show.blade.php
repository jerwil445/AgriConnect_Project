@extends('layouts.buyers_page')

@section('content')
    
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
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
                                         onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')" >
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
                            
                            <!-- Egg Type -->
                            @if($product->egg_type)
                            <div class="mt-3">
                                <p class="text-sm text-gray-500">Egg Type</p>
                                <p class="text-xl font-semibold">
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
                                </p>
                            </div>
                            @endif
                            
                            <div class="mt-2 flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($product->status == 'Available') bg-green-100 text-green-800
                                    @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </div>
                            
                            @if($product->description)
                                <p class="mt-2 text-gray-600">{{ $product->description }}</p>
                            @endif

                        <!-- Egg Sizes -->
                        @if($product->sizes->count() > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Available Sizes</h4>
                            <div class="flex flex-wrap gap-2">
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
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <span>{{ $sizeLabels[$size->size_name] ?? ucfirst(str_replace('_', ' ', $size->size_name)) }}</span>
                                        <span class="mx-1">•</span>
                                        <span>{{ $size->tray_count }} trays</span>
                                        <span class="mx-1">•</span>
                                        <span>₱{{ number_format($size->price_per_tray, 2) }}/tray</span>
                                        <span class="mx-1">•</span>
                                        <span>Total: ₱{{ number_format($size->total_price, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mt-5">Original Quantity</p>
                                <p class="text-lg font-semibold">{{ $product->quantity }} {{ $product->unit }}</p>
                                @if($product->remainingInventory)
                                    <p class="text-sm text-gray-500 mt-2">Remaining Quantity</p>
                                    <p class="text-lg font-semibold text-green-600">{{ $product->remainingInventory->remaining_quantity }} {{ $product->unit }}</p>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 mt-5">Original Price</p>
                                <p class="text-lg font-semibold">₱{{ number_format($product->price, 2) }}</p>
                                @if($product->remainingInventory)
                                    <p class="text-sm text-gray-500 mt-2">Remaining Price</p>
                                    <p class="text-lg font-semibold text-green-600">₱{{ number_format($product->remainingInventory->remaining_price, 2) }}</p>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Harvest Date</p>
                                <p class="text-lg font-semibold">{{ $product->harvest_date?->format('F d, Y') ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Posted On</p>
                                <p class="text-lg font-semibold">{{ $product->created_at->format('F d, Y') }}</p>
                            </div>
                            
                            <!-- Product Address Information -->
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500">Product Location</p>
                                <p class="text-lg font-semibold">
                                    @if($product->purok_street)
                                        {{ $product->purok_street }}
                                    @endif
                                    @if($product->barangay)
                                        {{ $product->barangay }}
                                    @endif
                                    @if($product->municipality_city)
                                        {{ $product->municipality_city }}
                                    @endif
                                    @if($product->province)
                                        {{ $product->province }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Egg Sizes & Pricing Table -->
                        @if($product->sizes->count() > 0)
                        <div class="mt-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Egg Sizes & Pricing</h4>
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Size</th>
                                            <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Original Trays</th>
                                            <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Remaining Trays</th>
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
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                @if($product->remainingInventory)
                                                    @php
                                                        $remainingTrays = 0;
                                                        if($product->remainingInventory->per_size_remaining) {
                                                            foreach($product->remainingInventory->per_size_remaining as $remainingSize) {
                                                                if($remainingSize['size_id'] == $size->id) {
                                                                    $remainingTrays = $remainingSize['remaining_tray_count'];
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="font-semibold text-green-600">{{ $remainingTrays }}</span>
                                                @else
                                                    {{ $size->tray_count }}
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱{{ number_format($size->price_per_tray, 2) }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">₱{{ number_format($size->total_price, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="py-3 pl-4 pr-3 text-right text-sm font-medium text-gray-900 sm:pl-6">Original Total:</td>
                                            <td class="px-3 py-3 text-sm font-semibold text-gray-900">₱{{ number_format($product->price, 2) }}</td>
                                        </tr>
                                        @if($product->remainingInventory)
                                        <tr>
                                            <td colspan="4" class="py-3 pl-4 pr-3 text-right text-sm font-medium text-gray-900 sm:pl-6">Remaining Total:</td>
                                            <td class="px-3 py-3 text-sm font-semibold text-green-600">₱{{ number_format($product->remainingInventory->remaining_price, 2) }}</td>
                                        </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif
                            
                            
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
                                    @if($product->farmer->farm_address)
                                    <p class="text-sm font-medium text-gray-900 mt-1">Farm Address: {{ $product->farmer->farm_address }}</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Contact</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $product->farmer->user->phone_number ?? 'Phone Number' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            @if($product->status == 'Sold Out')
                                <button disabled 
                                   class="flex-1 rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-500 cursor-not-allowed text-center">
                                    <i class="fas fa-envelope mr-2"></i> Product Sold Out
                                </button>
                            @else
                                <form action="{{ route('buyer.message-farmer', $product) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-center">
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
</div>
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