@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-5xl mx-auto">
            
            <!-- Page Header -->
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-green-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 shadow-inner">
                        <i class="fas fa-edit text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Market Demand</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Maintain data integrity and system matching</p>
                    </div>
                </div>
                
                <a href="{{ route('admin.demands.index') }}" 
                   class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                    <i class="fas fa-arrow-left mr-2 text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    Back to Demands
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50/80 backdrop-blur-sm border-l-4 border-red-500 text-red-800 px-5 py-4 rounded-r-xl shadow-sm mb-8 animate-in fade-in slide-in-from-top-1">
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

            <form action="{{ route('admin.demands.update', $demand) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Left Column: Product Info -->
                    <div class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white shadow-sm p-8 space-y-6">
                        <div class="flex items-center gap-3 mb-2 pb-2 border-b border-gray-100">
                            <span class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-green-600 text-xs">
                                <i class="fas fa-box"></i>
                            </span>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Product Specification</h3>
                        </div>

                        <!-- Product Name -->
                        <div class="relative w-full">
                            <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $demand->product_name) }}" placeholder=" " required
                                   class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                            <label for="product_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                Product Name <span class="text-red-400">*</span>
                            </label>
                        </div>

                        <!-- Variety / Size -->
                        <div class="relative w-full">
                            <input type="text" name="variety_size" id="variety_size" value="{{ old('variety_size', $demand->variety_size) }}" placeholder=" "
                                   class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                            <label for="variety_size" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                Variety / Size
                            </label>
                        </div>

                        <!-- Quantity & Unit -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative w-full">
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $demand->quantity) }}" min="1" placeholder=" " required
                                       class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm text-center">
                                <label for="quantity" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                    Quantity <span class="text-red-400">*</span>
                                </label>
                            </div>
                            <div class="relative w-full group">
                                <select name="unit" id="unit"
                                        class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value="" disabled {{ !old('unit', $demand->unit) ? 'selected' : '' }}>Select Unit</option>
                                    @foreach(['pieces', 'trays', 'dozen', 'kilos', 'boxes', 'bunches', 'sacks'] as $unit)
                                        <option value="{{ $unit }}" {{ old('unit', $demand->unit) == $unit ? 'selected' : '' }}>{{ ucfirst($unit) }}</option>
                                    @endforeach
                                </select>
                                <label for="unit" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded">Unit</label>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4 pt-4">
                            <div class="relative w-full">
                                <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $demand->delivery_date ? $demand->delivery_date->format('Y-m-d') : '') }}" required
                                       class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all shadow-sm">
                                <label for="delivery_date" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded">Delivery Date <span class="text-red-400">*</span></label>
                            </div>
                            <div class="relative w-full">
                                <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $demand->deadline ? \Carbon\Carbon::parse($demand->deadline)->format('Y-m-d') : '') }}"
                                       class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all shadow-sm">
                                <label for="deadline" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded">Deadline</label>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Location & Admin Info -->
                    <div class="space-y-8">
                        
                        <!-- Location Info -->
                        <div class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white shadow-sm p-8 space-y-6">
                            <div class="flex items-center gap-3 mb-2 pb-2 border-b border-gray-100">
                                <span class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 text-xs">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Delivery Location</h3>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative w-full">
                                    <input type="text" name="purok_street" id="purok_street" value="{{ old('purok_street', $demand->purok_street) }}" placeholder=" "
                                           class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                                    <label for="purok_street" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-amber-600 rounded cursor-text">
                                        Purok / Street
                                    </label>
                                </div>
                                <div class="relative w-full">
                                    <input type="text" name="barangay" id="barangay" value="{{ old('barangay', $demand->barangay) }}" placeholder=" "
                                           class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                                    <label for="barangay" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-amber-600 rounded cursor-text">
                                        Barangay
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative w-full">
                                    <input type="text" name="municipality_city" id="municipality_city" value="{{ old('municipality_city', $demand->municipality_city) }}" placeholder=" "
                                           class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                                    <label for="municipality_city" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-amber-600 rounded cursor-text">
                                        Municipality / City
                                    </label>
                                </div>
                                <div class="relative w-full">
                                    <input type="text" name="province" id="province" value="{{ old('province', $demand->province) }}" placeholder=" "
                                           class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                                    <label for="province" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-amber-600 rounded cursor-text">
                                        Province
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Status Info -->
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-8 text-white shadow-lg shadow-green-200">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white text-xs backdrop-blur-md">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                <h3 class="text-sm font-bold uppercase tracking-wider">Administrative Metadata</h3>
                            </div>

                            <div class="space-y-6">
                                <div class="relative w-full group">
                                    <select name="status" id="status" required
                                            class="peer w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 focus:bg-white focus:text-gray-800 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all shadow-sm appearance-none cursor-pointer font-bold text-white">
                                        @foreach(['unmatched', 'matched', 'in negotiation', 'completed'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $demand->status) == $status ? 'selected' : '' }} class="bg-white text-gray-800">{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <label for="status" class="absolute left-4 -top-2.5 text-[10px] font-bold uppercase tracking-widest text-white/80 bg-green-600 px-1 rounded">Internal Status</label>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white group-focus-within:text-green-500">
                                        <i class="fas fa-chevron-down text-sm"></i>
                                    </div>
                                </div>

                                <div class="relative w-full group">
                                    <select name="buyer_id" id="buyer_id" required
                                            class="peer w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 focus:bg-white focus:text-gray-800 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all shadow-sm appearance-none cursor-pointer font-bold text-white">
                                        @foreach($buyers as $buyer)
                                            <option value="{{ $buyer->id }}" {{ old('buyer_id', $demand->buyer_id) == $buyer->id ? 'selected' : '' }} class="bg-white text-gray-800">
                                                {{ $buyer->first_name }} {{ $buyer->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="buyer_id" class="absolute left-4 -top-2.5 text-[10px] font-bold uppercase tracking-widest text-white/80 bg-green-600 px-1 rounded">Responsible Buyer</label>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white group-focus-within:text-green-500">
                                        <i class="fas fa-user-circle text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-10 pt-8 flex justify-end gap-4 border-t border-gray-100">
                    <a href="{{ route('admin.demands.index') }}" 
                       class="bg-white hover:bg-gray-50 text-gray-700 font-bold px-8 py-3.5 rounded-2xl border border-gray-200 shadow-sm transition-all duration-200">
                        Cancel Changes
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-green-600 to-emerald-500 text-white font-black px-12 py-3.5 rounded-2xl shadow-xl shadow-green-200 hover:shadow-green-300 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Save Demand Updates
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection