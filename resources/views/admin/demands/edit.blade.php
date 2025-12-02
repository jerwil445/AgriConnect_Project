@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Edit Demand</h2>
            <p class="text-sm text-gray-500 mt-1">Update demand details</p>
        </div>

        <form action="{{ route('admin.demands.update', $demand) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="egg_type" class="block text-sm font-medium text-gray-700 mb-1">Egg Type / Category</label>
                        <select name="egg_type" id="egg_type" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            <option value="">Select Egg Type</option>
                            <option value="chicken" {{ old('egg_type', $demand->egg_type) == 'chicken' ? 'selected' : '' }}>Chicken</option>
                            <option value="duck" {{ old('egg_type', $demand->egg_type) == 'duck' ? 'selected' : '' }}>Duck</option>
                            <option value="quail" {{ old('egg_type', $demand->egg_type) == 'quail' ? 'selected' : '' }}>Quail</option>
                            <option value="native_chicken" {{ old('egg_type', $demand->egg_type) == 'native_chicken' ? 'selected' : '' }}>Native Chicken</option>
                            <option value="brown" {{ old('egg_type', $demand->egg_type) == 'brown' ? 'selected' : '' }}>Brown Egg</option>
                            <option value="white" {{ old('egg_type', $demand->egg_type) == 'white' ? 'selected' : '' }}>White Egg</option>
                        </select>
                        @error('egg_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="address" id="address" 
                               value="{{ old('address', $demand->address) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                               placeholder="Enter delivery address">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Egg-specific fields -->
                    <div id="egg-demand-fields">
                        <div class="border-t border-gray-200 pt-4 mt-2">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Egg-Specific Details</h4>
                            
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Size / Grade</label>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <label class="inline-flex items-center mt-1">
                                            <input type="checkbox" name="egg_sizes[]" value="small" id="small_checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'small') !== false || strpos(($demand->egg_size ?? ''), 'small') !== false) ? 'checked' : '' }}>
                                            <span class="ml-2">Small</span>
                                        </label>
                                        <div id="small_tray_container" class="ml-4 {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'small') !== false || strpos(($demand->egg_size ?? ''), 'small') !== false) ? '' : 'hidden' }}">
                                            <label for="small_trays" class="text-sm text-gray-600">Tray(s):</label>
                                            <input type="number" name="small_trays" id="small_trays" min="1" 
                                                   class="ml-2 w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                   placeholder="Qty" 
                                                   value="{{ preg_match('/small \((\d+)/', old('egg_size', $demand->egg_size ?? ''), $matches) ? $matches[1] : '' }}">
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <label class="inline-flex items-center mt-1">
                                            <input type="checkbox" name="egg_sizes[]" value="medium" id="medium_checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'medium') !== false || strpos(($demand->egg_size ?? ''), 'medium') !== false) ? 'checked' : '' }}>
                                            <span class="ml-2">Medium</span>
                                        </label>
                                        <div id="medium_tray_container" class="ml-4 {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'medium') !== false || strpos(($demand->egg_size ?? ''), 'medium') !== false) ? '' : 'hidden' }}">
                                            <label for="medium_trays" class="text-sm text-gray-600">Tray(s):</label>
                                            <input type="number" name="medium_trays" id="medium_trays" min="1" 
                                                   class="ml-2 w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                   placeholder="Qty" 
                                                   value="{{ preg_match('/medium \((\d+)/', old('egg_size', $demand->egg_size ?? ''), $matches) ? $matches[1] : '' }}">
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <label class="inline-flex items-center mt-1">
                                            <input type="checkbox" name="egg_sizes[]" value="large" id="large_checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'large') !== false || strpos(($demand->egg_size ?? ''), 'large') !== false) ? 'checked' : '' }}>
                                            <span class="ml-2">Large</span>
                                        </label>
                                        <div id="large_tray_container" class="ml-4 {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'large') !== false || strpos(($demand->egg_size ?? ''), 'large') !== false) ? '' : 'hidden' }}">
                                            <label for="large_trays" class="text-sm text-gray-600">Tray(s):</label>
                                            <input type="number" name="large_trays" id="large_trays" min="1" 
                                                   class="ml-2 w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                   placeholder="Qty" 
                                                   value="{{ preg_match('/large \((\d+)/', old('egg_size', $demand->egg_size ?? ''), $matches) ? $matches[1] : '' }}">
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <label class="inline-flex items-center mt-1">
                                            <input type="checkbox" name="egg_sizes[]" value="extra_large" id="extra_large_checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'extra_large') !== false || strpos(($demand->egg_size ?? ''), 'extra_large') !== false) ? 'checked' : '' }}>
                                            <span class="ml-2">Extra Large</span>
                                        </label>
                                        <div id="extra_large_tray_container" class="ml-4 {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'extra_large') !== false || strpos(($demand->egg_size ?? ''), 'extra_large') !== false) ? '' : 'hidden' }}">
                                            <label for="extra_large_trays" class="text-sm text-gray-600">Tray(s):</label>
                                            <input type="number" name="extra_large_trays" id="extra_large_trays" min="1" 
                                                   class="ml-2 w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                   placeholder="Qty" 
                                                   value="{{ preg_match('/extra_large \((\d+)/', old('egg_size', $demand->egg_size ?? ''), $matches) ? $matches[1] : '' }}">
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <label class="inline-flex items-center mt-1">
                                            <input type="checkbox" name="egg_sizes[]" value="jumbo" id="jumbo_checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'jumbo') !== false || strpos(($demand->egg_size ?? ''), 'jumbo') !== false) ? 'checked' : '' }}>
                                            <span class="ml-2">Jumbo</span>
                                        </label>
                                        <div id="jumbo_tray_container" class="ml-4 {{ (strpos(old('egg_size', $demand->egg_size ?? ''), 'jumbo') !== false || strpos(($demand->egg_size ?? ''), 'jumbo') !== false) ? '' : 'hidden' }}">
                                            <label for="jumbo_trays" class="text-sm text-gray-600">Tray(s):</label>
                                            <input type="number" name="jumbo_trays" id="jumbo_trays" min="1" 
                                                   class="ml-2 w-20 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                                   placeholder="Qty" 
                                                   value="{{ preg_match('/jumbo \((\d+)/', old('egg_size', $demand->egg_size ?? ''), $matches) ? $matches[1] : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="egg_size" id="egg_size_hidden" value="{{ old('egg_size', $demand->egg_size ?? '') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" id="quantity" 
                               value="{{ old('quantity', $demand->quantity) }}" min="1"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Delivery Location</label>
                        <input type="text" name="location" id="location" 
                               value="{{ old('location', $demand->location) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                        <input type="date" name="delivery_date" id="delivery_date" 
                               value="{{ old('delivery_date', $demand->delivery_date ? $demand->delivery_date->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                               min="{{ date('Y-m-d') }}">
                        @error('delivery_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
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
                        <label for="buyer_id" class="block text-sm font-medium text-gray-700 mb-1">Buyer</label>
                        <select name="buyer_id" id="buyer_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
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
            
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.demands.index') }}" 
                   class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit" 
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Update Demand
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
window.addEventListener('DOMContentLoaded', function() {
    
    // Elements for small eggs
    const smallCheckbox = document.getElementById('small_checkbox');
    const smallTrayContainer = document.getElementById('small_tray_container');
    const smallTrays = document.getElementById('small_trays');
    
    // Elements for medium eggs
    const mediumCheckbox = document.getElementById('medium_checkbox');
    const mediumTrayContainer = document.getElementById('medium_tray_container');
    const mediumTrays = document.getElementById('medium_trays');
    
    // Elements for large eggs
    const largeCheckbox = document.getElementById('large_checkbox');
    const largeTrayContainer = document.getElementById('large_tray_container');
    const largeTrays = document.getElementById('large_trays');
    
    // Elements for extra large eggs
    const extraLargeCheckbox = document.getElementById('extra_large_checkbox');
    const extraLargeTrayContainer = document.getElementById('extra_large_tray_container');
    const extraLargeTrays = document.getElementById('extra_large_trays');
    
    // Elements for jumbo eggs
    const jumboCheckbox = document.getElementById('jumbo_checkbox');
    const jumboTrayContainer = document.getElementById('jumbo_tray_container');
    const jumboTrays = document.getElementById('jumbo_trays');
    
    // Show/hide tray input for small eggs
    if (smallCheckbox) {
        smallCheckbox.addEventListener('change', function() {
            if (this.checked) {
                smallTrayContainer.classList.remove('hidden');
            } else {
                smallTrayContainer.classList.add('hidden');
                if (smallTrays) {
                    smallTrays.value = '';
                }
            }
            updateEggSizeHidden();
        });
    }
    
    // Show/hide tray input for medium eggs
    if (mediumCheckbox) {
        mediumCheckbox.addEventListener('change', function() {
            if (this.checked) {
                mediumTrayContainer.classList.remove('hidden');
            } else {
                mediumTrayContainer.classList.add('hidden');
                if (mediumTrays) {
                    mediumTrays.value = '';
                }
            }
            updateEggSizeHidden();
        });
    }
    
    // Show/hide tray input for large eggs
    if (largeCheckbox) {
        largeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                largeTrayContainer.classList.remove('hidden');
            } else {
                largeTrayContainer.classList.add('hidden');
                if (largeTrays) {
                    largeTrays.value = '';
                }
            }
            updateEggSizeHidden();
        });
    }
    
    // Show/hide tray input for extra large eggs
    if (extraLargeCheckbox) {
        extraLargeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                extraLargeTrayContainer.classList.remove('hidden');
            } else {
                extraLargeTrayContainer.classList.add('hidden');
                if (extraLargeTrays) {
                    extraLargeTrays.value = '';
                }
            }
            updateEggSizeHidden();
        });
    }
    
    // Show/hide tray input for jumbo eggs
    if (jumboCheckbox) {
        jumboCheckbox.addEventListener('change', function() {
            if (this.checked) {
                jumboTrayContainer.classList.remove('hidden');
            } else {
                jumboTrayContainer.classList.add('hidden');
                if (jumboTrays) {
                    jumboTrays.value = '';
                }
            }
            updateEggSizeHidden();
        });
    }
    
    // Update tray count when changed for each size
    if (smallTrays) {
        smallTrays.addEventListener('input', updateEggSizeHidden);
    }
    if (mediumTrays) {
        mediumTrays.addEventListener('input', updateEggSizeHidden);
    }
    if (largeTrays) {
        largeTrays.addEventListener('input', updateEggSizeHidden);
    }
    if (extraLargeTrays) {
        extraLargeTrays.addEventListener('input', updateEggSizeHidden);
    }
    if (jumboTrays) {
        jumboTrays.addEventListener('input', updateEggSizeHidden);
    }
    
    // Function to update the hidden input with egg sizes and tray counts
    function updateEggSizeHidden() {
        const checkboxes = document.querySelectorAll('input[name="egg_sizes[]"]');
        const selectedValues = [];
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                let trayCount = '';
                let trayElement = null;
                
                switch (checkbox.value) {
                    case 'small':
                        trayElement = document.getElementById('small_trays');
                        break;
                    case 'medium':
                        trayElement = document.getElementById('medium_trays');
                        break;
                    case 'large':
                        trayElement = document.getElementById('large_trays');
                        break;
                    case 'extra_large':
                        trayElement = document.getElementById('extra_large_trays');
                        break;
                    case 'jumbo':
                        trayElement = document.getElementById('jumbo_trays');
                        break;
                }
                
                if (trayElement) {
                    trayCount = trayElement.value;
                }
                
                if (trayCount) {
                    selectedValues.push(`${checkbox.value} (${trayCount} tray${trayCount > 1 ? 's' : ''})`);
                } else {
                    selectedValues.push(checkbox.value);
                }
            }
        });
        
        if (eggSizeHidden) {
            eggSizeHidden.value = selectedValues.join(', ');
        }
    }
    
    // Add event listeners to all egg size checkboxes
    document.querySelectorAll('input[name="egg_sizes[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateEggSizeHidden);
    });
    
    // Initialize hidden input with any pre-selected values
    updateEggSizeHidden();
});
</script>
@endsection