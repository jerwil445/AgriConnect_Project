@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-4xl mx-auto">
            
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-green-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600 shadow-inner">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Demand</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Update market demand requirements</p>
                    </div>
                </div>
                
                <a href="{{ route('admin.demands.index') }}" 
                   class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400 group-hover:text-gray-600 transition-colors" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Demands
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50/80 backdrop-blur-sm border-l-4 border-red-500 text-red-800 px-5 py-4 rounded-r-xl shadow-sm mb-8">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <strong class="font-bold text-red-700">Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600/90 ml-6 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.demands.update', $demand) }}" method="POST" class="bg-white/60 backdrop-blur-xl rounded-2xl border border-white shadow-sm p-6 lg:p-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Basic Info -->
                    <div class="space-y-6">
                        <div class="mb-2 pb-2 border-b border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-green-700 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Basic Specification
                            </h3>
                        </div>

                        <div class="relative w-full group">
                            <select name="egg_type" id="egg_type" required
                                    class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all shadow-sm appearance-none">
                                <option value="" disabled {{ !old('egg_type', $demand->egg_type) ? 'selected' : '' }}>Select Egg Type</option>
                                <option value="chicken" {{ old('egg_type', $demand->egg_type) == 'chicken' ? 'selected' : '' }}>Chicken</option>
                                <option value="duck" {{ old('egg_type', $demand->egg_type) == 'duck' ? 'selected' : '' }}>Duck</option>
                                <option value="quail" {{ old('egg_type', $demand->egg_type) == 'quail' ? 'selected' : '' }}>Quail</option>
                                <option value="native_chicken" {{ old('egg_type', $demand->egg_type) == 'native_chicken' ? 'selected' : '' }}>Native Chicken</option>
                                <option value="brown" {{ old('egg_type', $demand->egg_type) == 'brown' ? 'selected' : '' }}>Brown Egg</option>
                                <option value="white" {{ old('egg_type', $demand->egg_type) == 'white' ? 'selected' : '' }}>White Egg</option>
                            </select>
                            <label for="egg_type" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded transition-all">
                                Egg Type / Category
                            </label>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>

                        <div class="relative w-full">
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $demand->quantity) }}" min="1" placeholder=" " required
                                   class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                            <label for="quantity" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                Total Quantity (Trays)
                            </label>
                        </div>

                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                                ₱
                            </div>
                            <input type="number" name="target_price" id="target_price" value="{{ old('target_price', $demand->target_price) }}" step="0.01" min="0" placeholder=" "
                                   class="peer w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                            <label for="target_price" class="absolute left-8 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 peer-focus:left-4 rounded cursor-text">
                                Target Price per Unit
                            </label>
                        </div>
                    </div>

                    <!-- Logistics Info -->
                    <div class="space-y-6">
                        <div class="mb-2 pb-2 border-b border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-green-700 flex items-center gap-2">
                                <i class="fas fa-truck"></i> Delivery Details
                            </h3>
                        </div>

                        <div class="relative w-full">
                            <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $demand->delivery_date ? $demand->delivery_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}" placeholder=" "
                                   class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                            <label for="delivery_date" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                Target Delivery Date
                            </label>
                        </div>

                        <div class="relative w-full">
                            <input type="text" name="location" id="location" value="{{ old('location', $demand->location) }}" placeholder=" "
                                   class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                            <label for="location" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                Delivery City/Municipality
                            </label>
                        </div>

                        <div class="relative w-full">
                            <input type="text" name="address" id="address" value="{{ old('address', $demand->address) }}" placeholder=" "
                                   class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                            <label for="address" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                Specific Delivery Address
                            </label>
                        </div>
                    </div>

                    <!-- Status & Ownership -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                        <div class="relative w-full group">
                            <select name="status" id="status"
                                    class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all shadow-sm appearance-none font-semibold text-gray-700">
                                <option value="unmatched" {{ old('status', $demand->status) == 'unmatched' ? 'selected' : '' }}>Unmatched</option>
                                <option value="matched" {{ old('status', $demand->status) == 'matched' ? 'selected' : '' }}>Matched</option>
                                <option value="in negotiation" {{ old('status', $demand->status) == 'in negotiation' ? 'selected' : '' }}>In Negotiation</option>
                                <option value="completed" {{ old('status', $demand->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <label for="status" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded transition-all">
                                Administrative Status
                            </label>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>

                        <div class="relative w-full group">
                            <select name="buyer_id" id="buyer_id" required
                                    class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all shadow-sm appearance-none">
                                @foreach($buyers as $buyer)
                                    <option value="{{ $buyer->id }}" {{ old('buyer_id', $demand->buyer_id) == $buyer->id ? 'selected' : '' }}>
                                        {{ $buyer->first_name }} {{ $buyer->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="buyer_id" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded transition-all">
                                Associated Buyer
                            </label>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Egg-Specific Fields -->
                    <div id="egg-demand-fields" class="md:col-span-2 pt-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100 p-6 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 text-green-200/40 transform rotate-12 pointer-events-none">
                                <i class="fas fa-egg text-8xl"></i>
                            </div>
                            
                            <h3 class="text-sm font-bold uppercase tracking-wider text-green-800 mb-6 pb-2 border-b border-green-200 relative z-10">
                                <i class="fas fa-list-ul mr-2"></i> Quality & Size Breakdown
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 relative z-10">
                                @php
                                    $egg_sizes = ['small', 'medium', 'large', 'extra_large', 'jumbo'];
                                    $eggSizeStr = old('egg_size', $demand->egg_size ?? '');
                                @endphp
                                
                                @foreach($egg_sizes as $size)
                                    @php
                                        $isChecked = strpos($eggSizeStr, $size) !== false;
                                        preg_match('/' . $size . ' \((\d+)/', $eggSizeStr, $matches);
                                        $trayCount = isset($matches[1]) ? $matches[1] : '';
                                    @endphp
                                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-white shadow-sm transition-all hover:shadow-md group">
                                        <div class="flex items-center justify-between mb-3">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="egg_sizes[]" value="{{ $size }}" id="{{ $size }}_checkbox" 
                                                       class="w-5 h-5 rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 focus:ring-offset-0 transition-all cursor-pointer" 
                                                       {{ $isChecked ? 'checked' : '' }}>
                                                <span class="ml-2.5 font-bold text-gray-700 capitalize">{{ str_replace('_', ' ', $size) }}</span>
                                            </label>
                                        </div>
                                        <div id="{{ $size }}_tray_container" class="{{ $isChecked ? '' : 'hidden' }} mt-2">
                                            <div class="relative w-full">
                                                <input type="number" name="{{ $size }}_trays" id="{{ $size }}_trays" min="1" value="{{ $trayCount }}" placeholder="Qty"
                                                       class="w-full pl-3 pr-10 py-2 border border-green-100 rounded-lg bg-white/80 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 transition-all text-sm font-semibold">
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-[10px] font-bold text-green-600 uppercase">Trays</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="egg_size" id="egg_size_hidden" value="{{ $eggSizeStr }}">
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 flex justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('admin.demands.index') }}" 
                       class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-6 py-3 rounded-xl border border-gray-200 shadow-sm transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-green-600 to-emerald-500 text-white font-bold px-8 py-3 rounded-xl shadow-[0_10px_20px_-10px_rgba(16,185,129,0.5)] hover:shadow-[0_15px_25px_-10px_rgba(16,185,129,0.6)] transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i> Update Demand
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Preserving exactly the same functional logic but updating element selection for the new layout -->
<script>
window.addEventListener('DOMContentLoaded', function() {
    const sizes = ['small', 'medium', 'large', 'extra_large', 'jumbo'];
    const eggSizeHidden = document.getElementById('egg_size_hidden');
    
    sizes.forEach(size => {
        const checkbox = document.getElementById(`${size}_checkbox`);
        const trayContainer = document.getElementById(`${size}_tray_container`);
        const trayInput = document.getElementById(`${size}_trays`);
        
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    trayContainer.classList.remove('hidden');
                    trayContainer.classList.add('animate-in', 'fade-in', 'slide-in-from-top-2', 'duration-200');
                } else {
                    trayContainer.classList.add('hidden');
                    if (trayInput) trayInput.value = '';
                }
                updateEggSizeHidden();
            });
        }
        
        if (trayInput) {
            trayInput.addEventListener('input', updateEggSizeHidden);
        }
    });
    
    function updateEggSizeHidden() {
        const selectedValues = [];
        sizes.forEach(size => {
            const checkbox = document.getElementById(`${size}_checkbox`);
            if (checkbox && checkbox.checked) {
                const trayInput = document.getElementById(`${size}_trays`);
                const trayCount = trayInput ? trayInput.value : '';
                
                if (trayCount) {
                    selectedValues.push(`${size} (${trayCount} tray${trayCount > 1 ? 's' : ''})`);
                } else {
                    selectedValues.push(size);
                }
            }
        });
        
        if (eggSizeHidden) {
            eggSizeHidden.value = selectedValues.join(', ');
        }
    }
});
</script>
@endsection