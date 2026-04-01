{{-- Results Summary --}}
<div
    class="mb-4 text-sm text-gray-500 bg-white p-3 rounded-lg border border-gray-100 shadow-sm flex items-center justify-between">
    <div>
        Showing <span class="font-bold text-gray-900">{{ $products->firstItem() ?? 0 }}</span>
        to <span class="font-bold text-gray-900">{{ $products->lastItem() ?? 0 }}</span>
        of <span class="font-bold text-gray-900">{{ $products->total() }}</span> products
    </div>
    @if($hasFilters)
        <div class="text-[10px] font-bold uppercase tracking-widest text-green-600 bg-green-50 px-2 py-1 rounded">
            Filtered View
        </div>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    @forelse($products as $product)
        @php
            $remainingQuantity = $product->remainingInventory?->remaining_quantity ?? $product->quantity;
            $remainingAmount = $product->remainingInventory?->remaining_price ?? ($product->total_amount ?? $product->quantity * $product->price);
        @endphp
        <div
            class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 group hover:shadow-md transition-shadow duration-200">
            <span
                class="px-2 py-1 text-[10px] rounded-full font-bold absolute m-4 uppercase z-10
                    {{ $product->status == 'Available' ? 'bg-green-100 text-green-700 border border-green-200' :
            ($product->status == 'Sold Out' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-yellow-100 text-yellow-700 border border-yellow-200') }}">
                {{ $product->status }}
            </span>
            <div class="h-48 bg-gray-100 relative overflow-hidden">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div
                        class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
                        <i class="fas fa-seedling text-4xl text-green-200 mb-2"></i>
                        <p class="text-green-600 text-[10px] font-bold uppercase tracking-wider">No Image</p>
                    </div>
                @endif
            </div>

            <div class="p-5 space-y-4">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 truncate" title="{{ $product->product_name }}">
                                {{ $product->product_name }}</h3>
                            <p class="text-xs text-gray-500 truncate mt-1">
                                {{ $product->variety_size ?: 'No variety/size specified' }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-bold text-green-600 text-lg leading-none">
                                ₱{{ number_format($product->price, 2) }}
                            </p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">PER {{ $product->unit }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 border-t border-gray-50 pt-3">
                    <div class="flex items-center text-xs text-gray-600 gap-2">
                        <span
                            class="w-6 h-6 flex items-center justify-center bg-green-50 text-green-600 rounded-md shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <span class="truncate">
                            {{ collect([$product->barangay, $product->municipality_city, $product->province])->filter()->implode(', ') ?: 'Location not provided' }}
                        </span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 gap-2">
                        <span
                            class="w-6 h-6 flex items-center justify-center bg-yellow-50 text-yellow-600 rounded-md shrink-0">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                        <span>Harvest: {{ optional($product->harvest_date)->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>

                <a href="{{ route('buyer.products.show', $product) }}"
                    class="block w-full rounded-lg bg-green-600 px-4 py-2.5 text-center text-sm font-bold text-white hover:bg-green-700 shadow-md shadow-green-100 transition-all duration-200 active:scale-95">
                    View Details
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
            <div
                class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                <i class="fas fa-search text-3xl text-gray-200"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">No products found</h3>
            <p class="text-gray-500 mt-2 max-w-sm mx-auto">We couldn't find any listings matching your current filters. Try
                adjusting your search terms or clearing some filters.</p>
            @if ($hasFilters)
                <button type="button" onclick="window.location.href='{{ route('buyer.dashboard') }}'"
                    class="mt-6 inline-flex items-center gap-2 rounded-lg bg-gray-100 text-gray-700 px-6 py-2.5 text-sm font-bold hover:bg-gray-200 transition-colors">
                    <i class="fas fa-undo"></i> Clear All Filters
                </button>
            @endif
        </div>
    @endforelse
</div>

@if ($products->hasPages())
    <div class="mt-10 px-4 py-4 bg-gray-50 rounded-xl border border-gray-100 flex justify-center ajax-pagination">
        {{ $products->links() }}
    </div>
@endif