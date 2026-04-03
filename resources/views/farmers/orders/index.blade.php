@extends('layouts.farmers_page')

@section('content')
    <div class="ml-64 mr-5 mt-5 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
        
        <!-- Subtle Background Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

        <main class="relative z-10 flex-1 p-8 lg:p-10">
            <div class="max-w-7xl mx-auto">
                
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-5 pb-6 border-b border-gray-100 gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20 text-2xl">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Order Management</h2>
                            <p class="text-sm text-gray-500 font-medium mt-1">Track and manage customer agricultural orders</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="hidden lg:flex items-center gap-6 px-6 py-2.5 bg-white/50 backdrop-blur-md rounded-2xl border border-white shadow-sm">
                            <div class="text-center border-r border-gray-100 pr-6">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total Active Orders</p>
                                <p class="text-lg font-black text-gray-800 mt-1">{{ $orders->total() }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest leading-none">Status</p>
                                <p class="text-xs font-black text-emerald-600 mt-1 uppercase tracking-tighter">Live Monitor</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Toolbar / Filters -->
                <div class="relative z-[60] bg-white/60 backdrop-blur-lg rounded-[2rem] border border-white shadow-sm p-6 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <form act   ion="{{ route('farmer.orders') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full">
                        <div class="flex items-center gap-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Show</label>
                            <select name="per_page" onchange="this.form.submit()"
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold text-gray-600 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all shadow-sm">
                                @foreach ([10, 25, 50, 100] as $count)
                                    <option value="{{ $count }}" {{ ($perPage ?? 10) == $count ? 'selected' : '' }}>
                                        {{ $count }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</label>
                            <select name="status" onchange="this.form.submit()"
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold text-gray-600 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all shadow-sm min-w-[140px]">
                                <option value="">All Streams</option>
                                @foreach ($orderStatuses as $statusOption)
                                    <option value="{{ $statusOption }}" {{ ($statusFilter ?? '') === $statusOption ? 'selected' : '' }}>
                                        {{ $statusOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative flex-1 lg:max-w-md group ml-auto">
                            <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search ID, buyer, or product..."
                                class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm text-sm font-medium">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Data Table -->
                <div class="bg-white/40 backdrop-blur-xl border border-white rounded-[2.5rem] shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Order Reference</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Buyer Context</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Product Details</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Volume & Value</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Current State</th>
                                    <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Management</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($orders as $order)
                                    <tr class="hover:bg-white/60 transition-all group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-black text-gray-800">#{{ $order->id }}</div>
                                            <div class="text-[10px] font-medium text-gray-400 mt-1 uppercase tracking-widest">{{ $order->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-700 leading-none">{{ $order->buyer->first_name }} {{ $order->buyer->last_name }}</div>
                                            <div class="text-[10px] font-medium text-emerald-600 mt-1.5 uppercase tracking-tighter hover:underline cursor-pointer">View Profile</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-center text-gray-300 font-black overflow-hidden bg-cover bg-center transition-all group-hover:scale-105"
                                                     style="background-image: url('{{ optional($order->product)->image ? asset('storage/' . $order->product->image) : (optional($order->product)->images && $order->product->images->count() > 0 ? asset('storage/' . $order->product->images->first()->image_path) : '') }}')">
                                                    @if(!optional($order->product)->image && (!optional($order->product)->images || $order->product->images->count() == 0)) <i class="fas fa-seedling text-gray-200"></i> @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-gray-800">{{ $order->product->product_name }}</div>
                                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $order->product->variety_size ?: 'Standard' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-black text-gray-800">{{ $order->final_quantity }} <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $order->product->unit }}</span></div>
                                            <div class="text-sm font-black text-indigo-700 mt-1">₱{{ number_format($order->total_amount, 2) }}</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex flex-col gap-1.5">
                                                <span class="px-3 py-1 text-[9px] font-black uppercase tracking-tight rounded-full border shadow-sm w-fit
                                                    @if ($order->status == 'Ordered') bg-yellow-50 text-yellow-700 border-yellow-100
                                                    @elseif($order->status == 'Accepted' || $order->status == 'Active') bg-emerald-50 text-emerald-700 border-emerald-100
                                                    @elseif($order->status == 'Rejected') bg-red-50 text-red-700 border-red-100
                                                    @else bg-gray-50 text-gray-700 border-gray-100 @endif">
                                                    Order: {{ $order->status }}
                                                </span>
                                                <span class="px-3 py-1 text-[9px] font-black uppercase tracking-tight rounded-full border shadow-sm w-fit
                                                    @if ($order->payment_status == 'Paid') bg-green-50 text-green-700 border-green-100
                                                    @else bg-amber-50 text-amber-700 border-amber-100 @endif">
                                                    Payment: {{ $order->payment_status }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('orders.show', ['transaction' => $order->id]) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-blue-100 shadow-sm" title="View Details">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                                
                                                <div class="relative">
                                                    <button type="button" onclick="toggleOrderDropdown({{ $order->id }}, 'farmer')" 
                                                            class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all duration-300 flex items-center justify-center border border-gray-200 shadow-sm">
                                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                                    </button>
                                                    <div id="farmer-order-dropdown-menu-{{ $order->id }}" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 z-[9999] overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200 text-left">
                                                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Lifecycle Actions</p>
                                                        </div>
                                                        <a href="{{ route('farmer.messages') }}?transaction_id={{ $order->id }}" class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-700 border-b border-gray-50">
                                                            <i class="fas fa-comment text-blue-500"></i> Open Channel
                                                        </a>
                                                        <button type="button" class="accept-order-btn w-full text-left flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 border-b border-gray-50" data-transaction-id="{{ $order->id }}">
                                                            <i class="fas fa-check-circle text-emerald-500"></i> Validate Order
                                                        </button>
                                                        <button type="button" class="reject-order-btn w-full text-left flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-red-50 hover:text-red-700 border-b border-gray-50" data-transaction-id="{{ $order->id }}">
                                                            <i class="fas fa-times-circle text-red-500"></i> Decline Stream
                                                        </button>
                                                        <button type="button" class="mark-prepared-btn w-full text-left flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 border-b border-gray-50" data-transaction-id="{{ $order->id }}">
                                                            <i class="fas fa-box text-indigo-500"></i> Signal Prepared
                                                        </button>
                                                        <button type="button" class="assign-logistics-btn w-full text-left flex items-center gap-2 px-4 py-3 text-xs font-bold text-emerald-800 bg-emerald-50/50 hover:bg-emerald-100 transition-colors" data-transaction-id="{{ $order->id }}">
                                                            <i class="fas fa-truck text-emerald-600"></i> Dispatch Logistics
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-8 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 mb-4 border-2 border-dashed border-gray-100">
                                                    <i class="fas fa-box-open text-3xl"></i>
                                                </div>
                                                <h4 class="text-lg font-black text-gray-300 uppercase tracking-widest">No Active Streams</h4>
                                                <p class="text-xs text-gray-400 mt-2 font-medium">You don't have any incoming agricultural orders at this moment.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($orders->hasPages())
                        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
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
                    if (confirm(
                            'Are you sure you want to reject this order? This action cannot be undone.'
                        )) {
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
