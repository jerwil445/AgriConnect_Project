@extends('layouts.farmers_page')

@section('title', 'Product Listings • AgriConnect')

@section('content')
<div class="ml-60">
    @php
        $hasFilters = $search !== '' || $statusFilter !== '' || $sort !== 'latest' || $perPage !== 10;
    @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-green-700">My Product Listings</h1>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-green-500 transition">
                <i class="fas fa-plus"></i>
                Add New Product
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/70">
            <form method="GET" action="{{ route('products.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div>
                        <label for="search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
                        <input type="text" name="search" id="search" value="{{ $search }}"
                            placeholder="Search product name, variety, description, or unit"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="per_page" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Show Entries</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            @foreach([5, 10, 25, 50] as $entries)
                                <option value="{{ $entries }}" {{ $perPage === $entries ? 'selected' : '' }}>{{ $entries }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
                        <select name="status" id="status" onchange="this.form.submit()"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">All Statuses</option>
                            @foreach(['Available', 'Pending', 'Sold Out'] as $statusOption)
                                <option value="{{ $statusOption }}" {{ $statusFilter === $statusOption ? 'selected' : '' }}>
                                    {{ $statusOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">Sort By</label>
                        <select name="sort" id="sort" onchange="this.form.submit()"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price High-Low</option>
                            <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price Low-High</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-green-500 transition">
                            Search
                        </button>
                        @if($hasFilters)
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="mt-4 flex flex-col gap-2 text-sm text-gray-500 md:flex-row md:items-center md:justify-between">
                <p>
                    Showing
                    <span class="font-semibold text-gray-700">{{ $products->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-semibold text-gray-700">{{ $products->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-700">{{ $products->total() }}</span>
                    entries
                </p>
                @if($hasFilters)
                    <p class="text-xs text-gray-400">Search and dropdown filters stay applied while changing pages.</p>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variety/Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price per Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harvest Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->image)
                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->product_name }}</div>
                                        @if($product->description)
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ $product->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->variety_size ?: 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $product->quantity }} {{ $product->unit }}</div>
                                @if($product->remainingInventory)
                                    <div class="text-xs text-green-600">{{ $product->remainingInventory->remaining_quantity }} remaining</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{ number_format($product->total_amount ?? ($product->quantity * $product->price), 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($product->harvest_date)->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($product->status == 'Available') bg-green-100 text-green-800
                                    @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $product->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="relative inline-block text-left">
                                    <button type="button"
                                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                                        id="product-actions-menu-{{ $product->id }}">
                                        Actions
                                        <i class="fas fa-chevron-down ml-2"></i>
                                    </button>

                                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10"
                                        id="dropdown-menu-{{ $product->id }}">
                                        <div class="py-1">
                                            <a href="{{ route('products.show', $product) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-eye mr-2"></i> View
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-edit mr-2"></i> Edit
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-trash mr-2"></i> Delete
                                                </button>
                                            </form>
                                            <div class="border-t border-gray-100"></div>
                                            @foreach(['Available', 'Pending', 'Sold Out'] as $status)
                                                <a href="#"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onclick="event.preventDefault(); changeProductStatus({{ $product->id }}, '{{ $status }}');">
                                                    {{ $status }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No products found</h3>
                                    @if($hasFilters)
                                        <p class="text-gray-500 mb-4">No products matched your current search or dropdown filters.</p>
                                        <a href="{{ route('products.index') }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                                            Clear Filters
                                        </a>
                                    @else
                                        <p class="text-gray-500 mb-4">Get started by adding your first product.</p>
                                        <a href="{{ route('products.create') }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-green-500 transition">
                                            <i class="fas fa-plus"></i>
                                            Add New Product
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-gray-500">
                    Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                </p>
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('click', function(event) {
        const dropdownButtons = document.querySelectorAll('[id^="product-actions-menu-"]');

        dropdownButtons.forEach(button => {
            if (button.contains(event.target)) {
                const productId = button.id.replace('product-actions-menu-', '');
                const dropdown = document.getElementById('dropdown-menu-' + productId);
                dropdown.classList.toggle('hidden');
                event.stopPropagation();
            }
        });

        document.querySelectorAll('[id^="dropdown-menu-"]').forEach(dropdown => {
            if (!dropdown.classList.contains('hidden') && !dropdown.contains(event.target)) {
                let isClickOnButton = false;
                dropdownButtons.forEach(button => {
                    if (button.contains(event.target)) {
                        isClickOnButton = true;
                    }
                });

                if (!isClickOnButton) {
                    dropdown.classList.add('hidden');
                }
            }
        });
    });

    function changeProductStatus(productId, status) {
        const dropdown = document.getElementById('dropdown-menu-' + productId);
        if (dropdown) {
            dropdown.classList.add('hidden');
        }

        if (confirm('Are you sure you want to change the status of this product to ' + status + '?')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            fetch('/farmer/products/' + productId + '/status', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update status: ' + data.message);
                }
            })
            .catch(() => {
                alert('Failed to update status. Please try again.');
            });
        }
    }
</script>
@endsection
