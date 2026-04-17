@extends('layouts.farmers_page')

@section('content')
    <div class="ml-64">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-4 lg:px-6 py-4 lg:py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Add New Product</h2>
            </div>

            <form action="{{ route('farmer.products.store') }}" method="POST" enctype="multipart/form-data"
                class="p-4 lg:p-6 space-y-6 lg:space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8">
                    <div class="space-y-4 lg:space-y-6">
                        <div>
                            <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product
                                Name</label>
                            <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                required>
                            @error('product_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="variety_size"
                                class="block text-sm font-medium text-gray-700 mb-1">Variety/Size</label>
                            <input type="text" name="variety_size" id="variety_size" value="{{ old('variety_size') }}"
                                placeholder="Large, Medium, Grade A, Bundle, etc."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('variety_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                placeholder="Describe the product quality, packaging, or harvest notes.">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Images</label>
                            <div
                                class="mt-2 rounded-2xl border border-gray-200 bg-gradient-to-br from-green-50 via-white to-emerald-50 p-4">
                                <div id="image-upload-dropzone"
                                    class="relative overflow-hidden rounded-2xl border-2 border-dashed border-green-200 bg-white/90 p-6 transition duration-200 hover:border-green-400 hover:bg-green-50/60">
                                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="sr-only"
                                        onchange="previewImages(this)">

                                    <label for="images" class="block cursor-pointer">
                                        <div class="mx-auto flex max-w-lg flex-col items-center text-center">
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-100 text-green-700 shadow-sm">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                        d="M3 15.75V16.5A2.25 2.25 0 005.25 18.75h13.5A2.25 2.25 0 0021 16.5v-.75M7.5 10.5L12 6m0 0l4.5 4.5M12 6v9">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h3 class="mt-4 text-base font-semibold text-gray-900">Drop product images here
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500">or click to browse from your device</p>
                                            <div
                                                class="mt-4 inline-flex items-center rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                                                Choose Images
                                            </div>
                                            <div
                                                class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-gray-500">
                                                <span
                                                    class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">Up
                                                    to 10 images</span>
                                                <span
                                                    class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">JPG,
                                                    PNG, GIF</span>
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
                                        <p id="image-limit-note" class="text-xs text-gray-500">Up to 10 images. First image
                                            becomes the cover.</p>
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
                                        <p class="text-sm font-semibold text-gray-800">Selected Images</p>
                                        <p class="text-xs text-gray-500">Arrange by reselecting files. The first image will
                                            be used as the cover.</p>
                                    </div>
                                </div>
                                <div id="preview-container" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
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
                                    @foreach (['pieces', 'trays', 'dozen', 'kilos', 'boxes', 'bunches', 'sacks'] as $unit)
                                        <option value="{{ $unit }}" {{ old('unit') === $unit ? 'selected' : '' }}>
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
                                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                <p id="price-error" class="mt-1 text-sm text-red-600 hidden">Price per unit cannot be negative.</p>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_amount_display" class="block text-sm font-medium text-gray-700 mb-1">Total
                                    Amount (₱)</label>
                                <input type="text" id="total_amount_display" value="0.00"
                                    class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm" readonly>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest
                                    Date</label>
                                <input type="date" name="harvest_date" id="harvest_date" value="{{ old('harvest_date') }}"
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
                                        <option value="{{ $status }}" {{ old('status', 'Available') === $status ? 'selected' : '' }}>
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
                                    <label for="purok_street" class="block text-xs text-gray-500 mb-1">Purok/Street</label>
                                    <input type="text" name="purok_street" id="purok_street"
                                        value="{{ old('purok_street') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label for="barangay" class="block text-xs text-gray-500 mb-1">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" value="{{ old('barangay') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label for="municipality_city"
                                        class="block text-xs text-gray-500 mb-1">Municipality/City</label>
                                    <input type="text" name="municipality_city" id="municipality_city"
                                        value="{{ old('municipality_city') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label for="province" class="block text-xs text-gray-500 mb-1">Province</label>
                                    <input type="text" name="province" id="province" value="{{ old('province') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('farmer.products.index') }}"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    @vite('resources/js/farmer/products/farmer-products-create.js')

@endsection