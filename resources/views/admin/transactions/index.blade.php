@extends('layouts.admin_page')

@section('content')
<div class="md:ml-64 mt-16 flex flex-col min-h-screen">
    <main class="flex-1 p-6 ">
        <div class="max-w-7xl mx-auto ">
            <div class="bg-white rounded-lg shadow p-6 overflow-auto" >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Transaction Management</h2>
                </div>

                <!-- Search and Entries Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <!-- Show Entries -->
                    <div class="relative inline-block text-left">
                        <div>
                            <button type="button" 
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                    id="per-page-menu-button"
                                    aria-expanded="false" 
                                    aria-haspopup="true"
                                    onclick="togglePerPageDropdown()">
                                Show: {{ request('per_page', 10) }} entries
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div id="per-page-dropdown" 
                             class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                             style="position: absolute; z-index: 9999;">
                            <div class="py-1" role="none">
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 10 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    10 entries
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 25, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 25 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    25 entries
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 50 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    50 entries
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 100, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 100 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    100 entries
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Dropdowns -->
                    <div class="flex items-center space-x-4">
                        <!-- Payment Status Filter -->
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" 
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                        id="payment-status-filter-menu-button"
                                        aria-expanded="false" 
                                        aria-haspopup="true"
                                        onclick="togglePaymentStatusFilterDropdown()">
                                    Payment: {{ request('payment_status') ? ucfirst(request('payment_status')) : 'All Statuses' }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div id="payment-status-filter-dropdown" 
                                 class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="position: absolute; z-index: 9999;">
                                <div class="py-1" role="none">
                                    <a href="{{ request()->fullUrlWithoutQuery(['payment_status', 'page']) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ !request('payment_status') ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        All Statuses
                                    </a>
                                    @foreach($paymentStatuses as $status)
                                    <a href="{{ request()->fullUrlWithQuery(['payment_status' => $status, 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('payment_status') == $status ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        {{ ucfirst($status) }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delivery Status Filter -->
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" 
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                        id="delivery-status-filter-menu-button"
                                        aria-expanded="false" 
                                        aria-haspopup="true"
                                        onclick="toggleDeliveryStatusFilterDropdown()">
                                    Delivery: {{ request('delivery_status') ? ucfirst(request('delivery_status')) : 'All Statuses' }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div id="delivery-status-filter-dropdown" 
                                 class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="position: absolute; z-index: 9999;">
                                <div class="py-1" role="none">
                                    <a href="{{ request()->fullUrlWithoutQuery(['delivery_status', 'page']) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ !request('delivery_status') ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        All Statuses
                                    </a>
                                    @foreach($deliveryStatuses as $status)
                                    <a href="{{ request()->fullUrlWithQuery(['delivery_status' => $status, 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('delivery_status') == $status ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        {{ ucfirst($status) }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex items-center" id="search-form">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
                        <input type="hidden" name="delivery_status" value="{{ request('delivery_status') }}">
                        <div class="relative">
                            <input type="text" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search transactions..."
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 sm:text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit"
                                class="ml-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Transactions Table -->
                <div class="overflow-x-auto" style="overflow: visible;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demand</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Status</th>
                                <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th> -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->product ? $transaction->product->product_name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->demand ? $transaction->demand->product_name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->buyer ? $transaction->buyer->first_name . ' ' . $transaction->buyer->last_name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->farmer ? $transaction->farmer->first_name . ' ' . $transaction->farmer->last_name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->final_quantity ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{ number_format($transaction->total_amount ?? 0, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($transaction->payment_status == 'Paid') bg-green-100 text-green-800
                                        @elseif($transaction->payment_status == 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($transaction->payment_status == 'Failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($transaction->payment_status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($transaction->delivery_status == 'Delivered') bg-green-100 text-green-800
                                        @elseif($transaction->delivery_status == 'Scheduled') bg-blue-100 text-blue-800
                                        @elseif($transaction->delivery_status == 'In Transit') bg-purple-100 text-purple-800
                                        @elseif($transaction->delivery_status == 'Cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($transaction->delivery_status ?? 'N/A') }}
                                    </span>
                                </td>
                                <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</td> -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Dropdown Actions -->
                                    <div class="relative inline-block text-left">
                                        <div>
                                            <button type="button" 
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                                    id="actions-menu-button-{{ $transaction->id }}"
                                                    aria-expanded="false" 
                                                    aria-haspopup="true"
                                                    onclick="toggleDropdown({{ $transaction->id }})">
                                                Actions
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="dropdown-menu-{{ $transaction->id }}" 
                                             class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                             role="menu" 
                                             aria-orientation="vertical" 
                                             aria-labelledby="actions-menu-button-{{ $transaction->id }}"
                                             style="position: absolute; z-index: 9999;">
                                            <div class="py-1" role="none">
                                                <a href="{{ route('admin.transactions.view', $transaction) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-eye mr-2 text-blue-500"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No transactions found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Toggle dropdown visibility
    function toggleDropdown(transactionId) {
        const dropdown = document.getElementById('dropdown-menu-' + transactionId);
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id^="dropdown-menu-"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Toggle per page dropdown
    function togglePerPageDropdown() {
        const dropdown = document.getElementById('per-page-dropdown');
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Toggle payment status filter dropdown
    function togglePaymentStatusFilterDropdown() {
        const dropdown = document.getElementById('payment-status-filter-dropdown');
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Toggle delivery status filter dropdown
    function toggleDeliveryStatusFilterDropdown() {
        const dropdown = document.getElementById('delivery-status-filter-dropdown');
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        if (!e.target.closest('[id$="-menu-button"]') && !e.target.closest('[id$="-dropdown"]')) {
            document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
</script>
@endsection