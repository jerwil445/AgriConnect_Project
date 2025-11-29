@php
    $isFarmer = auth()->user()->farmer ? true : false;
@endphp

@extends($isFarmer ? 'layouts.farmers_page' : 'layouts.buyers_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 {{ $isFarmer ? 'ml-64' : '' }}">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Order Details</h1>
            <a href="{{ $isFarmer ? route('farmer.orders') : route('buyer.orders') }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Back to Orders
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Order Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Information</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-medium">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Date:</span>
                        <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->status == 'Ordered') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'Accepted') bg-green-100 text-green-800
                            @elseif($order->status == 'Rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $order->status }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->payment_status == 'Paid') bg-green-100 text-green-800
                            @elseif($order->payment_status == 'Pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $order->payment_status }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->delivery_status == 'Delivered') bg-green-100 text-green-800
                            @elseif($order->delivery_status == 'Scheduled') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $order->delivery_status }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Product Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Information</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Product Name:</span>
                        <span class="font-medium">{{ $order->product->product_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Quantity:</span>
                        <span class="font-medium">{{ $order->final_quantity }} {{ $order->product->unit }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Unit Price:</span>
                        <span class="font-medium">₱{{ number_format($order->final_price, 2) }}/{{ $order->product->unit }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-bold text-green-600">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Buyer Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Buyer Information</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium">{{ $order->buyer->first_name }} {{ $order->buyer->last_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $order->buyer->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phone:</span>
                        <span class="font-medium">{{ $order->buyer_phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Address:</span>
                        <span class="font-medium">{{ $order->buyer_address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Farmer Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Farmer Information</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium">{{ $order->farmer->first_name }} {{ $order->farmer->last_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $order->farmer->email }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
            @if(auth()->user()->buyer && $order->status == 'Ordered')
                <a href="{{ route('buyer.messages') }}?transaction_id={{ $order->id }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="fas fa-comment mr-2"></i> Chat with Farmer
                </a>
            @elseif(auth()->user()->farmer && $order->status == 'Ordered')
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none accept-order-btn"
                        data-transaction-id="{{ $order->id }}">
                    <i class="fas fa-check mr-2"></i> Accept Order
                </button>
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none reject-order-btn"
                        data-transaction-id="{{ $order->id }}">
                    <i class="fas fa-times mr-2"></i> Reject Order
                </button>
            @endif
            
            @if(auth()->user()->buyer && $order->payment_status != 'Paid' && $order->status == 'Accepted')
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none mark-paid-btn"
                        data-transaction-id="{{ $order->id }}">
                    <i class="fas fa-money-bill mr-2"></i> Mark as Paid
                </button>
            @endif
            
            @if(auth()->user()->farmer && $order->status == 'Accepted')
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none mark-prepared-btn"
                        data-transaction-id="{{ $order->id }}">
                    <i class="fas fa-box mr-2"></i> Mark as Prepared
                </button>
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none assign-logistics-btn"
                        data-transaction-id="{{ $order->id }}">
                    <i class="fas fa-truck mr-2"></i> Assign Logistics
                </button>
            @endif
        </div>
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
                .then(response => response.json())
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
                    method: 'POST',n                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '{{ $isFarmer ? route('farmer.orders') : route('buyer.orders') }}';
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
                .then(response => response.json())
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
                .then(response => response.json())
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
                .then(response => response.json())
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
                .then(response => response.json())
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
                .then(response => response.json())
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
});
</script>
@endsection