@extends('layouts.buyers_page')

@section('content')
    <div class="px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 overflow-visible">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">My Orders</h1>

            <form method="GET" class="mb-6 grid gap-4 md:grid-cols-3 items-end">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 whitespace-nowrap">Show</label>
                    <select name="per_page"
                        class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                        @foreach ([10, 25, 50, 100] as $count)
                            <option value="{{ $count }}" {{ ($perPage ?? 10) == $count ? 'selected' : '' }}>
                                {{ $count }}</option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 whitespace-nowrap">Status</label>
                    <select name="status"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                        <option value="">All</option>
                        @foreach ($orderStatuses as $statusOption)
                            <option value="{{ $statusOption }}"
                                {{ ($statusFilter ?? '') === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <input type="search" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search order ID, product, or farmer"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" />
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-200">
                        Search
                    </button>
                </div>
            </form>

            @if ($orders->count() > 0)
                <div class="overflow-x-auto overflow-visible">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order ID</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Amount</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Delivery Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order Date</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                @if (optional($order->product)->images && $order->product->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}"
                                                        alt="{{ $order->product->product_name ?? 'Product image' }}"
                                                        class="h-14 w-14 rounded-xl object-cover border border-gray-200">
                                                @elseif(optional($order->product)->image)
                                                    <img src="{{ asset('storage/' . $order->product->image) }}"
                                                        alt="{{ $order->product->product_name ?? 'Product image' }}"
                                                        class="h-14 w-14 rounded-xl object-cover border border-gray-200">
                                                @else
                                                    <div
                                                        class="h-14 w-14 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200">
                                                        <i class="fas fa-seedling text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $order->product->product_name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $order->product->variety_size ?? 'No variety/size' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->final_quantity }} {{ $order->product->unit ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($order->status == 'Ordered') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'Accepted') bg-green-100 text-green-800
                                        @elseif($order->status == 'Rejected') bg-red-100 text-red-800
                                        @elseif($order->status == 'Prepared') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'In Transit') bg-purple-100 text-purple-800
                                        @elseif($order->status == 'Delivered') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($order->payment_status == 'Paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status == 'Pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($order->delivery_status == 'Delivered') bg-green-100 text-green-800
                                        @elseif($order->delivery_status == 'Scheduled') bg-blue-100 text-blue-800
                                        @elseif($order->delivery_status == 'In Transit') bg-purple-100 text-purple-800
                                        @elseif($order->delivery_status == 'Prepared') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->delivery_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <!-- Dropdown Actions -->
                                        <div class="relative inline-block text-left">
                                            <div>
                                                <button type="button"
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                                    id="buyer-order-actions-menu-button-{{ $order->id }}"
                                                    aria-expanded="false" aria-haspopup="true"
                                                    onclick="toggleOrderDropdown({{ $order->id }}, 'buyer')">
                                                    Actions
                                                    <svg class="-mr-1 ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div id="buyer-order-dropdown-menu-{{ $order->id }}"
                                                class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                                role="menu" aria-orientation="vertical"
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

                                                    @if ($order->status == 'Ordered' && $order->payment_status == 'Pending')
                                                        <button type="button"
                                                            class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 mark-paid-btn"
                                                            role="menuitem" data-transaction-id="{{ $order->id }}">
                                                            <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>Mark
                                                            as Paid
                                                        </button>
                                                    @endif

                                                    @if ($order->delivery_status == 'In Transit' || $order->delivery_status == 'Prepared')
                                                        <button type="button"
                                                            class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 mark-delivered-btn"
                                                            role="menuitem" data-transaction-id="{{ $order->id }}">
                                                            <i class="fas fa-truck mr-2 text-blue-500"></i>Mark as
                                                            Delivered
                                                        </button>
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
                <div class="mt-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between text-sm text-gray-600">
                    <div>
                        Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of
                        {{ $orders->total() }} entries
                    </div>
                    <div class="overflow-x-auto">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No orders yet</h3>
                    <p class="text-gray-500">You haven't placed any orders yet.</p>
                    <a href="{{ route('demands.index') }}"
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
