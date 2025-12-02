<div class="p-4 border-b border-gray-200 bg-white">
    <h2 class="text-lg font-bold text-gray-900">Transaction Details</h2>
</div>

<div class="flex-1 overflow-y-auto p-4 ">
    <!-- Transaction ID -->
    <!-- <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="flex justify-between items-center">
            <span class="text-gray-600 font-medium">Transaction ID</span>
            <span class="font-bold text-lg">#{{ $transaction->id }}</span>
        </div>
    </div> -->

    <!-- Product Information -->
    <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Product Information</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Egg Type</span>
                <span class="font-medium">
                    @php
                        $eggTypes = [
                            'chicken' => 'Chicken',
                            'duck' => 'Duck',
                            'quail' => 'Quail',
                            'native_chicken' => 'Native Chicken',
                            'brown' => 'Brown Egg',
                            'white' => 'White Egg'
                        ];
                    @endphp
                    {{ $eggTypes[$transaction->product->egg_type] ?? ucfirst(str_replace('_', ' ', $transaction->product->egg_type)) }}
                </span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Quantity</span>
                <span class="font-medium quantity-value">{{ $transaction->product->quantity }} {{ $transaction->product->unit }}</span>
            </div>
            <!-- <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Unit Price</span>
                <span class="font-medium price-value">₱{{ number_format($transaction->final_price, 2) }}/{{ $transaction->product->unit }}</span>
            </div> -->
            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-900 font-medium">total Amount</span>
                <span class="font-bold text-green-600 text-lg unit-price">₱{{ number_format($transaction->product->price, 2) }} </span>
            </div>
        </div>
    </div>

    <!-- Egg Sizes Information -->
    @if($transaction->product->sizes && $transaction->product->sizes->count() > 0)
    <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Egg Sizes</h3>
        <div class="space-y-3">
            @foreach($transaction->product->sizes as $size)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                <div>
                    <h4 class="font-medium text-gray-900">{{ $size->size_name }}</h4>
                    <p class="text-sm text-gray-600">{{ $size->tray_count }} trays</p>
                </div>
                <div class="text-right">
                    <p class="font-medium">₱{{ number_format($size->price_per_tray ?? 0, 2) }}/tray</p>
                    <p class="text-sm text-gray-600">Total: ₱{{ number_format($size->total_price ?? 0, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Other Product Matches with Same User -->
    @php
        // Get other products from the same farmer that are matched with the buyer's demands
        if (Auth::id() == $transaction->buyer_id) {
            // Current user is buyer, get other products from the SAME farmer that match the buyer's demands
            // First, get the buyer's demands (only those that still exist)
            $buyerDemands = \App\Models\Demand::where('buyer_id', $transaction->buyer_id)->pluck('id');
            
            // Then, get transactions with the same farmer where the products match the buyer's demands
            // But only include transactions where:
            // 1. The demand still exists
            // 2. The transaction is associated with an existing match
            $otherTransactions = \App\Models\Transaction::where('farmer_id', $transaction->farmer_id)
                ->where('buyer_id', $transaction->buyer_id)
                ->where('id', '!=', $transaction->id)
                ->whereNotNull('demand_id') // Only include transactions where demand still exists
                ->whereExists(function ($query) {
                    $query->select(\DB::raw(1))
                        ->from('matches')
                        ->whereColumn('matches.demand_id', 'transactions.demand_id')
                        ->whereColumn('matches.product_id', 'transactions.product_id');
                })
                ->with(['product'])
                ->get();
                
            // If there are no transactions based on existing demands and matches, let's show other products from the same farmer
            if ($otherTransactions->isEmpty()) {
                // Get other products from the same farmer (excluding the current product)
                $otherProducts = \App\Models\Product::where('farmer_id', $transaction->farmer_id)
                    ->where('id', '!=', $transaction->product_id)
                    ->where('status', 'Available') // Only show available products
                    ->get();
                    
                // Create a collection of pseudo-transactions for these products
                $otherTransactions = collect();
                foreach ($otherProducts as $product) {
                    // Create a pseudo-transaction object for display purposes
                    $pseudoTransaction = new \stdClass();
                    $pseudoTransaction->product = $product;
                    $pseudoTransaction->final_quantity = $product->quantity;
                    $pseudoTransaction->final_price = $product->price;
                    $pseudoTransaction->total_amount = $product->quantity * $product->price;
                    $pseudoTransaction->id = 'product_' . $product->id; // Use product ID for identification
                    $otherTransactions->push($pseudoTransaction);
                }
            }
        } else {
            // Current user is farmer, get other products for the SAME buyer that match the farmer's products
            // First, get the farmer's products
            $farmerProducts = \App\Models\Product::where('farmer_id', $transaction->farmer_id)->pluck('id');
            
            // Then, get transactions with the same buyer where the demands match the farmer's products
            // But only include transactions where:
            // 1. The demand still exists
            // 2. The transaction is associated with an existing match
            $otherTransactions = \App\Models\Transaction::where('buyer_id', $transaction->buyer_id)
                ->where('farmer_id', $transaction->farmer_id)
                ->where('id', '!=', $transaction->id)
                ->whereNotNull('demand_id') // Only include transactions where demand still exists
                ->whereExists(function ($query) {
                    $query->select(\DB::raw(1))
                        ->from('matches')
                        ->whereColumn('matches.demand_id', 'transactions.demand_id')
                        ->whereColumn('matches.product_id', 'transactions.product_id');
                })
                ->with(['product'])
                ->get();
                
            // If there are no transactions based on existing demands and matches, let's show other products from the same farmer
            if ($otherTransactions->isEmpty()) {
                // Get other products from the same farmer (excluding the current product)
                $otherProducts = \App\Models\Product::where('farmer_id', $transaction->farmer_id)
                    ->where('id', '!=', $transaction->product_id)
                    ->where('status', 'Available') // Only show available products
                    ->get();
                    
                // Create a collection of pseudo-transactions for these products
                $otherTransactions = collect();
                foreach ($otherProducts as $product) {
                    // Create a pseudo-transaction object for display purposes
                    $pseudoTransaction = new \stdClass();
                    $pseudoTransaction->product = $product;
                    $pseudoTransaction->final_quantity = $product->quantity;
                    $pseudoTransaction->final_price = $product->price;
                    $pseudoTransaction->total_amount = $product->quantity * $product->price;
                    $pseudoTransaction->id = 'product_' . $product->id; // Use product ID for identification
                    $otherTransactions->push($pseudoTransaction);
                }
            }
        }
    @endphp
    

    <!-- Confirm Order Button - Visible to buyers when product is available and has quantity -->
    @if(Auth::id() == $transaction->buyer_id && $transaction->product->quantity > 0 && $transaction->product->status != 'Sold Out')
    <div class="mb-4">
        <button id="confirmOrderBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Confirm Order
        </button>
    </div>
    
    <!-- Order Confirmation Modal -->
    <div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border shadow-lg rounded-md bg-white max-w-4xl w-full">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Confirm Order</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class=" flex ">
                    <!-- Left Column - Input Fields -->
                    <div class="bg-gree-50 rounded-lg">
                        <form id="orderForm" action="{{ route('transactions.order', $transaction->id) }}" method="POST" class="flex space-x-4 ">
                            @csrf
                            
                            <!-- Display validation errors -->
                            @if ($errors->any())
                                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="w-full">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_name">
                                        Full Name
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                        id="buyer_name" name="buyer_name" type="text" 
                                        value="{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_email">
                                        Email
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                        id="buyer_email" name="buyer_email" type="email" 
                                        value="{{ Auth::user()->email ?? '' }}" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_phone">
                                        Phone Number
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                        id="buyer_phone" name="buyer_phone" type="text" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_address">
                                        Delivery Address
                                    </label>
                                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                            id="buyer_address" name="buyer_address" required></textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_method">
                                        Payment Method
                                    </label>
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="e_wallet">E-Wallet</option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-between mt-6">
                                <button type="button" id="cancelOrder" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Place Order
                                </button>
                            </div>
                            </div>
                            <div>
                                <!-- Simple quantity input if no sizes -->
                                @if(!$transaction->product->sizes || $transaction->product->sizes->count() == 0)
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="order_quantity_simple">
                                        Quantity to Order
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                        id="order_quantity_simple" name="order_quantity" type="number" 
                                        min="1" max="{{ $transaction->product->quantity }}" 
                                        value="{{ $transaction->demand ? $transaction->demand->quantity : '1' }}" required>
                                    <p class="text-gray-600 text-xs mt-1">Available: {{ $transaction->product->quantity }} {{ $transaction->product->unit }}</p>
                                </div>
                                @else
                                <!-- Hidden inputs for total calculation for products with sizes -->
                                <input type="hidden" id="total_quantity" name="order_quantity" value="0">
                                <input type="hidden" id="total_price" name="total_price" value="0">
                                
                                <!-- Size selection inputs (moved inside form) -->
                                @if($transaction->product->sizes && $transaction->product->sizes->count() > 0)
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Select Sizes to Order
                                    </label>
                                    
                                    <div class="space-y-3" id="sizeSelectionContainer">
                                        @foreach($transaction->product->sizes as $size)
                                        <div class="flex items-center justify-between p-3 bg-white border rounded">
                                            <div>
                                                <div class="font-medium">{{ $size->size_name }}</div>
                                                <div class="text-sm text-gray-600">₱{{ number_format($size->price_per_tray ?? 0, 2) }}/tray</div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <button type="button" class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center decrease-tray" data-size-id="{{ $size->id }}" data-price-per-tray="{{ $size->price_per_tray ?? 0 }}">-</button>
                                                <input type="number" 
                                                    class="w-16 text-center border rounded tray-input" 
                                                    id="tray_count_{{ $size->id }}" 
                                                    name="tray_counts[{{ $size->id }}]" 
                                                    min="0" 
                                                    max="{{ $size->tray_count }}" 
                                                    value="0"
                                                    data-size-id="{{ $size->id }}"
                                                    data-price-per-tray="{{ $size->price_per_tray ?? 0 }}">
                                                <button type="button" class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center increase-tray" data-size-id="{{ $size->id }}" data-price-per-tray="{{ $size->price_per_tray ?? 0 }}" data-max="{{ $size->tray_count }}">+</button>
                                                <span class="text-sm text-gray-600">/ {{ $size->tray_count }} trays</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endif
                                <div>
                                @if($transaction->product->sizes && $transaction->product->sizes->count() > 0)
                                <div class="mb-4">
                                    
                                    <!-- Display calculated totals -->
                                    <div class="mt-4 p-3 bg-gray-50 rounded">
                                        <div class="flex justify-between mb-1">
                                            <span>Total Trays:</span>
                                            <span id="display_total_quantity">0</span>
                                        </div>
                                        <div class="flex justify-between font-bold">
                                            <span>Total Price:</span>
                                            <span id="display_total_price">₱0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                            
                            <!-- <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_name">
                                    Full Name
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                       id="buyer_name" name="buyer_name" type="text" 
                                       value="{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_email">
                                    Email
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                       id="buyer_email" name="buyer_email" type="email" 
                                       value="{{ Auth::user()->email ?? '' }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_phone">
                                    Phone Number
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                       id="buyer_phone" name="buyer_phone" type="text" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="buyer_address">
                                    Delivery Address
                                </label>
                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                          id="buyer_address" name="buyer_address" required></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_method">
                                    Payment Method
                                </label>
                                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash_on_delivery">Cash on Delivery</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="e_wallet">E-Wallet</option>
                                </select>
                            </div> -->
                            
                            <!-- <div class="flex items-center justify-between mt-6">
                                <button type="button" id="cancelOrder" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Place Order
                                </button>
                            </div> -->
                        </form>
                    </div>
                    
                    <!-- Middle Column - Size Selection -->
                    <!-- <div>
                        @if($transaction->product->sizes && $transaction->product->sizes->count() > 0)
                        <div class="mb-4">
                            
                            //Display calculated totals
                            <div class="mt-4 p-3 bg-gray-50 rounded">
                                <div class="flex justify-between mb-1">
                                    <span>Total Trays:</span>
                                    <span id="display_total_quantity">0</span>
                                </div>
                                <div class="flex justify-between font-bold">
                                    <span>Total Price:</span>
                                    <span id="display_total_price">₱0.00</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div> -->
                    
                    <!-- Right Column - Transaction Details -->
                    <div class="ml-6">
                        <h4 class="text-md font-bold text-gray-900 mb-4">Transaction Details</h4>
                        
                        <div class="space-y-4">
                            <!-- Product Information -->
                            <div class="border-b border-gray-200 pb-3">
                                <h5 class="font-medium text-gray-800 mb-2">Product</h5>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Egg Type:</span>
                                    <span class="font-medium">
                                        @php
                                            $eggTypes = [
                                                'chicken' => 'Chicken',
                                                'duck' => 'Duck',
                                                'quail' => 'Quail',
                                                'native_chicken' => 'Native Chicken',
                                                'brown' => 'Brown Egg',
                                                'white' => 'White Egg'
                                            ];
                                        @endphp
                                        {{ $eggTypes[$transaction->product->egg_type] ?? ucfirst(str_replace('_', ' ', $transaction->product->egg_type)) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-600">Quantity:</span>
                                    <span class="font-medium product-quantity">{{ $transaction->product->quantity }} {{ $transaction->product->unit }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-600">Unit Price:</span>
                                    <span class="font-medium">₱{{ number_format($transaction->product->price, 2) }} </span>
                                </div>
                            </div>
                            
                            <!-- Egg Sizes Information -->
                            @if($transaction->product->sizes && $transaction->product->sizes->count() > 0)
                            <div class="border-b border-gray-200 pb-3">
                                <h5 class="font-medium text-gray-800 mb-2">Egg Sizes</h5>
                                <div class="space-y-2">
                                    @foreach($transaction->product->sizes as $size)
                                    <div class="flex justify-between items-center p-2 bg-white rounded border">
                                        <div>
                                            <span class="font-medium">{{ $size->size_name }}</span>
                                            <span class="text-gray-600 text-sm ml-2" id="size-tray-count-{{ $size->id }}">({{ $size->tray_count }} trays)</span>
                                        </div>
                                        <div class="text-right">
                                            <div id="size-unit-price-{{ $size->id }}">₱{{ number_format($size->price_per_tray ?? 0, 2) }}/tray</div>
                                            <div class="text-gray-600 text-xs">Total: <span id="size-total-price-{{ $size->id }}">₱{{ number_format($size->total_price ?? 0, 2) }}</span></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Total Amount -->
                            <div class="pt-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 font-medium">Total Amount</span>
                                    <span class="font-bold text-green-600 text-lg total-amount">₱{{ number_format($transaction->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if($otherTransactions->count() > 0)
    <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Other Product Matches</h3>
        <p class="text-sm text-gray-600 mb-3">This user has other products available for transaction:</p>
        <div class="space-y-3">
            @foreach($otherTransactions as $otherTransaction)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                <div>
                    <h4 class="font-medium text-gray-900">{{ $otherTransaction->product->product_name }}</h4>
                    <p class="text-sm text-gray-600">{{ $otherTransaction->final_quantity }} {{ $otherTransaction->product->unit }} @ ₱{{ number_format($otherTransaction->final_price, 2) }}/{{ $otherTransaction->product->unit }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-green-600">₱{{ number_format($otherTransaction->total_amount, 2) }}</p>
                    @if(isset($otherTransaction->id) && strpos($otherTransaction->id, 'product_') === 0)
                        <!-- This is a pseudo-transaction for a product, not a real transaction -->
                        <button class="mt-1 text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-2 py-1 rounded transition-colors"
                                onclick="showInterestModal('{{ $otherTransaction->product->id }}')">
                            Show Interest
                        </button>
                    @else
                        <!-- This is a real transaction -->
                        <button class="mt-1 text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-2 py-1 rounded transition-colors"
                                onclick="switchToTransaction('{{ $otherTransaction->id }}')">
                            View Details
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Parties Information -->
    <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Parties</h3>
        <div class="space-y-4 relative overflow-hidden">
            <!-- Buyer -->
            <div class="flex items-center p-1 bg-gray-50 rounded-lg">
                <div class="bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-800 font-bold text-lg">
                        {{ substr($transaction->buyer->first_name, 0, 1) }}
                    </span>
                </div>
                <div class="ml-4">
                    <h4 class="font-bold text-gray-900">Buyer</h4>
                    <p class="text-gray-700">{{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}</p>
                    <p class="text-sm text-gray-500 text-wrap">{{ $transaction->buyer->email }}</p>
                </div>
            </div>

            <!-- Farmer -->
            <div class="flex items-center p-1 bg-gray-50 rounded-lg">
                <div class="bg-green-100 rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <span class="text-green-800 font-bold text-lg">
                        {{ substr($transaction->farmer->first_name, 0, 1) }}
                    </span>
                </div>
                <div class="ml-4">
                    <h4 class="font-bold text-gray-900">Farmer</h4>
                    <p class="text-gray-700">{{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}</p>
                    <p class="text-sm text-gray-500">{{ $transaction->farmer->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Information -->
    <!-- <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Status</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium">Transaction Status</span>
                <span class="px-3 py-1 {{ $transaction->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full text-sm font-medium">
                    {{ $transaction->status }}
                </span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium">Payment Status</span>
                <span class="px-3 py-1 {{ $transaction->payment_status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full text-sm font-medium">
                    {{ $transaction->payment_status }}
                </span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-600 font-medium">Delivery Status</span>
                <span class="px-3 py-1 {{ $transaction->delivery_status == 'Delivered' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }} rounded-full text-sm font-medium">
                    {{ $transaction->delivery_status }}
                </span>
            </div>
        </div>
    </div> -->

    <!-- Dates -->
    <!-- <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-3">Important Dates</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium">Created</span>
                <span class="text-sm font-medium">{{ $transaction->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-600 font-medium">Last Updated</span>
                <span class="text-sm font-medium">{{ $transaction->updated_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
    </div> -->
</div>

<script>
function switchToTransaction(transactionId) {
    // Load the new transaction details
    Promise.all([
        fetch(`/messages/conversation/${transactionId}`).then(response => response.text()),
        fetch(`/messages/transaction-details/${transactionId}`).then(response => response.text())
    ])
    .then(([conversationHtml, detailsHtml]) => {
        document.getElementById('conversation-container').innerHTML = conversationHtml;
        document.getElementById('transaction-details').innerHTML = detailsHtml;
                                    
        // Update the conversation item in the list to show this transaction as selected
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-indigo-100', 'border-l-4', 'border-l-indigo-500');
            if (item.getAttribute('data-transaction-id') == transactionId) {
                item.classList.add('bg-indigo-100', 'border-l-4', 'border-l-indigo-500');
            }
        });
                                    
        // Re-initialize message functionality
        initializeMessaging();
        
        // Initialize size selection when content is loaded dynamically
        if (typeof initializeSizeSelection === 'function') {
            initializeSizeSelection();
        }
    })
    .catch(error => {
        console.error('Error loading transaction:', error);
    });
}

// Function to show interest in a product
function showInterestModal(productId) {
    // For now, we'll just show an alert
    // In a real implementation, this would open a modal to express interest
    alert('Interest shown for product ID: ' + productId + '. In a full implementation, this would open a modal to express interest in this product.');
}
</script>