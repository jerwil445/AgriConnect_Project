@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6" style="overflow: visible;">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Product Management</h2>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center" id="search-form">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input type="hidden" name="farmer" value="{{ request('farmer') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="relative">
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search products..."
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 sm:text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="ml-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">Search</button>
                    </form>

                    <div class="flex items-center gap-4">
                        <div class="relative inline-block text-left">
                            <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" onclick="toggleDropdownById('farmer-filter-dropdown')">
                                Farmer: {{ request('farmer') ? 'Selected' : 'All Farmers' }}
                            </button>
                            <div id="farmer-filter-dropdown" class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ request()->fullUrlWithoutQuery(['farmer', 'page']) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">All Farmers</a>
                                    @foreach($farmers as $farmer)
                                        <a href="{{ request()->fullUrlWithQuery(['farmer' => $farmer->id, 'page' => 1]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                            {{ $farmer->first_name }} {{ $farmer->last_name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="relative inline-block text-left">
                            <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" onclick="toggleDropdownById('status-filter-dropdown')">
                                Status: {{ request('status') ?: 'All Statuses' }}
                            </button>
                            <div id="status-filter-dropdown" class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ request()->fullUrlWithoutQuery(['status', 'page']) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">All Statuses</a>
                                    @foreach($statuses as $status)
                                        <a href="{{ request()->fullUrlWithQuery(['status' => $status, 'page' => 1]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                            {{ $status }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variety/Size</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price per Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harvest Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->product_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->variety_size ?: 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->farmer->user->first_name }} {{ $product->farmer->user->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->quantity }} {{ $product->unit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{ number_format($product->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{ number_format($product->total_amount ?? ($product->quantity * $product->price), 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($product->status == 'Available') bg-green-100 text-green-800
                                            @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $product->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($product->harvest_date)->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                                                    onclick="toggleDropdownById('dropdown-menu-{{ $product->id }}')">
                                                Actions
                                            </button>

                                            <div id="dropdown-menu-{{ $product->id }}" class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                <div class="py-1">
                                                    <a href="{{ route('admin.products.view', $product) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                                        <i class="fas fa-eye mr-2 text-blue-500"></i>View Details
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                                        <i class="fas fa-edit mr-2 text-green-500"></i>Edit
                                                    </a>
                                                    <a href="{{ route('admin.products.approve', $product) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                                        <i class="fas fa-check-circle mr-2 text-green-500"></i>Approve
                                                    </a>
                                                    <a href="{{ route('admin.products.reject', $product) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                                        <i class="fas fa-times-circle mr-2 text-yellow-500"></i>Mark Pending
                                                    </a>
                                                    <form action="{{ route('admin.products.delete', $product) }}" method="POST" class="inline delete-form" data-product-name="{{ $product->product_name }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                                            <i class="fas fa-trash mr-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const productName = this.getAttribute('data-product-name');
            if (confirm(`Are you sure you want to delete product ${productName}?`)) {
                this.submit();
            }
        });
    });

    function toggleDropdownById(id) {
        document.querySelectorAll('[id$="-dropdown"], [id^="dropdown-menu-"]').forEach((element) => {
            if (element.id !== id) {
                element.classList.add('hidden');
            }
        });

        const target = document.getElementById(id);
        if (target) {
            target.classList.toggle('hidden');
        }
    }

    window.addEventListener('click', function(e) {
        if (!e.target.closest('button') && !e.target.closest('.delete-form') && !e.target.closest('a')) {
            document.querySelectorAll('[id$="-dropdown"], [id^="dropdown-menu-"]').forEach(el => el.classList.add('hidden'));
        }
    });
</script>
@endsection
