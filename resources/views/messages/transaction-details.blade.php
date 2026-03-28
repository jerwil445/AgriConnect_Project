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
        if (Auth::id() == $transaction->buyer_id) {
            $otherTransactions = \App\Models\Transaction::where('farmer_id', $transaction->farmer_id)
                ->where('buyer_id', $transaction->buyer_id)
                ->where('id', '!=', $transaction->id)
                ->with(['product'])
                ->get();

            if ($otherTransactions->isEmpty()) {
                $otherProducts = \App\Models\Product::where('farmer_id', $transaction->farmer_id)
                    ->where('id', '!=', $transaction->product_id)
                    ->where('status', 'Available')
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

<script>
function switchToTransaction(transactionId) {
    Promise.all([
        fetch(`/messages/conversation/${transactionId}`).then(response => response.text()),
        fetch(`/messages/transaction-details/${transactionId}`).then(response => response.text())
    ])
    .then(([conversationHtml, detailsHtml]) => {
        document.getElementById('conversation-container').innerHTML = conversationHtml;
        document.getElementById('transaction-details').innerHTML = detailsHtml;

        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-indigo-100', 'border-l-4', 'border-l-indigo-500');
            if (item.getAttribute('data-transaction-id') == transactionId) {
                item.classList.add('bg-indigo-100', 'border-l-4', 'border-l-indigo-500');
            }
        });

        if (typeof initializeMessaging === 'function') {
            initializeMessaging();
        }

        if (typeof initializeOrderModal === 'function') {
            initializeOrderModal();
        }
    })
    .catch(error => {
        console.error('Error loading transaction:', error);
    });
}

function showInterestModal(productId) {
    alert('Interest shown for product ID: ' + productId + '.');
}
</script>
