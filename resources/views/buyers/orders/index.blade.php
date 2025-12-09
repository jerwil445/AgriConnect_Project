@extends('layouts.buyers_page')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Orders</h1>
        <p class="text-gray-600">Track and manage all your orders in one place</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Orders Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <i class="fas fa-shopping-bag text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Order History</h2>
                        <p class="text-sm text-gray-500">{{ $orders->count() }} orders found</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total Amount</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Payment Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Delivery Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order Date</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->product->egg_type ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->final_quantity }} {{ $order->product->unit ?? '' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status == 'Ordered') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'Accepted') bg-green-100 text-green-800
                                        @elseif($order->status == 'Rejected') bg-red-100 text-red-800
                                        @elseif($order->status == 'Prepared') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'In Transit') bg-purple-100 text-purple-800
                                        @elseif($order->status == 'Delivered') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->payment_status == 'Paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status == 'Pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->delivery_status == 'Delivered') bg-green-100 text-green-800
                                        @elseif($order->delivery_status == 'Scheduled') bg-blue-100 text-blue-800
                                        @elseif($order->delivery_status == 'In Transit') bg-purple-100 text-purple-800
                                        @elseif($order->delivery_status == 'Prepared') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $order->delivery_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Dropdown Actions -->
                                    <div class="relative inline-block text-left">
                                        <div>
                                            <button type="button" 
                                                    class="inline-flex justify-center items-center px-4 py-2 rounded-lg border-2 border-gray-300 shadow-sm bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200"
                                                    id="buyer-order-actions-menu-button-{{ $order->id }}"
                                                    aria-expanded="false" 
                                                    aria-haspopup="true"
                                                    onclick="toggleOrderDropdown({{ $order->id }}, 'buyer')">
                                                Actions
                                                <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="buyer-order-dropdown-menu-{{ $order->id }}" 
                                             class="hidden absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-white border border-gray-200 focus:outline-none z-50"
                                             role="menu" 
                                             aria-orientation="vertical" 
                                             aria-labelledby="buyer-order-actions-menu-button-{{ $order->id }}"
                                             style="position: absolute; z-index: 9999;">
                                            <div class="py-1" role="none">
                                                <a href="{{ route('orders.show', ['transaction' => $order->id]) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-eye mr-2 text-indigo-500"></i>View Order Details
                                                </a>
                                                <a href="{{ route('buyer.messages') }}?transaction_id={{ $order->id }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-comment mr-2 text-blue-500"></i>Chat with Farmer
                                                </a>
                                                
                                                @if($order->payment_status == 'Pending' && in_array($order->status, ['Ordered', 'Accepted', 'Prepared', 'In Transit']))
                                                <button type="button" 
                                                        class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 mark-paid-btn" 
                                                        role="menuitem"
                                                        data-transaction-id="{{ $order->id }}">
                                                    <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>Mark as Paid
                                                </button>
                                                @endif
                                                
                                                @if($order->delivery_status == 'In Transit' || $order->delivery_status == 'Prepared')
                                                <button type="button" 
                                                        class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 mark-delivered-btn" 
                                                        role="menuitem"
                                                        data-transaction-id="{{ $order->id }}">
                                                    <i class="fas fa-truck mr-2 text-blue-500"></i>Mark as Delivered
                                                </button>
                                                @endif
                                                
                                                @if($order->status == 'Delivered')
                                                    @if($order->farmerReview)
                                                        <div class="text-gray-500 px-4 py-2 text-sm flex items-center">
                                                            <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                                            <span>Reviewed</span>
                                                        </div>
                                                    @else
                                                        <a href="{{ route('buyer.orders.review', $order->id) }}" 
                                                           class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                           role="menuitem">
                                                            <i class="fas fa-star mr-2 text-yellow-500"></i>Leave a Review
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16 px-4">
                <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-bag text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Orders Yet</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">You haven't placed any orders yet. Start browsing our products to make your first order!</p>
                <a href="{{ route('buyer.dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Mark Paid button functionality
    document.querySelectorAll('.mark-paid-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to mark this order as paid?')) {
                fetch(`/transactions/${transactionId}/mark-paid`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request.');
                });
            }
        });
    });
    
    // Mark Delivered button functionality
    document.querySelectorAll('.mark-delivered-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to mark this order as delivered?')) {
                fetch(`/transactions/${transactionId}/mark-delivered-by-buyer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request.');
                });
            }
        });
    });
    
    // Toggle dropdown visibility
    window.toggleOrderDropdown = function(orderId, userType) {
        const dropdown = document.getElementById(userType + '-order-dropdown-menu-' + orderId);
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown-menu-' + orderId + '"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
});
</script>
@endsection