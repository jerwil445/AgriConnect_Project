@extends('layouts.farmers_page')

@section('content')
<div class="ml-64">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf
        
        <!-- Hidden fields for auto-calculated values -->
        <input type="hidden" name="quantity" id="quantity" value="{{ old('quantity', 0) }}">
        <input type="hidden" name="unit" value="trays">
        <input type="hidden" name="price" id="price" value="{{ old('price', 0) }}">
        <input type="hidden" name="egg_size" id="egg_size_hidden">
        <input type="hidden" name="total_price" id="total_price_hidden">
        <input type="hidden" name="egg_type" id="egg_type_hidden" value="{{ old('egg_type') }}">
        
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Add New Product</h2>
                        <p class="text-sm text-gray-500">Fill in the details below to list your eggs</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('products.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Product
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content - Left 2 columns -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Step 1: Basic Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Basic Information</h3>
                                <p class="text-xs text-gray-500">Select your egg type and harvest details</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Egg Type -->
                            <div>
                                <label for="egg_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        Egg Type <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                @php
                                    $oldEggType = old('egg_type');
                                    $isChickenColor = in_array($oldEggType, ['brown', 'white']);
                                @endphp
                                <select id="egg_type_select" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900">
                                    <option value="">Choose egg type...</option>
                                    <option value="chicken" {{ $oldEggType == 'chicken' || $isChickenColor ? 'selected' : '' }}>🐔 Chicken Eggs</option>
                                    <option value="duck" {{ $oldEggType == 'duck' ? 'selected' : '' }}>🦆 Duck Eggs</option>
                                    <option value="quail" {{ $oldEggType == 'quail' ? 'selected' : '' }}>🐦 Quail Eggs</option>
                                    <option value="native_chicken" {{ $oldEggType == 'native_chicken' ? 'selected' : '' }}>🐓 Native Chicken Eggs</option>
                                </select>
                                <div id="chicken-color-wrapper" class="mt-3 {{ $isChickenColor ? '' : 'hidden' }}">
                                    <label for="chicken_color" class="block text-xs font-medium text-gray-600 mb-1">Chicken Egg Color</label>
                                    <select id="chicken_color" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900">
                                        <option value="">Select color...</option>
                                        <option value="brown" {{ $oldEggType == 'brown' ? 'selected' : '' }}>Brown Eggs</option>
                                        <option value="white" {{ $oldEggType == 'white' ? 'selected' : '' }}>White Eggs</option>
                                    </select>
                                </div>
                                @error('egg_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Harvest Date -->
                            <div>
                                <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Harvest Date <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                <input type="date" name="harvest_date" id="harvest_date" required
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                       value="{{ old('harvest_date', date('Y-m-d')) }}">
                                @error('harvest_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pickup Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Pickup Location <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                                    </span>
                                </label>
                                <input type="text" name="address" id="address" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                       value="{{ old('address') }}" 
                                       placeholder="e.g., Barangay San Jose, Quezon City">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Sizes & Pricing -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Sizes & Pricing</h3>
                                    <p class="text-xs text-gray-500">Add the egg sizes you have available</p>
                                </div>
                            </div>
                            <button type="button" id="add-size-btn" 
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Size
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="sizes-container" class="space-y-4">
                            <!-- Size cards will be added here dynamically -->
                        </div>
                        
                        <div id="no-sizes-message" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="font-medium">No sizes added yet</p>
                            <p class="text-sm">Click "Add Size" to start adding your egg sizes and prices</p>
                        </div>
                        
                        @error('sizes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Step 3: Description & Images -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Description & Photos</h3>
                                <p class="text-xs text-gray-500">Add details and images to attract buyers</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                    </svg>
                                    Description <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                                </span>
                            </label>
                            <textarea name="description" id="description" rows="4" 
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                      placeholder="Describe your eggs - freshness, feed quality, free-range, organic, etc.">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Product Photos <span class="text-gray-400 text-xs font-normal">(Optional - up to 10 images)</span>
                                </span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-gray-50 hover:border-green-400 hover:bg-green-50/30 transition-all cursor-pointer group" 
                                 onclick="document.getElementById('images').click()" id="image-dropzone">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-200 transition">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 mb-1">Click to upload photos</p>
                                    <p class="text-xs text-gray-500">or drag and drop here</p>
                                    <p class="text-xs text-gray-400 mt-2">PNG, JPG, GIF up to 10MB each</p>
                                </div>
                                <input type="file" name="images[]" id="images" multiple 
                                       class="sr-only" accept="image/*" onchange="previewImages(this)">
                            </div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview -->
                            <div id="image-preview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-3">Selected Photos:</p>
                                <div id="preview-container" class="flex flex-wrap gap-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Right column -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    <!-- Order Summary Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                            <h3 class="font-semibold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Product Summary
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Total Trays</span>
                                    <span id="summary-trays" class="text-lg font-bold text-gray-900">0</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Sizes Added</span>
                                    <span id="summary-sizes" class="text-lg font-bold text-gray-900">0</span>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-sm font-medium text-gray-700">Total Value</span>
                                    <span id="summary-price" class="text-2xl font-bold text-green-600">₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-amber-50 rounded-xl border border-amber-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-amber-800 mb-1">Quick Tips</h4>
                                <ul class="text-xs text-amber-700 space-y-1">
                                    <li>• Add multiple sizes to reach more buyers</li>
                                    <li>• Clear photos increase buyer trust</li>
                                    <li>• Accurate harvest dates matter</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        const sizesContainer = document.getElementById('sizes-container');
        const noSizesMessage = document.getElementById('no-sizes-message');
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const addSizeBtn = document.getElementById('add-size-btn');
        const eggTypeSelect = document.getElementById('egg_type_select');
        const eggTypeHidden = document.getElementById('egg_type_hidden');
        const chickenColorWrapper = document.getElementById('chicken-color-wrapper');
        const chickenColorSelect = document.getElementById('chicken_color');
        const form = document.getElementById('product-form');
        
        let sizeCounter = 0;
        
        function syncEggType() {
            if (!eggTypeSelect || !eggTypeHidden) {
                return;
            }
            
            const baseType = eggTypeSelect.value;
            
            if (baseType === 'chicken') {
                if (chickenColorWrapper) {
                    chickenColorWrapper.classList.remove('hidden');
                }
                if (chickenColorSelect && (chickenColorSelect.value === 'brown' || chickenColorSelect.value === 'white')) {
                    eggTypeHidden.value = chickenColorSelect.value;
                } else {
                    eggTypeHidden.value = 'chicken';
                }
            } else {
                if (chickenColorWrapper) {
                    chickenColorWrapper.classList.add('hidden');
                }
                if (chickenColorSelect) {
                    chickenColorSelect.value = '';
                }
                eggTypeHidden.value = baseType || '';
            }
        }
        
        if (eggTypeSelect) {
            eggTypeSelect.addEventListener('change', syncEggType);
        }
        
        if (chickenColorSelect) {
            chickenColorSelect.addEventListener('change', syncEggType);
        }
        
        if (form && eggTypeSelect && chickenColorSelect) {
            form.addEventListener('submit', function(e) {
                if (eggTypeSelect.value === 'chicken' && (!chickenColorSelect.value || chickenColorSelect.value === '')) {
                    e.preventDefault();
                    alert('Please select whether the chicken eggs are White or Brown.');
                    chickenColorSelect.focus();
                }
            });
        }
        
        syncEggType();
        
        const sizeOptions = [
            { value: 'small', label: 'Small', icon: 'S' },
            { value: 'medium', label: 'Medium', icon: 'M' },
            { value: 'large', label: 'Large', icon: 'L' },
            { value: 'extra_large', label: 'Extra Large', icon: 'XL' }
        ];
        
        // Add size button click handler
        addSizeBtn.addEventListener('click', function() {
            addSizeCard();
        });
        
        // Function to add a new size card
        function addSizeCard() {
            const cardId = 'size-card-' + sizeCounter;
            const currentIndex = sizeCounter;
            
            // Hide "no sizes" message
            noSizesMessage.classList.add('hidden');
            
            // Get existing selected sizes
            const existingSizes = Array.from(document.querySelectorAll('.size-card select[name$="[name]"]'))
                .map(select => select.value);
            
            // Build size options HTML
            let sizeSelectOptions = '<option value="">Select size...</option>';
            sizeOptions.forEach(option => {
                if (!existingSizes.includes(option.value)) {
                    sizeSelectOptions += `<option value="${option.value}">${option.label}</option>`;
                }
            });
            
            const card = document.createElement('div');
            card.id = cardId;
            card.className = 'size-card bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-green-300 transition-all';
            card.innerHTML = `
                <div class="flex items-start gap-4">
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Size</label>
                            <select name="sizes[${currentIndex}][name]" required
                                    class="size-select w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                ${sizeSelectOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Number of Trays</label>
                            <input type="number" name="sizes[${currentIndex}][tray_count]" min="1" placeholder="0" required
                                   class="tray-input w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Price per Tray</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₱</span>
                                <input type="number" name="sizes[${currentIndex}][price_per_tray]" step="0.01" min="0" placeholder="0.00" required
                                       class="price-input w-full pl-7 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 min-w-[100px]">
                        <button type="button" class="remove-size-btn text-gray-400 hover:text-red-500 transition p-1" title="Remove">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Subtotal</span>
                            <p class="subtotal text-lg font-bold text-green-600">₱0.00</p>
                        </div>
                    </div>
                </div>
            `;
            
            sizesContainer.appendChild(card);
            
            // Add event listeners
            const trayInput = card.querySelector('.tray-input');
            const priceInputEl = card.querySelector('.price-input');
            const sizeSelect = card.querySelector('.size-select');
            const removeBtn = card.querySelector('.remove-size-btn');
            const subtotal = card.querySelector('.subtotal');
            
            // Update calculations on input change
            const updateCardSubtotal = () => {
                const trays = parseInt(trayInput.value) || 0;
                const price = parseFloat(priceInputEl.value) || 0;
                subtotal.textContent = '₱' + (trays * price).toFixed(2);
                updateTotals();
            };
            
            trayInput.addEventListener('input', updateCardSubtotal);
            priceInputEl.addEventListener('input', updateCardSubtotal);
            sizeSelect.addEventListener('change', () => {
                updateSizeSelectOptions();
                updateTotals();
            });
            
            // Remove button handler
            removeBtn.addEventListener('click', function() {
                card.remove();
                updateSizeSelectOptions();
                updateTotals();
                
                // Show "no sizes" message if no cards left
                if (sizesContainer.children.length === 0) {
                    noSizesMessage.classList.remove('hidden');
                }
            });
            
            sizeCounter++;
            updateTotals();
        }
        
        // Update size select options to prevent duplicates
        function updateSizeSelectOptions() {
            const existingSizes = Array.from(document.querySelectorAll('.size-card select[name$="[name]"]'))
                .map(select => select.value)
                .filter(val => val !== '');
            
            document.querySelectorAll('.size-card select[name$="[name]"]').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Select size...</option>';
                
                sizeOptions.forEach(option => {
                    if (!existingSizes.includes(option.value) || option.value === currentValue) {
                        const optionEl = document.createElement('option');
                        optionEl.value = option.value;
                        optionEl.textContent = option.label;
                        if (option.value === currentValue) optionEl.selected = true;
                        select.appendChild(optionEl);
                    }
                });
            });
        }
        
        // Update all totals
        function updateTotals() {
            let totalTrays = 0;
            let totalPrice = 0;
            let sizeCount = 0;
            
            document.querySelectorAll('.size-card').forEach(card => {
                const trays = parseInt(card.querySelector('.tray-input').value) || 0;
                const price = parseFloat(card.querySelector('.price-input').value) || 0;
                totalTrays += trays;
                totalPrice += trays * price;
                sizeCount++;
            });
            
            // Update hidden form inputs
            quantityInput.value = totalTrays;
            priceInput.value = totalPrice.toFixed(2);
            document.getElementById('total_price_hidden').value = totalPrice.toFixed(2);
            
            // Update summary sidebar
            document.getElementById('summary-trays').textContent = totalTrays;
            document.getElementById('summary-sizes').textContent = sizeCount;
            document.getElementById('summary-price').textContent = '₱' + totalPrice.toFixed(2);
            
            // Update egg size hidden input
            updateEggSizeHidden();
        }
        
        // Update the hidden input with egg sizes summary
        function updateEggSizeHidden() {
            const selectedValues = [];
            document.querySelectorAll('.size-card').forEach(card => {
                const sizeSelect = card.querySelector('select[name$="[name]"]');
                const trayInput = card.querySelector('.tray-input');
                const priceInputEl = card.querySelector('.price-input');
                
                const sizeValue = sizeSelect.value;
                const trayCount = trayInput.value;
                const priceValue = priceInputEl.value;
                
                const sizeLabel = sizeOptions.find(opt => opt.value === sizeValue)?.label || sizeValue;
                
                if (sizeValue && trayCount && priceValue) {
                    selectedValues.push(`${sizeLabel} (${trayCount} tray${trayCount > 1 ? 's' : ''}) @ ₱${parseFloat(priceValue).toFixed(2)}/tray`);
                }
            });
            
            document.getElementById('egg_size_hidden').value = selectedValues.join(', ');
        }
        
        // Initialize with one empty card
        addSizeCard();
    });
    
    // Preview newly uploaded images
    function previewImages(input) {
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');
        
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            imagePreview.classList.remove('hidden');
            
            for (let i = 0; i < Math.min(input.files.length, 10); i++) {
                const file = input.files[i];
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative group';
                        wrapper.innerHTML = `
                            <img src="${e.target.result}" class="h-20 w-20 object-cover rounded-lg border-2 border-gray-200 group-hover:border-green-400 transition" alt="Preview">
                            ${i === 0 ? '<span class="absolute -top-1 -left-1 bg-green-500 text-white text-xs px-1.5 py-0.5 rounded-full text-[10px] font-medium">Primary</span>' : ''}
                        `;
                        previewContainer.appendChild(wrapper);
                    };
                    
                    reader.readAsDataURL(file);
                }
            }
        } else {
            imagePreview.classList.add('hidden');
        }
    }
</script>
@endsection