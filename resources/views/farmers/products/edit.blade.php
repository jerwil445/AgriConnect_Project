@extends('layouts.farmers_page')

@section('content')
<div class="">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Edit Product</h2>
        </div>
        
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Basic Information -->
                <div>
                    <div class="space-y-6">
                        <div>
                            <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('product_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="product_category" class="block text-sm font-medium text-gray-700 mb-1">Product Category</label>
                            <select name="product_category" id="product_category" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="">Select Category</option>
                                <option value="Crop" {{ old('product_category', $product->product_category) == 'Crop' ? 'selected' : '' }}>Crop</option>
                                <option value="Livestock" {{ old('product_category', $product->product_category) == 'Livestock' ? 'selected' : '' }}>Livestock</option>
                                <option value="Processed" {{ old('product_category', $product->product_category) == 'Processed' ? 'selected' : '' }}>Processed</option>
                                <option value="Other" {{ old('product_category', $product->product_category) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('product_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="product_type" class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                            <input type="text" name="product_type" id="product_type" value="{{ old('product_type', $product->product_type) }}"
                                   placeholder="e.g., Rice, Corn, Milk, etc."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('product_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                      placeholder="Describe your product...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Quantity, Pricing & Dates -->
                <div>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" min="1"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                <select name="unit" id="unit" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                    <option value="">Select Unit</option>
                                    <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="tons" {{ old('unit', $product->unit) == 'tons' ? 'selected' : '' }}>tons</option>
                                    <option value="pieces" {{ old('unit', $product->unit) == 'pieces' ? 'selected' : '' }}>pieces</option>
                                    <option value="liters" {{ old('unit', $product->unit) == 'liters' ? 'selected' : '' }}>liters</option>
                                </select>
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price per Unit (₱)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest Date</label>
                                <input type="date" name="harvest_date" id="harvest_date" value="{{ old('harvest_date', $product->harvest_date ? $product->harvest_date->format('Y-m-d') : '') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                @error('harvest_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date (Optional)</label>
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('expiry_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="product_condition" class="block text-sm font-medium text-gray-700 mb-1">Product Condition</label>
                            <select name="product_condition" id="product_condition" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="">Select Condition</option>
                                <option value="Fresh" {{ old('product_condition', $product->product_condition) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                <option value="Dried" {{ old('product_condition', $product->product_condition) == 'Dried' ? 'selected' : '' }}>Dried</option>
                                <option value="Processed" {{ old('product_condition', $product->product_condition) == 'Processed' ? 'selected' : '' }}>Processed</option>
                                <option value="Frozen" {{ old('product_condition', $product->product_condition) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                            </select>
                            @error('product_condition')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Location Information -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Location Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="purok_street" class="block text-sm font-medium text-gray-700 mb-1">Purok/Street</label>
                        <input type="text" name="purok_street" id="purok_street" value="{{ old('purok_street', $product->purok_street) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('purok_street')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                        <input type="text" name="barangay" id="barangay" value="{{ old('barangay', $product->barangay) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('barangay')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="municipality_city" class="block text-sm font-medium text-gray-700 mb-1">Municipality/City</label>
                        <input type="text" name="municipality_city" id="municipality_city" value="{{ old('municipality_city', $product->municipality_city) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('municipality_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                        <input type="text" name="province" id="province" value="{{ old('province', $product->province) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('province')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Product Image -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Image</h3>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload New Product Image (Optional)</label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    @if($product->image)
                        <p class="mt-2 text-sm text-gray-600">Current image will be replaced if you upload a new one.</p>
                    @endif
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
                            <input type="number" name="quantity" id="quantity" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                   value="{{ old('quantity', $product->quantity) }}" min="1" required>
                            <p class="mt-1 text-sm text-gray-500">For quail eggs: Enter number of dozens/packs. For other eggs: Automatically calculated based on tray counts</p>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure</label>
                            <select name="unit" id="unit" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                <option value="trays" {{ (old('unit', $product->unit) == 'trays') ? 'selected' : '' }}>Trays</option>
                                <!-- <option value="pieces" {{ (old('unit', $product->unit) == 'pieces') ? 'selected' : '' }}>Pieces (pcs)</option> -->
                                <option value="dozen" {{ (old('unit', $product->unit) == 'dozen') ? 'selected' : '' }}>Dozen</option>
                                <!-- <option value="kilos" {{ (old('unit', $product->unit) == 'kilos') ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="bunches" {{ (old('unit', $product->unit) == 'bunches') ? 'selected' : '' }}>Bunches</option>
                                <option value="boxes" {{ (old('unit', $product->unit) == 'boxes') ? 'selected' : '' }}>Boxes</option>
                                <option value="sacks" {{ (old('unit', $product->unit) == 'sacks') ? 'selected' : '' }}>Sacks</option> -->
                            </select>
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Total Price (₱)</label>
                            <input type="number" name="price" id="price" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                   value="{{ old('price', $product->price) }}" required>
                            <p class="mt-1 text-sm text-gray-500">For quail eggs: Enter total price. For other eggs: Automatically calculated based on size prices</p>
                            <div id="quail-total-price-display" class="mt-2 text-sm text-gray-700 hidden">
                                <strong>Total Price: <span id="calculated-total-price">₱0.00</span></strong>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest Date</label>
                            <input type="date" name="harvest_date" id="harvest_date" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                   value="{{ old('harvest_date', $product->harvest_date ? $product->harvest_date->format('Y-m-d') : '') }}" required>
                            @error('harvest_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="purok_street" class="block text-xs text-gray-500 mb-1">Purok/Street</label>
                                    <input type="text" name="purok_street" id="purok_street" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                           value="{{ old('purok_street', $product->purok_street) }}" placeholder="Enter purok or street">
                                    @error('purok_street')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="barangay" class="block text-xs text-gray-500 mb-1">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                           value="{{ old('barangay', $product->barangay) }}" placeholder="Enter barangay">
                                    @error('barangay')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="municipality_city" class="block text-xs text-gray-500 mb-1">Municipality/City</label>
                                    <input type="text" name="municipality_city" id="municipality_city" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                           value="{{ old('municipality_city', $product->municipality_city) }}" placeholder="Enter municipality or city">
                                    @error('municipality_city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="province" class="block text-xs text-gray-500 mb-1">Province</label>
                                    <input type="text" name="province" id="province" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                           value="{{ old('province', $product->province) }}" placeholder="Enter province">
                                    @error('province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Egg-specific fields -->
                        <div id="egg-fields">
                            <div class="border-t border-gray-200 pt-6 mt-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Egg-Specific Details</h3>
                                
                                <div class="space-y-6">
                                    <!-- Unit Selection for Quail Eggs -->
                                    <div id="quail-unit-options" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Unit / Selling Option</label>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="border border-green-500 rounded-lg p-4 bg-green-50 cursor-pointer quail-unit-option"">
                                                <div class="font-medium">Per Dozen (12 pcs)</div>
                                                <div class="text-sm text-gray-500">Default selection</div>
                                                <div class="mt-2">
                                                    <label class="block text-xs text-gray-600">Quantity</label>
                                                    <input type="number" name="quail_quantity_dozen" id="quail_quantity_dozen" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Enter quantity">
                                                </div>
                                                <div class="mt-2">
                                                    <label class="block text-xs text-gray-600">Price per dozen (₱)</label>
                                                    <input type="number" name="quail_price_dozen" id="quail_price_dozen" step="0.01" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Enter price">
                                                </div>
                                            </div>
                                            <div class="border border-gray-300 rounded-lg p-4 hover:border-green-300 cursor-pointer quail-unit-option" data-value="pack_24">
                                                <div class="font-medium">Per 24 pcs pack</div>
                                                <div class="mt-2">
                                                    <label class="block text-xs text-gray-600">Quantity</label>
                                                    <input type="number" name="quail_quantity_pack" id="quail_quantity_pack" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Enter quantity">
                                                </div>
                                                <div class="mt-2">
                                                    <label class="block text-xs text-gray-600">Price per pack (₱)</label>
                                                    <input type="number" name="quail_price_pack" id="quail_price_pack" step="0.01" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Enter price">
                                                </div>
                                            </div>
                                            <div class="border border-gray-300 rounded-lg p-4 hover:border-green-300 cursor-pointer quail-unit-option" data-value="tray_36">
                                                <div class="font-medium">Per Tray (36 pcs)</div>
                                                <div class="text-sm text-gray-500">Optional</div>
                                                <div class="mt-2">
                                                    <label class="block text-xs text-gray-600">Quantity</label>
                                                    <input type="number" name="quail_quantity_tray" id="quail_quantity_tray" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Enter quantity">
                                                </div>
                                                <div class="mt-2">
                                                    <label class="block text-xs text-gray-600">Price per tray (₱)</label>
                                                    <input type="number" name="quail_price_tray" id="quail_price_tray" step="0.01" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Enter price">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <input type="hidden" name="unit" id="quail-unit-hidden" value="{{ old('unit', $product->unit) }}"> -->
                                        <div id="quail-total-price-display" class="mt-2 text-sm text-gray-700 hidden">
                                            <strong>Total Price: <span id="calculated-total-price">₱0.00</span></strong>
                                        </div>
                                    </div>
                                    
                                    <!-- Egg Sizes Table -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Sizes</label>
                                        
                                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Size</th>
                                                        <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Trays</th>
                                                        <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Price per Tray</th>
                                                        <th scope="col" class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Total</th>
                                                        <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-6">
                                                            <span class="sr-only">Actions</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white" id="sizes-table-body">
                                                    <!-- Size rows will be added here dynamically -->
                                                </tbody>
                                                <tfoot class="bg-gray-50">
                                                    <tr>
                                                        <td colspan="3" class="py-3 pl-4 pr-3 text-right text-sm font-medium text-gray-900 sm:pl-6">Totals:</td>
                                                        <td class="px-3 py-3 text-left text-sm font-semibold text-gray-900">
                                                            <span id="total-price-display">₱0.00</span>
                                                        </td>
                                                        <td class="relative py-3 pl-3 pr-4 sm:pr-6"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        
                                        <!-- Add Size Button -->
                                        <div class="mt-4">
                                            <button type="button" id="add-size-btn" 
                                                    class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                Add Size
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="egg_size" id="egg_size_hidden">
                        <input type="hidden" name="total_price" id="total_price_hidden">
                        @error('egg_size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Right Column - Description and Images -->
                <div>
                    <div class="space-y-6">
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="6" 
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                      placeholder="Describe the quality, farming methods, etc.">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Images</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:border-green-400 transition-colors cursor-pointer" 
                                 onclick="document.getElementById('images').click()">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                            <span>Upload files</span>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    <p class="text-xs text-gray-500 mt-1">You can select up to 10 images. The first image will be used as the primary image.</p>
                                </div>
                                <input type="file" name="images[]" id="images" multiple 
                                       class="sr-only" accept="image/*" onchange="previewImages(this)">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Click or drag images to this area to upload</p>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview of newly uploaded images -->
                            <div id="image-preview" class="mt-4 flex flex-wrap gap-3 hidden">
                                <p class="text-sm text-gray-700 font-medium mb-2 w-full">Newly Uploaded Images:</p>
                                <div id="preview-container" class="flex flex-wrap gap-3"></div>
                            </div>
                            
                            <!-- Current images -->
                            @if($product->images->count() > 0)
                            <div class="mt-4">
                                <p class="text-sm text-gray-700 font-medium mb-2">Current Images:</p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($product->images as $image)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image" 
                                                 class="h-24 w-24 object-cover rounded-md border border-gray-200">
                                            <button type="button" 
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity remove-image-btn"
                                                    data-image-id="{{ $image->id }}">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end gap-3">
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
</div>
@endsection
