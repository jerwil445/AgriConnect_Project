@extends('layouts.farmers_page')

@section('title', 'Product Listings • AgriConnect')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-64 relative">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">My Product Listings</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your agricultural products</p>
        </div>
        <a href="{{ route('products.create') }}" 
           class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-green-500 transition">
            <i class="fas fa-plus"></i>
            Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="relative">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harvest Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $product->quantity }} {{ $product->unit }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ₱{{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($product->harvest_date)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($product->status == 'Available') bg-green-100 text-green-800
                            @elseif($product->status == 'Sold Out') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                        <div class="relative inline-block text-left">
                            <button type="button" 
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                    id="product-actions-menu-{{ $product->id }}">
                                Actions
                                <i class="fas fa-chevron-down ml-2"></i>
                            </button>

                            <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10"
                                 id="dropdown-menu-{{ $product->id }}">
                                <div class="py-1" role="menu">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fas fa-eye mr-2"></i> View
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fas fa-edit mr-2"></i> Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                                role="menuitem">
                                            <i class="fas fa-trash mr-2"></i> Delete
                                        </button>
                                    </form>
                                    <div class="border-t border-gray-100"></div>
                                    <div class="py-1">
                                        <a href="#" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                           role="menuitem" 
                                           onclick="event.preventDefault(); changeProductStatus({{ $product->id }}, 'Available');">
                                            <i class="fas fa-check-circle mr-2 text-green-500"></i> Mark as Available
                                        </a>
                                        <a href="#" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                           role="menuitem" 
                                           onclick="event.preventDefault(); changeProductStatus({{ $product->id }}, 'Pending');">
                                            <i class="fas fa-clock mr-2 text-yellow-500"></i> Mark as Pending
                                        </a>
                                        <a href="#" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                           role="menuitem" 
                                           onclick="event.preventDefault(); changeProductStatus({{ $product->id }}, 'Sold Out');">
                                            <i class="fas fa-times-circle mr-2 text-red-500"></i> Mark as Sold Out
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center justify-center py-12">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No products found</h3>
                            <p class="text-gray-500 mb-4">Get started by adding your first product.</p>
                            <a href="{{ route('products.create') }}" 
                               class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-green-500 transition">
                                <i class="fas fa-plus"></i>
                                Add New Product
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>

<script>
    // Toggle dropdown visibility
    document.addEventListener('click', function(event) {
        // Handle dropdown toggles
        const dropdownButtons = document.querySelectorAll('[id^="product-actions-menu-"]');
        dropdownButtons.forEach(button => {
            if (button.contains(event.target)) {
                const productId = button.id.replace('product-actions-menu-', '');
                const dropdown = document.getElementById('dropdown-menu-' + productId);
                dropdown.classList.toggle('hidden');
                event.stopPropagation();
            }
        });

        // Close all dropdowns when clicking outside
        const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
        dropdowns.forEach(dropdown => {
            if (!dropdown.classList.contains('hidden') && !dropdown.contains(event.target)) {
                // Check if click was on a dropdown button
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

    // Change product status
    function changeProductStatus(productId, status) {
        // Close the dropdown
        const dropdown = document.getElementById('dropdown-menu-' + productId);
        if (dropdown) {
            dropdown.classList.add('hidden');
        }
        
        // Show confirmation
        if (confirm('Are you sure you want to change the status of this product to ' + status + '?')) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            // Make AJAX request
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
                    // Reload the page to show updated status
                    location.reload();
                } else {
                    alert('Failed to update status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status. Please try again.');
            });
        }
    }
</script>
@endsection