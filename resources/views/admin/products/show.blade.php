@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20n">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Product Details</h2>
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Products
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Egg Type</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->egg_type }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->description ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Category</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->category ?? 'N/A' }}</p>
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
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <p class="mt-1 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($product->status == 'available') bg-green-100 text-green-800
                                        @elseif($product->status == 'sold') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Farmer Information</h3>
                        
                        <div class="space-y-4">
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
                                <label class="block text-sm font-medium text-gray-600">Farm Size</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->farmer->farm_size ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Harvest Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->harvest_date ? $product->harvest_date->format('F d, Y') : 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Posted On</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('F d, Y g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('F d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($product->sizes->count() > 0)
                <div class="mt-6 bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Egg Sizes</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Size</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Trays</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Price per Tray</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total Price</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($product->sizes as $size)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $size->size_name)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $size->tray_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($size->price_per_tray, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($size->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                
                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Product
                    </a>
                    
                    <form action="{{ route('admin.products.delete', $product) }}" method="POST" 
                          class="inline delete-form" data-product-name="{{ $product->egg_type }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Confirm before deleting
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