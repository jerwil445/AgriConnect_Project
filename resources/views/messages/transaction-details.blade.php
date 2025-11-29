<div class="p-4 border-b border-gray-200 bg-white">
    <h2 class="text-lg font-bold text-gray-900">Transaction Details</h2>
</div>

<div class="flex-1 overflow-y-auto p-4">
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
                <span class="text-gray-600">Product Name</span>
                <span class="font-medium">{{ $transaction->product->product_name }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Quantity</span>
                <span class="font-medium">{{ $transaction->product->quantity }} {{ $transaction->product->unit }}</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-gray-600">Unit Price</span>
                <span class="font-medium">₱{{ number_format($transaction->final_price, 2) }}/{{ $transaction->product->unit }}</span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-900 font-medium">Total Amount</span>
                <span class="font-bold text-green-600 text-lg">₱{{ number_format($transaction->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

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
    

    <!-- Confirm Order Button - Visible to buyers for active transactions or when product has remaining quantity -->
    @if(Auth::id() == $transaction->buyer_id && ($transaction->status == 'Active' || ($transaction->status == 'Ordered' && $transaction->product->quantity > 0 && $transaction->product->status != 'Sold Out')))
    <div class="mb-4">
        <button id="confirmOrderBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Confirm Order
        </button>
    </div>
    
    <!-- Order Confirmation Modal -->
    <div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Confirm Order</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="orderForm" action="{{ route('transactions.order', $transaction->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="order_quantity">
                            Quantity to Order
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               id="order_quantity" name="order_quantity" type="number" 
                               min="1" max="{{ $transaction->product->quantity }}" 
                               value="{{ $transaction->demand ? $transaction->demand->quantity : '1' }}" required>
                        <p class="text-gray-600 text-xs mt-1">Available: {{ $transaction->product->quantity }} {{ $transaction->product->unit }}</p>
                    </div>
                    
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
                    
                    <div class="flex items-center justify-between">
                        <button type="button" id="cancelOrder" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Place Order
                        </button>
                    </div>
                </form>
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
    })
    .catch(error => {
        console.error('Error loading transaction:', error);
    });
}

// Order Confirmation Modal Functionality
function initializeOrderModal() {
    const confirmOrderBtn = document.getElementById('confirmOrderBtn');
    const orderModal = document.getElementById('orderModal');
    const closeModal = document.getElementById('closeModal');
    const cancelOrder = document.getElementById('cancelOrder');
    
    if (confirmOrderBtn) {
        confirmOrderBtn.addEventListener('click', function() {
            orderModal.classList.remove('hidden');
        });
    }
    
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            orderModal.classList.add('hidden');
        });
    }
    
    if (cancelOrder) {
        cancelOrder.addEventListener('click', function() {
            orderModal.classList.add('hidden');
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === orderModal) {
            orderModal.classList.add('hidden');
        }
    });
}

// Function to show interest in a product
function showInterestModal(productId) {
    // For now, we'll just show an alert
    // In a real implementation, this would open a modal to express interest
    alert('Interest shown for product ID: ' + productId + '. In a full implementation, this would open a modal to express interest in this product.');
}
</script>