@extends('layouts.farmers_page')

@section('content')
<div class=" px-4 py-8 ml-60">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Order Management</h1>
        
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Egg Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->buyer->first_name }} {{ $order->buyer->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->product->egg_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->final_quantity }} {{ $order->product->unit }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status == 'Ordered') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'Accepted') bg-green-100 text-green-800
                                        @elseif($order->status == 'Rejected') bg-red-100 text-red-800
                                        @elseif($order->status == 'Active') bg-blue-100 text-blue-800
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
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                                    id="farmer-order-actions-menu-button-{{ $order->id }}"
                                                    aria-expanded="false" 
                                                    aria-haspopup="true"
                                                    onclick="toggleOrderDropdown({{ $order->id }}, 'farmer')">
                                                Actions
                                                <svg class="-mr-1 ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="farmer-order-dropdown-menu-{{ $order->id }}" 
                                             class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                             role="menu" 
                                             aria-orientation="vertical" 
                                             aria-labelledby="farmer-order-actions-menu-button-{{ $order->id }}"
                                             style="position: absolute; z-index: 9999;">
                                            <div class="py-1" role="none">
                                                <a href="{{ route('orders.show', ['transaction' => $order->id]) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-eye mr-2 text-indigo-500"></i>View Order Details
                                                </a>
                                                <a href="{{ route('farmer.messages') }}?transaction_id={{ $order->id }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-comment mr-2 text-blue-500"></i>Chat
                                                </a>
                                                
                                                <button type="button" 
                                                        class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 accept-order-btn" 
                                                        role="menuitem"
                                                        data-transaction-id="{{ $order->id }}">
                                                    <i class="fas fa-check mr-2 text-green-500"></i>Accept Order
                                                </button>
                                                
                                                <button type="button" 
                                                        class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 reject-order-btn" 
                                                        role="menuitem"
                                                        data-transaction-id="{{ $order->id }}">
                                                    <i class="fas fa-times mr-2 text-red-500"></i>Reject Order
                                                </button>
                                                
                                                <button type="button" 
                                                        class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 mark-prepared-btn" 
                                                        role="menuitem"
                                                        data-transaction-id="{{ $order->id }}">
                                                    <i class="fas fa-box mr-2 text-blue-500"></i>Mark Prepared
                                                </button>
                                                
                                                <button type="button" 
                                                        class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 assign-logistics-btn" 
                                                        role="menuitem"
                                                        data-transaction-id="{{ $order->id }}">
                                                    <i class="fas fa-truck mr-2 text-purple-500"></i>Assign Logistics
                                                </button>
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
            <div class="text-center py-12">
                <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No orders yet</h3>
                <p class="text-gray-500">You don't have any orders to manage.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Accept Order button functionality
    document.querySelectorAll('.accept-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to accept this order?')) {
                fetch(`/transactions/${transactionId}/accept`, {
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

    // Reject Order button functionality
    document.querySelectorAll('.reject-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to reject this order? This action cannot be undone.')) {
                fetch(`/transactions/${transactionId}/reject`, {
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

    // Mark Prepared button functionality
    document.querySelectorAll('.mark-prepared-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to mark this order as prepared?')) {
                fetch(`/transactions/${transactionId}/mark-prepared`, {
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

    // Assign Logistics button functionality
    document.querySelectorAll('.assign-logistics-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to assign logistics for this order?')) {
                fetch(`/transactions/${transactionId}/assign-logistics`, {
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