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
        <div class="mb-4">
            <button id="confirmOrderBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Confirm Order
            </button>
        </div>

        <div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border shadow-lg rounded-md bg-white max-w-3xl w-full">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Confirm Order</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="orderForm" action="{{ route('transactions.order', $transaction->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_name">Full Name</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="buyer_name" name="buyer_name" type="text"
                                    value="{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_email">Email</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="buyer_email" name="buyer_email" type="email"
                                    value="{{ Auth::user()->email ?? '' }}" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_phone">Phone Number</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="buyer_phone" name="buyer_phone" type="text" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_address">Delivery Address</label>
                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="buyer_address" name="buyer_address" required></textarea>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_method">Payment Method</label>
                                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash_on_delivery">Cash on Delivery</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="e_wallet">E-Wallet</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-900">{{ $transaction->product->product_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $transaction->product->variety_size ?: 'No variety/size specified' }}</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="order_quantity_simple">Quantity to Order</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                                    id="order_quantity_simple"
                                    name="order_quantity"
                                    type="number"
                                    min="1"
                                    max="{{ $availableQuantity }}"
                                    value="{{ $transaction->demand ? min($transaction->demand->quantity, $availableQuantity) : 1 }}"
                                    data-price-per-unit="{{ $transaction->product->price }}"
                                    required>
                                <p class="text-gray-600 text-xs mt-1">Available: {{ $availableQuantity }} {{ $transaction->product->unit }}</p>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Price per Unit</span>
                                    <span id="price_per_unit_value">₱{{ number_format($transaction->product->price, 2) }}/{{ $transaction->product->unit }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Estimated Total</span>
                                    <span id="order-total-preview" class="font-semibold text-green-600">₱{{ number_format($transaction->product->price, 2) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <button type="button" id="cancelOrder" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Place Order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
