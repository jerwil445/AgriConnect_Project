@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Product Details</h2>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Back to Products
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Product Information</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Product Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->product_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Variety/Size</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->variety_size ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->description ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Quantity</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->quantity }} {{ $product->unit }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Price per Unit</label>
                            <p class="mt-1 text-sm text-gray-900">₱{{ number_format($product->price, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Total Amount</label>
                            <p class="mt-1 text-sm text-gray-900">₱{{ number_format($product->total_amount ?? ($product->quantity * $product->price), 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->status }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Farmer Information</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Farmer Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->farmer->user->first_name }} {{ $product->farmer->user->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->farmer->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->farmer->user->phone_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Farm Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->farmer->farm_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Farm Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->farmer->farm_address ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Harvest Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ optional($product->harvest_date)->format('F d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Posted On</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('F d, Y g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Remaining Quantity</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->remainingInventory?->remaining_quantity ?? $product->quantity }} {{ $product->unit }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($product->images->count() > 0)
                    <div class="mt-6 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Images</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->product_name }}"
                                    class="h-24 w-24 object-cover rounded-md border border-gray-200">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('admin.products.edit', $product) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                        Edit Product
                    </a>
                    <form action="{{ route('admin.products.delete', $product) }}" method="POST" class="inline delete-form" data-product-name="{{ $product->product_name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                            Delete Product
                        </button>
                    </form>
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
</script>
@endsection
