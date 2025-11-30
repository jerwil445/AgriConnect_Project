@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Orders</h1>
        
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                    @php
                                                                        $eggTypes = [
                                                                            'chicken' => 'Chicken',
                                                                            'duck' => 'Duck',
                                                                            'quail' => 'Quail',
                                                                            'native_chicken' => 'Native Chicken',
                                                                            'brown' => 'Brown Egg',
                                                                            'white' => 'White Egg'
                                                                        ];
                                                                    @endphp
                                                                    {{ $eggTypes[$order->product->egg_type] ?? ucfirst(str_replace('_', ' ', $order->product->egg_type)) }}
                                                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->final_quantity }} {{ $order->product->unit }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No orders yet</h3>
                <p class="text-gray-500">You haven't placed any orders yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection