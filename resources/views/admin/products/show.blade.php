@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 pb-6 border-b border-gray-100 gap-4">
                <div class="flex items-center gap-5">
                    @if($product->images->count() > 0)
                        <div class="w-24 h-24 rounded-2xl overflow-hidden shadow-lg border-2 border-white flex-shrink-0">
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-600/20 flex-shrink-0">
                            <i class="fas fa-box-open text-3xl"></i>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">{{ $product->product_name }}</h2>
                        <div class="flex flex-wrap items-center mt-3 gap-2">
                            <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border
                                @if($product->status == 'Available') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($product->status == 'Pending') bg-amber-50 text-amber-700 border-amber-200
                                @else bg-red-50 text-red-700 border-red-200 @endif">
                                @if($product->status == 'Available') <i class="fas fa-check-circle mr-1.5 mt-0.5"></i>
                                @elseif($product->status == 'Pending') <i class="fas fa-clock mr-1.5 mt-0.5"></i>
                                @else <i class="fas fa-times-circle mr-1.5 mt-0.5"></i> @endif
                                {{ $product->status }}
                            </span>
                            
                            @if($product->category)
                                <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border bg-emerald-50 text-emerald-700 border-emerald-200">
                                    <i class="fas fa-leaf mr-1.5 mt-0.5"></i> {{ $product->category }}
                                </span>
                            @endif

                            @if($product->variety_size)
                                <span class="px-3 py-1 inline-flex text-[11px] font-bold uppercase tracking-wider rounded-full shadow-sm border bg-gray-50 text-gray-600 border-gray-200">
                                    <i class="fas fa-tag mr-1.5 mt-0.5"></i> {{ $product->variety_size }}
                                </span>
                            @endif

                            <span class="text-sm text-gray-500 font-medium ml-2">Posted on {{ $product->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-colors flex items-center group">
                        <i class="fas fa-arrow-left mr-2 text-gray-400 group-hover:text-gray-600"></i> Back
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-400 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md transition-all flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit Product
                    </a>
                </div>
            </div>

            <!-- Main Info Grids -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-20">
                
                <!-- Center Column: Core Information -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-green-700 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i> Pricing & Inventory Details
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                            <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-gray-200/50 text-5xl pointer-events-none"><i class="fas fa-boxes"></i></div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Original Quantity</p>
                                <p class="text-xl font-bold text-gray-900 mt-1 relative z-10">{{ $product->quantity }} <span class="text-sm font-semibold text-gray-500">{{ $product->unit }}</span></p>
                            </div>
                            
                            <div class="bg-green-50/50 rounded-2xl p-4 border border-green-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-green-200/50 text-5xl pointer-events-none"><i class="fas fa-layer-group"></i></div>
                                <p class="text-xs font-medium text-green-700 uppercase">Remaining Stock</p>
                                <p class="text-xl font-bold text-green-900 mt-1 relative z-10">{{ $product->remainingInventory?->remaining_quantity ?? $product->quantity }} <span class="text-sm font-semibold text-green-700/60">{{ $product->unit }}</span></p>
                            </div>
                            
                            <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-indigo-200/50 text-5xl pointer-events-none"><i class="fas fa-money-bill-wave"></i></div>
                                <p class="text-xs font-medium text-indigo-700 uppercase">Price per Unit</p>
                                <p class="text-xl font-bold text-indigo-900 mt-1 relative z-10">₱{{ number_format($product->price, 2) }}</p>
                            </div>

                            <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100/50 relative overflow-hidden">
                                <div class="absolute -right-2 -bottom-2 text-blue-200/50 text-5xl pointer-events-none"><i class="fas fa-calculator"></i></div>
                                <p class="text-xs font-medium text-blue-700 uppercase">Total Value</p>
                                <p class="text-xl font-bold text-blue-900 mt-1 relative z-10">₱{{ number_format($product->total_amount ?? ($product->quantity * $product->price), 2) }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-2">Description</h4>
                                <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100 text-sm text-gray-700 leading-relaxed min-h-[100px]">
                                    {{ $product->description ?: 'No description provided for this product.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Gallery -->
                    @if($product->images->count() > 0)
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                        <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-green-700 flex items-center gap-2">
                                <i class="fas fa-images"></i> Media Gallery
                            </h3>
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $product->images->count() }} Photos</span>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($product->images as $image)
                                <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="group relative aspect-square overflow-hidden rounded-2xl border border-gray-200 shadow-sm bg-gray-100 block">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 group-hover:rotate-1">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                        <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all font-bold text-xl"></i>
                                    </div>
                                    @if($loop->first)
                                        <div class="absolute top-2 left-2 bg-black/70 backdrop-blur text-white text-[9px] font-bold px-2 py-0.5 rounded-full">COVER</div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Right Column: Sidebar Info -->
                <div class="space-y-8">
                    
                    <!-- Analytics & Logistics -->
                    <div class="bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-5 pb-2 border-b border-gray-100">Analytics & Logistics</h3>
                        
                        <dl class="space-y-4">
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase mb-1 flex items-center gap-1.5"><i class="fas fa-calendar-alt text-green-500"></i> Expected Harvest</dt>
                                <dd class="text-sm font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100">{{ optional($product->harvest_date)->format('F d, Y') ?? 'Not specified' }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase mb-1 flex items-center gap-1.5"><i class="fas fa-clock text-blue-500"></i> System Registration</dt>
                                <dd class="text-sm font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100">{{ $product->created_at->format('F d, Y g:i A') }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase mb-1 flex items-center gap-1.5"><i class="fas fa-sync text-indigo-500"></i> Last Updated</dt>
                                <dd class="text-sm font-semibold text-gray-900 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100">{{ $product->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Origin / Farmer Data -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50/50 backdrop-blur-md border border-green-100 rounded-[2rem] shadow-sm relative overflow-hidden p-6">
                        <div class="absolute -right-4 -top-4 text-green-200 opacity-40 transform rotate-12 pointer-events-none">
                            <i class="fas fa-tractor text-8xl"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-green-800 mb-5 pb-2 border-b border-green-200/50 flex items-center gap-2">
                                <i class="fas fa-user-tag"></i> Supplier Details
                            </h3>
                            
                            <dl class="space-y-4">
                                <div class="flex flex-col">
                                    <dt class="text-[10px] uppercase font-bold text-green-700/70 mb-0.5">Farmer Name</dt>
                                    <dd class="text-sm font-bold text-gray-900">
                                        <a href="{{ route('admin.users.show', $product->farmer->user) }}" class="hover:text-green-600 transition-colors flex items-center gap-1">
                                            {{ $product->farmer->user->first_name }} {{ $product->farmer->user->last_name }} <i class="fas fa-external-link-alt text-[10px] text-gray-400"></i>
                                        </a>
                                    </dd>
                                </div>
                                
                                <div class="flex flex-col">
                                    <dt class="text-[10px] uppercase font-bold text-green-700/70 mb-0.5">Farm Identity</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $product->farmer->farm_name ?? 'Individual Farmer' }}</dd>
                                </div>

                                <div class="flex flex-col">
                                    <dt class="text-[10px] uppercase font-bold text-green-700/70 mb-0.5">Contact</dt>
                                    <dd class="text-sm font-medium text-gray-700 space-y-1">
                                        <div class="flex items-center gap-2"><i class="fas fa-envelope text-gray-400 w-3"></i> {{ $product->farmer->user->email }}</div>
                                        <div class="flex items-center gap-2"><i class="fas fa-phone text-gray-400 w-3"></i> {{ $product->farmer->user->phone_number ?? 'No phone' }}</div>
                                    </dd>
                                </div>

                                <div class="flex flex-col pt-3 border-t border-green-200/50 mt-2">
                                    <dt class="text-[10px] uppercase font-bold text-green-700/70 mb-1">Origin Location</dt>
                                    <dd class="text-sm font-semibold text-gray-800 leading-snug">
                                        {{ $product->purok_street ? $product->purok_street . ', ' : '' }}
                                        {{ $product->barangay ? $product->barangay . ', ' : '' }}<br>
                                        {{ $product->municipality_city ? $product->municipality_city . ', ' : '' }}
                                        {{ $product->province ?? $product->farmer->farm_address ?? 'Not specified' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="mt-8 border border-red-100 bg-red-50/50 rounded-2xl p-5">
                        <div class="mb-4">
                            <p class="text-sm font-bold text-red-700 flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Administrative Actions</p>
                        </div>
                        <form action="{{ route('admin.products.delete', $product) }}" method="POST" class="delete-form w-full" data-product-name="{{ $product->product_name }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-white border border-red-200 hover:bg-red-600 hover:text-white hover:border-red-600 text-red-600 font-bold px-4 py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-sm text-sm">
                                <i class="fas fa-trash-alt"></i> Permanently Delete Product
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

@vite('resources/js/admin/products/admin-products-show.js')
@endsection
