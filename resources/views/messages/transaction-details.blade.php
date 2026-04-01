<div class="p-4 border-b border-gray-200 bg-white">
    <h2 class="text-lg font-bold text-gray-900">Transaction Details</h2>
</div>

@php
    $availableQuantity = $transaction->product->remainingInventory->remaining_quantity ?? $transaction->product->quantity;
    $currentTotal = $transaction->total_amount ?: (($transaction->product->total_amount ?? ($transaction->product->quantity * $transaction->product->price)));
@endphp

<div class="flex-1 overflow-y-auto p-4">
    <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Product Information</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Product</span>
                <span class="font-medium">{{ $transaction->product->product_name }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Variety/Size</span>
                <span class="font-medium">{{ $transaction->product->variety_size ?: 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Available Quantity</span>
                <span class="font-medium quantity-value">{{ $availableQuantity }} {{ $transaction->product->unit }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Price per Unit</span>
                <span class="font-medium price-value">₱{{ number_format($transaction->product->price, 2) }}/{{ $transaction->product->unit }}</span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-900 font-medium">Total Amount</span>
                <span class="font-bold text-green-600 text-lg total-amount">₱{{ number_format($currentTotal, 2) }}</span>
            </div>
        </div>
    </div>

    @php
        // Get the search term for related products
        $demandName = $transaction->demand ? $transaction->demand->product_name : $transaction->product->product_name;
        // Simple search term: just the first word if it's too specific, or the whole name
        $searchTerm = explode(' ', trim($demandName))[0];

        // Statuses that indicate a confirmed/placed order — exclude from "Other Product Matches"
        $orderStatuses = ['Ordered', 'Accepted', 'Prepared', 'In Transit', 'Delivered', 'Rejected'];

        if (Auth::id() == $transaction->buyer_id) {
            $otherTransactions = \App\Models\Transaction::where('farmer_id', $transaction->farmer_id)
                ->where('buyer_id', $transaction->buyer_id)
                ->where('id', '!=', $transaction->id)
                ->whereNotIn('status', $orderStatuses)
                ->whereHas('product', function($query) use ($searchTerm) {
                    $query->where('product_name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->with(['product'])
                ->get();

            if ($otherTransactions->isEmpty()) {
                $otherProducts = \App\Models\Product::where('farmer_id', $transaction->farmer_id)
                    ->where('id', '!=', $transaction->product_id)
                    ->where('status', 'Available')
                    ->where('product_name', 'LIKE', '%' . $searchTerm . '%')
                    ->get();

                $otherTransactions = collect();
                foreach ($otherProducts as $product) {
                    $pseudoTransaction = new \stdClass();
                    $pseudoTransaction->product = $product;
                    $pseudoTransaction->final_quantity = $product->quantity;
                    $pseudoTransaction->final_price = $product->price;
                    $pseudoTransaction->total_amount = $product->total_amount ?? ($product->quantity * $product->price);
                    $pseudoTransaction->id = 'product_' . $product->id;
                    $otherTransactions->push($pseudoTransaction);
                }
            }
        } else {
            $otherTransactions = \App\Models\Transaction::where('buyer_id', $transaction->buyer_id)
                ->where('farmer_id', $transaction->farmer_id)
                ->where('id', '!=', $transaction->id)
                ->whereNotIn('status', $orderStatuses)
                ->whereHas('product', function($query) use ($searchTerm) {
                    $query->where('product_name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->with(['product'])
                ->get();
        }
    @endphp

    @if(Auth::id() == $transaction->buyer_id && $availableQuantity > 0 && $transaction->product->status != 'Sold Out')
        <div class="mb-6 flex justify-end">
            <button id="confirmOrderBtn" class="group relative inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all duration-200 bg-green-600 border border-transparent rounded-xl shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 overflow-hidden">
                <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 -ml-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Confirm Order
            </button>
        </div>

        <div id="orderModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            {{-- Backdrop --}}
            <div id="orderModalBackdrop" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>
            
            {{-- Modal Panel --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh] animate-modal-in z-10">
                
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center relative overflow-hidden">
                    <div class="absolute left-0 top-0 w-2 h-full bg-green-500"></div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2" id="modal-title">
                            <span class="p-1.5 rounded-lg bg-green-100 text-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            Complete Your Order
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 pl-10">Please verify your details below</p>
                    </div>
                    <button type="button" id="closeModal" class="rounded-full p-2 bg-white text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition-colors shadow-sm border border-gray-200">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form Content --}}
                <form id="orderForm" action="{{ route('transactions.order', $transaction->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    
                    <div class="flex-1 overflow-y-auto px-6 py-5">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            
                            {{-- Left Column: User Details --}}
                            <div class="lg:col-span-7 space-y-5">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Delivery Details
                                </h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="buyer_name">Full Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <i class="fas fa-user text-xs"></i>
                                            </div>
                                            <input class="block w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 transition-colors" 
                                                id="buyer_name" name="buyer_name" type="text" value="{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="buyer_phone">Phone Number</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <i class="fas fa-phone-alt text-xs"></i>
                                            </div>
                                            <input class="block w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 transition-colors" 
                                                id="buyer_phone" name="buyer_phone" type="text" required placeholder="e.g. 09123456789">
                                        </div>
                                    </div>
                                    
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="buyer_email">Email Address</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <i class="fas fa-envelope text-xs"></i>
                                            </div>
                                            <input class="block w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 transition-colors" 
                                                id="buyer_email" name="buyer_email" type="email" value="{{ Auth::user()->email ?? '' }}" required>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="buyer_address">Full Delivery Address</label>
                                        <textarea class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 transition-colors resize-none" 
                                            id="buyer_address" name="buyer_address" rows="3" required placeholder="Street, Barangay, Municipality, Province..."></textarea>
                                    </div>
                                </div>
                                
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide border-b border-gray-100 pb-2 mt-6 mb-4 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    Payment
                                </h4>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="payment_method">Preferred Payment Method</label>
                                    <div class="relative">
                                        <select class="appearance-none block w-full pl-3 pr-10 py-3 bg-white border border-gray-300 hover:border-gray-400 rounded-xl text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors" 
                                            id="payment_method" name="payment_method" required>
                                            <option value="" disabled selected>Select an option...</option>
                                            <option value="cash_on_delivery">Cash on Delivery (COD)</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="e_wallet">E-Wallet (GCash, PayMaya)</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Right Column: Order Summary --}}
                            <div class="lg:col-span-5 flex flex-col h-full relative">
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl border border-green-200 p-6 shadow-sm flex-1 flex flex-col justify-between relative overflow-hidden">
                                    {{-- Sub-decorative --}}
                                    <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-green-200/50 transform -rotate-12" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                    </svg>
                                    
                                    <div>
                                        <h4 class="text-xs font-bold text-green-600 uppercase tracking-widest mb-4">Order Summary</h4>
                                        
                                        <div class="bg-white rounded-xl p-4 shadow-sm border border-green-100 mb-5">
                                            <div class="flex items-start gap-3">
                                                <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                                    <i class="fas fa-box-open text-xl"></i>
                                                </div>
                                                <div>
                                                    <h5 class="font-bold text-gray-900 leading-tight">{{ $transaction->product->product_name }}</h5>
                                                    <p class="text-xs font-medium text-gray-500 mt-0.5">{{ $transaction->product->variety_size ?: 'Standard' }}</p>
                                                    <p class="text-xs font-semibold text-indigo-600 mt-1">Available: {{ $availableQuantity }} {{ $transaction->product->unit }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-800 mb-1.5" for="order_quantity_simple">Quantity ({{ $transaction->product->unit }})</label>
                                                <div class="flex items-center">
                                                    <input class="block w-full px-4 py-2.5 bg-white border border-green-200 rounded-lg text-lg font-bold text-center text-gray-900 focus:ring-green-500 focus:border-green-500"
                                                        id="order_quantity_simple"
                                                        name="order_quantity"
                                                        type="number"
                                                        min="1"
                                                        max="{{ $availableQuantity }}"
                                                        value="{{ $transaction->demand ? min($transaction->demand->quantity, $availableQuantity) : 1 }}"
                                                        data-price-per-unit="{{ $transaction->product->price }}"
                                                        required>
                                                </div>
                                            </div>
                                            
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-600 font-medium">Unit Price</span>
                                                <span class="font-bold text-gray-900" id="price_per_unit_value">₱{{ number_format($transaction->product->price, 2) }}</span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center text-sm border-t border-green-200/60 pt-3">
                                                <span class="text-gray-600 font-medium">Delivery</span>
                                                <span class="text-gray-500 italic text-xs">Calculated Later</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t-2 border-green-200 relative z-10">
                                        <div class="flex justify-between items-end">
                                            <span class="text-sm font-bold text-gray-700 uppercase">Estimated Total</span>
                                            <span id="order-total-preview" class="text-3xl font-black text-green-700 tracking-tight">₱{{ number_format($transaction->product->price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Footer Actions --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                        <button type="button" id="cancelOrder" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Back
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-green-600 rounded-xl shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-colors flex items-center justify-center gap-2">
                            <span>Place Order Now</span>
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($otherTransactions->count() > 0)
        <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-3">Other Product Matches</h3>
            <div class="space-y-3">
                @foreach($otherTransactions as $otherTransaction)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $otherTransaction->product->product_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $otherTransaction->product->variety_size ?: 'No variety/size' }}</p>
                            <p class="text-sm text-gray-600">{{ $otherTransaction->final_quantity }} {{ $otherTransaction->product->unit }} @ ₱{{ number_format($otherTransaction->final_price, 2) }}/{{ $otherTransaction->product->unit }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">₱{{ number_format($otherTransaction->total_amount, 2) }}</p>
                            @if(isset($otherTransaction->id) && str_starts_with((string) $otherTransaction->id, 'product_'))
                                <button class="mt-1 text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-2 py-1 rounded transition-colors"
                                    onclick="showInterestModal('{{ $otherTransaction->product->id }}')">
                                    Show Interest
                                </button> 
                            @else
                                <button class="mt-1 text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-2 py-1 rounded transition-colors view-details-btn"
                                    data-transaction-id="{{ $otherTransaction->id }}"
                                    data-product-name="{{ $otherTransaction->product->product_name }}"
                                    data-variety="{{ $otherTransaction->product->variety_size ?: 'N/A' }}"
                                    data-quantity="{{ $otherTransaction->final_quantity }}"
                                    data-unit="{{ $otherTransaction->product->unit }}"
                                    data-price="{{ number_format($otherTransaction->final_price, 2) }}"
                                    data-total="{{ number_format($otherTransaction->total_amount, 2) }}"
                                    data-status="{{ $otherTransaction->product->status ?? 'Available' }}"
                                    data-harvest="{{ $otherTransaction->product->harvest_date ? $otherTransaction->product->harvest_date->format('M d, Y') : 'N/A' }}"
                                    data-location="{{ implode(', ', array_filter([$otherTransaction->product->barangay, $otherTransaction->product->municipality_city])) ?: 'Location not specified' }}"
                                    data-image="{{ $otherTransaction->product->image ? asset('storage/'.$otherTransaction->product->image) : '' }}"
                                    data-farmer="{{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}"
                                    data-farmer-email="{{ $transaction->farmer->email }}"
                                    data-farmer-phone="{{ $transaction->farmer->user->phone_number ?? 'N/A' }}"
                                    data-farm-name="{{ $transaction->farmer->farm_name ?? '' }}"
                                    data-farm-address="{{ $transaction->farmer->farm_address ?? 'N/A' }}"
                                    onclick="openProductDetailsModal(this)">
                                    View Details
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Product Details Modal (redesigned to match reference card) --}}
    <div id="productDetailsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="pdm-title">
        {{-- Backdrop --}}
        <div id="pdm-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        {{-- Modal Card --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-modal-in">

            {{-- Close button (top-right) --}}
            <button onclick="closeProductDetailsModal()"
                class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-white/80 hover:bg-white text-gray-500 hover:text-gray-800 shadow transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Two-column body --}}
            <div class="flex flex-col md:flex-row">

                {{-- LEFT — Image panel --}}
                <div class="md:w-5/12 bg-gradient-to-br from-green-50 to-green-100 flex flex-col items-center justify-center p-6 min-h-[220px] relative">
                    <div id="pdm-img-wrap" class="w-full flex flex-col items-center justify-center">
                        <img id="pdm-product-img"
                            src="" alt="Product image"
                            class="hidden max-h-44 object-contain rounded-xl shadow">
                        <div id="pdm-no-image" class="flex flex-col items-center gap-2 text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-green-500 font-medium">No Image Available</p>
                        </div>
                    </div>
                    {{-- Status badge --}}
                    <div class="absolute bottom-3 left-3">
                        <span id="pdm-status-badge" class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-white text-green-700 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                            <span id="pdm-status-text">Available</span>
                        </span>
                    </div>
                </div>

                {{-- RIGHT — Product info --}}
                <div class="md:w-7/12 p-5 flex flex-col gap-3 overflow-y-auto max-h-[85vh]">

                    {{-- Label + Name --}}
                    <div>
                        <p class="text-xs font-bold text-green-600 tracking-widest uppercase mb-0.5">Fresh from the Farm</p>
                        <h2 id="pdm-title" class="text-2xl font-extrabold text-gray-900 leading-tight"></h2>
                        <div class="w-8 h-0.5 bg-green-500 mt-1 rounded-full"></div>
                    </div>

                    {{-- Price card --}}
                    <div class="bg-green-700 rounded-xl px-4 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-green-200 text-xs font-semibold uppercase tracking-widest mb-0.5">Market Price</p>
                            <p class="text-white font-extrabold text-2xl leading-none">
                                <span id="pdm-price"></span>
                                <span id="pdm-price-unit" class="text-sm font-normal text-green-200"></span>
                            </p>
                        </div>
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Three stat chips --}}
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-purple-50 rounded-xl p-3 flex flex-col items-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <p class="text-purple-500 text-[10px] font-bold uppercase tracking-wider">Variety</p>
                            <p id="pdm-variety" class="text-purple-700 text-xs font-semibold mt-0.5"></p>
                        </div>
                        <div class="bg-amber-50 rounded-xl p-3 flex flex-col items-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-amber-500 text-[10px] font-bold uppercase tracking-wider">Harvested</p>
                            <p id="pdm-harvest" class="text-amber-700 text-xs font-semibold mt-0.5"></p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3 flex flex-col items-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <p class="text-green-500 text-[10px] font-bold uppercase tracking-wider">Available</p>
                            <p id="pdm-quantity" class="text-green-700 text-xs font-semibold mt-0.5"></p>
                        </div>
                    </div>

                    {{-- Farm location --}}
                    <div class="border border-gray-100 rounded-xl px-4 py-3 flex items-center gap-3 bg-gray-50">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Farm Location</p>
                            <p id="pdm-location" class="text-gray-700 text-sm font-medium"></p>
                        </div>
                    </div>

                    {{-- About the Farmer --}}
                    <div class="border border-gray-100 rounded-xl p-4 bg-white">
                        <div class="flex items-center gap-2 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-sm font-bold text-gray-800">About the Farmer</p>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-green-600 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            </div>
                            <div>
                                <p id="pdm-farmer-name" class="font-bold text-gray-900 text-sm"></p>
                                <p id="pdm-farm-name" class="text-green-600 text-xs"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-blue-50 rounded-lg px-3 py-2">
                                <p class="text-blue-400 text-[9px] font-bold uppercase tracking-wider mb-0.5">Contact</p>
                                <p id="pdm-farmer-phone" class="text-blue-700 text-xs font-semibold"></p>
                            </div>
                            <div class="bg-purple-50 rounded-lg px-3 py-2">
                                <p class="text-purple-400 text-[9px] font-bold uppercase tracking-wider mb-0.5">Farm Address</p>
                                <p id="pdm-farm-address" class="text-purple-700 text-xs font-semibold"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer action --}}
                    <button id="pdm-switch-btn"
                        class="w-full py-3 rounded-xl bg-green-700 hover:bg-green-800 text-white font-bold text-sm transition-colors flex items-center justify-center gap-2 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Switch to This Transaction
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Parties</h3>
        <div class="space-y-4">
            <div class="flex items-center p-1 bg-gray-50 rounded-lg">
                <div class="bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-800 font-bold text-lg">{{ substr($transaction->buyer->first_name, 0, 1) }}</span>
                </div>
                <div class="ml-4">
                    <h4 class="font-bold text-gray-900">Buyer</h4>
                    <p class="text-gray-700">{{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}</p>
                    <p class="text-sm text-gray-500">{{ $transaction->buyer->email }}</p>
                </div>
            </div>

            <div class="flex items-center p-1 bg-gray-50 rounded-lg">
                <div class="bg-green-100 rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <span class="text-green-800 font-bold text-lg">{{ substr($transaction->farmer->first_name, 0, 1) }}</span>
                </div>
                <div class="ml-4">
                    <h4 class="font-bold text-gray-900">Farmer</h4>
                    <p class="text-gray-700">{{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}</p>
                    <p class="text-sm text-gray-500">{{ $transaction->farmer->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
