@extends('layouts.farmers_page')

@section('title', 'Edit Product • AgriConnect')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Edit Product</h2>
        <p class="text-sm text-gray-500 mt-1">Update your product details</p>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                <input type="text" name="product_name" id="product_name" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('product_name', $product->product_name) }}" required>
                @error('product_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                          placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Provide details about the product quality, farming methods, etc.</p>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                <input type="number" name="quantity" id="quantity" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('quantity', $product->quantity) }}" min="1" required>
                @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                <select name="unit" id="unit" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        required>
                    <option value="">Select Unit</option>
                    <option value="kilos" {{ (old('unit', $product->unit) == 'kilos') ? 'selected' : '' }}>Kilograms (kg)</option>
                    <option value="pieces" {{ (old('unit', $product->unit) == 'pieces') ? 'selected' : '' }}>Pieces</option>
                    <option value="bunches" {{ (old('unit', $product->unit) == 'bunches') ? 'selected' : '' }}>Bunches</option>
                    <option value="boxes" {{ (old('unit', $product->unit) == 'boxes') ? 'selected' : '' }}>Boxes</option>
                    <option value="sacks" {{ (old('unit', $product->unit) == 'sacks') ? 'selected' : '' }}>Sacks</option>
                </select>
                @error('unit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                <input type="number" name="price" id="price" step="0.01" min="0"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest Date</label>
                <input type="date" name="harvest_date" id="harvest_date" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('harvest_date', $product->harvest_date) }}" required>
                @error('harvest_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Images</label>
                
                <!-- Current Images Display -->
                @if($product->images->count() > 0)
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 font-medium mb-2">Current Images:</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->product_name }}" class="h-24 w-24 object-cover rounded-md border border-gray-200">
                                    @if($image->is_primary)
                                        <span class="absolute top-0 left-0 bg-green-500 text-white text-xs px-1 rounded-br rounded-tl">Primary</span>
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-md flex items-center justify-center">
                                        <span class="text-white text-xs text-center px-1">Existing Image</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($product->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 font-medium mb-2">Current Primary Image:</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="h-24 w-24 object-cover rounded-md border border-gray-200">
                        </div>
                    </div>
                @endif
                
                <!-- Upload New Images -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:border-green-400 transition-colors cursor-pointer" 
                     onclick="document.getElementById('images').click()">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                <span>Upload new images</span>
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        <p class="text-xs text-gray-500 mt-1">You can select up to 10 images. The first image will be used as the primary image.</p>
                    </div>
                    <input type="file" name="images[]" id="images" multiple 
                           class="sr-only" accept="image/*">
                </div>
                <p class="mt-2 text-sm text-gray-500">Click or drag images to this area to upload new images</p>
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('products.index') }}" 
               class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Cancel
            </a>
            <button type="submit" 
                    class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Update Product
            </button>
        </div>
    </form>
</div>
@endsection