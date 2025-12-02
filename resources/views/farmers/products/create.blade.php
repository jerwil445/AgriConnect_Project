@extends('layouts.farmers_page')

@section('content')
<div class="">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Add New Product</h2>
        </div>
        
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Main Product Information -->
                <div>
                    <div class="space-y-6">
                        <div>
                            <label for="egg_type" class="block text-sm font-medium text-gray-700 mb-1">Egg Type / Category</label>
                            <select name="egg_type" id="egg_type" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="">Select Egg Type</option>
                                <option value="chicken" {{ old('egg_type') == 'chicken' ? 'selected' : '' }}>Chicken</option>
                                <option value="duck" {{ old('egg_type') == 'duck' ? 'selected' : '' }}>Duck</option>
                                <option value="quail" {{ old('egg_type') == 'quail' ? 'selected' : '' }}>Quail</option>
                                <option value="native_chicken" {{ old('egg_type') == 'native_chicken' ? 'selected' : '' }}>Native Chicken</option>
                                <option value="brown" {{ old('egg_type') == 'brown' ? 'selected' : '' }}>Brown Egg</option>
                                <option value="white" {{ old('egg_type') == 'white' ? 'selected' : '' }}>White Egg</option>
                            </select>
                            @error('egg_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity Available</label>
                            <input type="number" name="quantity" id="quantity" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                   value="{{ old('quantity') }}" min="1" required readonly>
                            <p class="mt-1 text-sm text-gray-500">Automatically calculated based on tray counts</p>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure</label>
                            <select name="unit" id="unit" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                <option value="trays" selected>Trays</option>
                                <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces (pcs)</option>
                                <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                <option value="kilos" {{ old('unit') == 'kilos' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="bunches" {{ old('unit') == 'bunches' ? 'selected' : '' }}>Bunches</option>
                                <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>Boxes</option>
                                <option value="sacks" {{ old('unit') == 'sacks' ? 'selected' : '' }}>Sacks</option>
                            </select>
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Total Price (₱)</label>
                            <input type="number" name="price" id="price" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                   value="{{ old('price') }}" required readonly>
                            <p class="mt-1 text-sm text-gray-500">Automatically calculated based on size prices</p>
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
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="address" id="address" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                   value="{{ old('address') }}" placeholder="Enter product location/address">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Egg-specific fields -->
                        <div id="egg-fields">
                            <div class="border-t border-gray-200 pt-6 mt-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Egg-Specific Details</h3>
                                
                                <div class="space-y-6">
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
                                      placeholder="Describe the quality, farming methods, etc.">{{ old('description') }}</textarea>
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
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Check on page load
    window.addEventListener('DOMContentLoaded', function() {
        const sizesTableBody = document.getElementById('sizes-table-body');
        const totalPriceDisplay = document.getElementById('total-price-display');
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const addSizeBtn = document.getElementById('add-size-btn');
        
        let sizeCounter = 0;
        let totalTrays = 0;
        let totalPrice = 0;
        
        // Size options
        const sizeOptions = [
            { value: 'small', label: 'Small' },
            { value: 'medium', label: 'Medium' },
            { value: 'large', label: 'Large' },
            { value: 'extra_large', label: 'Extra Large' },
            { value: 'jumbo', label: 'Jumbo' }
        ];
        
        // Add size button click handler
        addSizeBtn.addEventListener('click', function() {
            addSizeRow();
        });
        
        // Function to add a new size row
        function addSizeRow(sizeData = null) {
            const rowId = 'size-row-' + sizeCounter;
            const sizeSelectId = 'size-name-' + sizeCounter;
            const trayInputId = 'tray-count-' + sizeCounter;
            const priceInputId = 'price-per-tray-' + sizeCounter;
            const totalSpanId = 'total-' + sizeCounter;
            const removeBtnId = 'remove-' + sizeCounter;
            
            const row = document.createElement('tr');
            row.id = rowId;
            row.className = 'size-row';
            
            // Size select - filter out already selected sizes
            const existingSizes = Array.from(document.querySelectorAll('select[name$="[name]"]')).map(select => select.value);
            let sizeSelectOptions = '';
            sizeOptions.forEach(option => {
                // Skip if this size is already selected (unless it's the one we're editing)
                if (existingSizes.includes(option.value) && !(sizeData && sizeData.name === option.value)) {
                    return;
                }
                
                const selected = sizeData && sizeData.name === option.value ? 'selected' : '';
                sizeSelectOptions += `<option value="${option.value}" ${selected}>${option.label}</option>`;
            });
            
            row.innerHTML = `
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    <select name="sizes[${sizeCounter}][name]" id="${sizeSelectId}" class="rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        ${sizeSelectOptions}
                    </select>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <input type="number" name="sizes[${sizeCounter}][tray_count]" id="${trayInputId}" min="1" value="${sizeData ? sizeData.tray_count : ''}"
                           class="w-24 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <div class="flex items-center">
                        <span class="mr-1">₱</span>
                        <input type="number" name="sizes[${sizeCounter}][price_per_tray]" id="${priceInputId}" step="0.01" min="0" value="${sizeData ? sizeData.price_per_tray : ''}"
                               class="w-24 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <span id="${totalSpanId}">₱0.00</span>
                </td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <button type="button" id="${removeBtnId}" class="text-red-600 hover:text-red-900 remove-size-btn">Remove</button>
                </td>
            `;
            
            sizesTableBody.appendChild(row);
            
            // Add event listeners for the new inputs
            const trayInput = document.getElementById(trayInputId);
            const priceInput = document.getElementById(priceInputId);
            const sizeSelect = document.getElementById(sizeSelectId);
            const removeBtn = document.getElementById(removeBtnId);
            
            // Update calculations when inputs change
            trayInput.addEventListener('input', updateCalculations);
            priceInput.addEventListener('input', updateCalculations);
            sizeSelect.addEventListener('change', function() {
                updateCalculations();
                
                // Update options in other selects to prevent duplicates
                updateSizeSelectOptions();
            });
            
            // Remove button handler
            removeBtn.addEventListener('click', function() {
                row.remove();
                updateCalculations();
                
                // Update options in other selects
                updateSizeSelectOptions();
            });
            
            sizeCounter++;
            updateCalculations();
        }
        
        // Function to update size select options to prevent duplicates
        function updateSizeSelectOptions() {
            const existingSizes = Array.from(document.querySelectorAll('select[name$="[name]"]')).map(select => select.value);
            
            document.querySelectorAll('select[name$="[name]"]').forEach(select => {
                const currentValue = select.value;
                
                // Clear existing options
                const selectedOption = select.options[select.selectedIndex];
                const selectedText = selectedOption ? selectedOption.text : '';
                
                select.innerHTML = '';
                
                // Add options that are not already selected (or is the current selection)
                sizeOptions.forEach(option => {
                    if (!existingSizes.includes(option.value) || option.value === currentValue) {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.value;
                        optionElement.textContent = option.label;
                        if (option.value === currentValue) {
                            optionElement.selected = true;
                        }
                        select.appendChild(optionElement);
                    }
                });
                
                // If current value is not in sizeOptions, preserve it
                if (currentValue && !sizeOptions.some(opt => opt.value === currentValue)) {
                    const optionElement = document.createElement('option');
                    optionElement.value = currentValue;
                    optionElement.textContent = selectedText || currentValue;
                    optionElement.selected = true;
                    select.appendChild(optionElement);
                }
            });
        }
        
        // Function to update calculations
        function updateCalculations() {
            totalTrays = 0;
            totalPrice = 0;
            
            // Get all size rows
            const rows = document.querySelectorAll('.size-row');
            
            rows.forEach((row, index) => {
                const trayInput = row.querySelector(`input[name="sizes[${index}][tray_count]"]`);
                const priceInput = row.querySelector(`input[name="sizes[${index}][price_per_tray]"]`);
                const totalSpan = row.querySelector(`td:nth-child(4) span`);
                
                const trayCount = parseInt(trayInput.value) || 0;
                const pricePerTray = parseFloat(priceInput.value) || 0;
                const total = trayCount * pricePerTray;
                
                totalSpan.textContent = '₱' + total.toFixed(2);
                
                totalTrays += trayCount;
                totalPrice += total;
            });
            
            // Update summary display
            totalPriceDisplay.textContent = '₱' + totalPrice.toFixed(2);
            
            // Update form inputs
            quantityInput.value = totalTrays;
            priceInput.value = totalPrice.toFixed(2);
            
            // Update hidden inputs
            document.getElementById('total_price_hidden').value = totalPrice.toFixed(2);
            
            // Update egg size hidden input
            updateEggSizeHidden();
        }
        
        // Function to update the hidden input with egg sizes and tray counts
        function updateEggSizeHidden() {
            const selectedValues = [];
            const rows = document.querySelectorAll('.size-row');
            
            rows.forEach((row, index) => {
                const sizeSelect = row.querySelector(`select[name="sizes[${index}][name]"]`);
                const trayInput = row.querySelector(`input[name="sizes[${index}][tray_count]"]`);
                const priceInput = row.querySelector(`input[name="sizes[${index}][price_per_tray]"]`);
                
                const sizeValue = sizeSelect.value;
                const trayCount = trayInput.value;
                const priceValue = priceInput.value;
                
                // Find the label for the size
                let sizeLabel = '';
                sizeOptions.forEach(option => {
                    if (option.value === sizeValue) {
                        sizeLabel = option.label;
                    }
                });
                
                if (sizeValue && trayCount && priceValue) {
                    selectedValues.push(`${sizeLabel} (${trayCount} tray${trayCount > 1 ? 's' : ''}) @ ₱${parseFloat(priceValue).toFixed(2)}/tray`);
                } else if (sizeValue && trayCount) {
                    selectedValues.push(`${sizeLabel} (${trayCount} tray${trayCount > 1 ? 's' : ''})`);
                } else if (sizeValue) {
                    selectedValues.push(sizeLabel);
                }
            });
            
            document.getElementById('egg_size_hidden').value = selectedValues.join(', ');
        }
        
        // Initialize with one empty row
        addSizeRow();
    });
    
    // Preview newly uploaded images
    function previewImages(input) {
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');
        
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            let hasImages = false;
            
            for (let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                if (file.type.match('image.*')) {
                    hasImages = true;
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-24 w-24 object-cover rounded-md border border-gray-200';
                        img.alt = 'Preview';
                        
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative group';
                        wrapper.appendChild(img);
                        
                        // Add remove button
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity';
                        removeBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        removeBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            wrapper.remove();
                            
                            // Hide preview container if no images left
                            if (previewContainer.children.length === 0) {
                                imagePreview.classList.add('hidden');
                            }
                        });
                        wrapper.appendChild(removeBtn);
                        
                        const badge = document.createElement('span');
                        badge.className = 'absolute top-0 left-0 bg-blue-500 text-white text-xs px-1 rounded-br rounded-tl';
                        badge.textContent = 'New';
                        wrapper.appendChild(badge);
                        
                        previewContainer.appendChild(wrapper);
                    };
                    
                    reader.readAsDataURL(file);
                }
            }
            
            if (hasImages) {
                imagePreview.classList.remove('hidden');
            } else {
                imagePreview.classList.add('hidden');
            }
        } else {
            imagePreview.classList.add('hidden');
        }
    }
</script>
@endsection