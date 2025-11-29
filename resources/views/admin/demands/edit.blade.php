@extends('layouts.admin_page')

@section('content')
<div class="md:ml-64 mt-16 flex flex-col min-h-screen">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Demand</h2>
                    <a href="{{ route('admin.demands.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Demands
                    </a>
                </div>

                <form action="{{ route('admin.demands.update', $demand) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                                <input type="text" name="product_name" id="product_name" 
                                       value="{{ old('product_name', $demand->product_name) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                @error('product_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" 
                                           value="{{ old('quantity', $demand->quantity) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    @error('quantity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="target_price" class="block text-sm font-medium text-gray-700">Target Price (₱)</label>
                                    <input type="number" name="target_price" id="target_price" step="0.01"
                                           value="{{ old('target_price', $demand->target_price) }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    @error('target_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" name="location" id="location" 
                                       value="{{ old('location', $demand->location) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                                <input type="date" name="delivery_date" id="delivery_date" 
                                       value="{{ old('delivery_date', $demand->delivery_date ? $demand->delivery_date->format('Y-m-d') : '') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                @error('delivery_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    <option value="unmatched" {{ old('status', $demand->status) == 'unmatched' ? 'selected' : '' }}>Unmatched</option>
                                    <option value="matched" {{ old('status', $demand->status) == 'matched' ? 'selected' : '' }}>Matched</option>
                                    <option value="in negotiation" {{ old('status', $demand->status) == 'in negotiation' ? 'selected' : '' }}>In Negotiation</option>
                                    <option value="completed" {{ old('status', $demand->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="buyer_id" class="block text-sm font-medium text-gray-700">Buyer</label>
                                <select name="buyer_id" id="buyer_id"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}" {{ old('buyer_id', $demand->buyer_id) == $buyer->id ? 'selected' : '' }}>
                                            {{ $buyer->first_name }} {{ $buyer->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('buyer_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Update Demand
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection