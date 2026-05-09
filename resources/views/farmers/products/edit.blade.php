@extends('layouts.farmers_page')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-4 lg:px-6 py-4 lg:py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Edit Product</h2>
            </div>

            <form action="{{ route('farmer.products.update', $product) }}" method="POST" enctype="multipart/form-data"
                class="p-4 lg:p-6 space-y-6 lg:space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8">
                    <div class="space-y-4 lg:space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Agricultural Category</label>
                                <select name="category" id="category"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                    <option value="" disabled>Select a category</option>
                                    @foreach($registeredCategories as $category)
                                        <option value="{{ $category }}" {{ old('category', $product->category) === $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                <select name="product_name" id="product_name"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    data-old-value="{{ old('product_name', $product->product_name) }}"
                                    required>
                                    <option value="" disabled>Select a category first</option>
                                    {{-- Populated via JavaScript --}}
                                </select>
                                @error('product_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Hidden product mapping for JS --}}
                        <script id="product-mapping-data" type="application/json">
                            {!! json_encode($allProductMapping) !!}
                        </script>

                        @php
                            $isCustom = true;
                            if (isset($allProductMapping[$product->category])) {
                                foreach ($allProductMapping[$product->category] as $subProducts) {
                                    if (in_array($product->product_name, $subProducts)) {
                                        $isCustom = false;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        <div id="other_product_container" class="{{ $isCustom ? '' : 'hidden' }}">
                            <label for="other_product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name (Custom)</label>
                            <input type="text" name="other_product_name" id="other_product_name" 
                                value="{{ $isCustom ? $product->product_name : '' }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                placeholder="Enter specific product name">
                            @error('other_product_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="variety" class="block text-sm font-medium text-gray-700 mb-1">Variety</label>
                                <input type="text" name="variety" id="variety" value="{{ old('variety', $product->variety) }}"
                                    placeholder="e.g., Dinorado, Wagwag, Cavendish"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('variety')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="size_grade" class="block text-sm font-medium text-gray-700 mb-1">Size / Grade</label>
                                <input type="text" name="size_grade" id="size_grade" value="{{ old('size_grade', $product->size_grade) }}"
                                    placeholder="e.g., Large, Grade A, Premium"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('size_grade')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Replace Product Images</label>
                            <div
                                class="mt-2 rounded-2xl border border-gray-200 bg-gradient-to-br from-green-50 via-white to-emerald-50 p-4">
                                <div id="image-upload-dropzone"
                                    class="relative overflow-hidden rounded-2xl border-2 border-dashed border-green-200 bg-white/90 p-6 transition duration-200 hover:border-green-400 hover:bg-green-50/60">
                                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                                        class="sr-only" onchange="previewImages(this)">

                                    <label for="images" class="block cursor-pointer">
                                        <div class="mx-auto flex max-w-lg flex-col items-center text-center">
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-100 text-green-700 shadow-sm">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                        d="M3 15.75V16.5A2.25 2.25 0 005.25 18.75h13.5A2.25 2.25 0 0021 16.5v-.75M7.5 10.5L12 6m0 0l4.5 4.5M12 6v9">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h3 class="mt-4 text-base font-semibold text-gray-900">Drop replacement images
                                                here</h3>
                                            <p class="mt-1 text-sm text-gray-500">or click to browse from your device</p>
                                            <div
                                                class="mt-4 inline-flex items-center rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                                                Choose New Images
                                            </div>
                                            <div
                                                class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-gray-500">
                                                <span
                                                    class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">Replaces
                                                    current gallery</span>
                                                <span
                                                    class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">Up
                                                    to 10 images</span>
                                                <span
                                                    class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">First
                                                    image = cover</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div id="image-selection-meta"
                                    class="mt-4 hidden items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-3">
                                    <div>
                                        <p id="selected-image-count" class="text-sm font-semibold text-gray-900">0 images
                                            selected</p>
                                        <p id="image-limit-note" class="text-xs text-gray-500">Uploading new images will
                                            replace the current gallery.</p>
                                    </div>
                                    <button type="button" id="clear-selected-images"
                                        class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                        Clear All
                                    </button>
                                </div>
                            </div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div id="image-preview" class="mt-4 hidden">
                                <div class="mb-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">New Images</p>
                                        <p class="text-xs text-gray-500">Saving will replace the current gallery with the
                                            images below.</p>
                                    </div>
                                </div>
                                <div id="preview-container" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                </div>
                            </div>

                            @if ($product->images->count() > 0)
                                <div class="mt-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">Current Images</p>
                                            <p class="text-xs text-gray-500">These stay in place unless you upload a
                                                replacement gallery.</p>
                                        </div>
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                            {{ $product->images->count() }} saved
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                        @foreach ($product->images as $image)
                                            <div
                                                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                                                <div class="relative h-28 overflow-hidden bg-gray-100">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        alt="{{ $product->product_name }}"
                                                        class="h-full w-full object-cover">
                                                    @if ($loop->first)
                                                        <span
                                                            class="absolute bottom-2 left-2 rounded-full bg-gray-900 px-3 py-1 text-xs font-semibold text-white shadow">
                                                            Current Cover
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Available Quantity</label>
                                @php
                                    $availableQty = $product->remainingInventory 
                                        ? $product->remainingInventory->remaining_quantity 
                                        : $product->quantity;
                                    $soldQty = $product->remainingInventory 
                                        ? ($product->remainingInventory->original_quantity - $product->remainingInventory->remaining_quantity) 
                                        : 0;
                                @endphp
                                <input type="number" name="quantity" id="quantity"
                                    value="{{ old('quantity', $availableQty) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">Enter the current number of units available for sale.</p>
                                @if($soldQty > 0)
                                    <p class="text-xs text-amber-600 font-medium italic mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        You have already sold {{ $soldQty }} {{ $product->unit }}{{ $soldQty > 1 ? 's' : '' }}.
                                    </p>
                                @endif
                                <p id="quantity-error" class="mt-1 text-sm text-red-600 hidden">Quantity cannot be negative.</p>
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                <select name="unit" id="unit"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                    @foreach (['kg', 'sack', 'tray', 'box', 'bundle', 'piece', 'dozen', 'liter'] as $unit)
                                        <option value="{{ $unit }}"
                                            {{ old('unit', $product->unit) === $unit ? 'selected' : '' }}>
                                            {{ ucfirst($unit) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price per Unit
                                    (₱)</label>
                                <input type="number" name="price" id="price"
                                    value="{{ old('price', $product->price) }}" step="0.01"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                <p id="price-error" class="mt-1 text-sm text-red-600 hidden">Price per unit cannot be negative.</p>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_amount_display"
                                    class="block text-sm font-medium text-gray-700 mb-1">Total Amount (₱)</label>
                                <input type="text" id="total_amount_display"
                                    value="{{ number_format((float) old('quantity', $product->quantity) * (float) old('price', $product->price), 2) }}"
                                    class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm" readonly>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest
                                    Date</label>
                                <input type="date" name="harvest_date" id="harvest_date"
                                    value="{{ old('harvest_date', optional($product->harvest_date)->format('Y-m-d')) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                @error('harvest_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                    @foreach (['Available', 'Pending', 'Sold Out'] as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $product->status) === $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Address</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="purok_street"
                                        class="block text-xs text-gray-500 mb-1">Purok/Street</label>
                                    <input type="text" name="purok_street" id="purok_street"
                                        value="{{ old('purok_street', $product->purok_street) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label for="barangay" class="block text-xs text-gray-500 mb-1">Barangay</label>
                                    <input type="text" name="barangay" id="barangay"
                                        value="{{ old('barangay', $product->barangay) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label for="municipality_city"
                                        class="block text-xs text-gray-500 mb-1">Municipality/City</label>
                                    <input type="text" name="municipality_city" id="municipality_city"
                                        value="{{ old('municipality_city', $product->municipality_city) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label for="province" class="block text-xs text-gray-500 mb-1">Province</label>
                                    <input type="text" name="province" id="province"
                                        value="{{ old('province', $product->province) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                            </div>
                        </div>

                        @if ($product->remainingInventory)
                            <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                                <h3 class="text-sm font-semibold text-gray-800 mb-2">Remaining Inventory Snapshot</h3>
                                <p class="text-sm text-gray-600">Remaining Quantity: <span
                                        class="font-semibold text-gray-900">{{ $product->remainingInventory->remaining_quantity }}</span>
                                </p>
                                <p class="text-sm text-gray-600">Remaining Total Amount: <span
                                        class="font-semibold text-gray-900">₱{{ number_format($product->remainingInventory->remaining_price, 2) }}</span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('farmer.products.index') }}"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    @vite('resources/js/farmer/products/farmer-products-edit.js')

@endsection

