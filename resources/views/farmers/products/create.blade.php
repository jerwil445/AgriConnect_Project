@extends('layouts.farmers_page')

@section('title', 'Add New Product • AgriConnect')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Add New Product</h2>
        <p class="text-sm text-gray-500 mt-1">Fill in the details below to add a new product listing</p>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                <input type="text" name="product_name" id="product_name" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('product_name') }}" required>
                @error('product_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                <input type="number" name="quantity" id="quantity" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('quantity') }}" min="1" required>
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
                    <option value="kilos" {{ old('unit') == 'kilos' ? 'selected' : '' }}>Kilograms (kg)</option>
                    <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                    <option value="bunches" {{ old('unit') == 'bunches' ? 'selected' : '' }}>Bunches</option>
                    <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>Boxes</option>
                    <option value="sacks" {{ old('unit') == 'sacks' ? 'selected' : '' }}>Sacks</option>
                </select>
                @error('unit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                <input type="number" name="price" id="price" step="0.01" min="0"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('price') }}" required>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest Date</label>
                <input type="date" name="harvest_date" id="harvest_date" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       value="{{ old('harvest_date') }}" required>
                @error('harvest_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Product Images (Up to 10)</label>
                <input type="file" name="images[]" id="images" multiple 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                       accept="image/*">
                <p class="mt-1 text-sm text-gray-500">You can select up to 10 images. The first image will be used as the primary image.</p>
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
                Add Product
            </button>
        </div>
    </form>
</div>
@endsection