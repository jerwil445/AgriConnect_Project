@extends('layouts.farmers_page')

@section('title', 'Product Orders • AgriConnect')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 relative ml-64 overflow-hidden">
        {{-- Header Section --}}
        <div class="px-6 py-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('farmer.products.index') }}" 
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Order History</h2>
                    <p class="text-sm text-gray-500 mt-1">Showing all buyers for 
                        <span class="font-semibold text-green-600">{{ $product->product_name }}</span>
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-semibold border border-green-100">
                    <i class="fas fa-box-open mr-2"></i> {{ $product->quantity }} {{ $product->unit }} Total
                </span>
                <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold border border-blue-100">
                    <i class="fas fa-tag mr-2"></i> ₱{{ number_format($product->price, 2) }}/{{ $product->unit }}
                </span>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Buyer Details</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Order Info</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Amount</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-green-600 shadow-sm border border-green-50 overflow-hidden shrink-0">
                                        @if($order->buyer && $order->buyer->profile_photo_path)
                                            <img src="{{ Storage::url($order->buyer->profile_photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-xl"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">
                                            {{ $order->buyer_name ?? ($order->buyer ? $order->buyer->first_name . ' ' . $order->buyer->last_name : 'Unknown Buyer') }}
                                        </p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                                <i class="far fa-envelope"></i> {{ $order->buyer_email ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-400 flex items-center gap-1 border-l border-gray-200 pl-3">
                                                <i class="fas fa-phone-alt scale-75"></i> {{ $order->buyer_phone ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ $order->final_quantity }} {{ $product->unit }}
                                </p>
                                <p class="text-[10px] uppercase font-bold text-gray-400 mt-1 tracking-wider">
                                    {{ $order->created_at->format('M d, Y • h:i A') }}
                                </p>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <p class="text-sm font-bold text-gray-900">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium">
                                    Total Payment
                                </p>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($order->payment_status == 'Paid') bg-green-100 text-green-700 border border-green-200
                                        @elseif($order->payment_status == 'Unpaid') bg-red-100 text-red-700 border border-red-200
                                        @else bg-yellow-100 text-yellow-700 border border-yellow-200 @endif">
                                        {{ $order->payment_status }}
                                    </span>
                                    <span class="text-[10px] font-medium text-gray-400">
                                        {{ $order->delivery_status }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-right">
                                <a href="{{ route('orders.show', $order) }}" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg hover:bg-green-600 hover:text-white transition-all duration-200">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                        <i class="fas fa-shopping-cart text-2xl text-gray-200"></i>
                                    </div>
                                    <h3 class="text-gray-900 font-bold">No sales yet</h3>
                                    <p class="text-sm text-gray-400 mt-1">This product hasn't been purchased by any buyers yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="px-6 py-6 border-t border-gray-100 bg-gray-50 flex items-center justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
