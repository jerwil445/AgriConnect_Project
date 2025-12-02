@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Transaction Details</h2>
                    <a href="{{ route('admin.transactions.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Transactions
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transaction Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaction Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID:</span>
                                <span class="font-medium">{{ $transaction->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created At:</span>
                                <span class="font-medium">{{ $transaction->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated At:</span>
                                <span class="font-medium">{{ $transaction->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($transaction->payment_status == 'Paid') bg-green-100 text-green-800
                                        @elseif($transaction->payment_status == 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($transaction->payment_status == 'Failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($transaction->payment_status ?? 'N/A') }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Status:</span>
                                <span class="font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($transaction->delivery_status == 'Delivered') bg-green-100 text-green-800
                                        @elseif($transaction->delivery_status == 'Scheduled') bg-blue-100 text-blue-800
                                        @elseif($transaction->delivery_status == 'In Transit') bg-purple-100 text-purple-800
                                        @elseif($transaction->delivery_status == 'Cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($transaction->delivery_status ?? 'N/A') }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Final Quantity:</span>
                                <span class="font-medium">{{ $transaction->final_quantity ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Final Price:</span>
                                <span class="font-medium">₱{{ number_format($transaction->final_price ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="font-medium">₱{{ number_format($transaction->total_amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Information</h3>
                        @if($transaction->product)
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Egg Type:</span>
                                <span class="font-medium">{{ $transaction->product->egg_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Category:</span>
                                <span class="font-medium">{{ $transaction->product->category ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Unit:</span>
                                <span class="font-medium">{{ $transaction->product->unit ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Price:</span>
                                <span class="font-medium">₱{{ number_format($transaction->product->price ?? 0, 2) }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-600">No product information available</p>
                        @endif
                    </div>

                    <!-- Demand Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Demand Information</h3>
                        @if($transaction->demand)
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Demand Name:</span>
                                <span class="font-medium">{{ $transaction->demand->egg_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-medium">{{ $transaction->demand->quantity }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Target Price:</span>
                                <span class="font-medium">₱{{ number_format($transaction->demand->target_price ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Location:</span>
                                <span class="font-medium">{{ $transaction->demand->location }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-600">No demand information available</p>
                        @endif
                    </div>

                    <!-- Buyer Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Buyer Information</h3>
                        @if($transaction->buyer)
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $transaction->buyer->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $transaction->buyer->phone_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Address:</span>
                                <span class="font-medium">{{ $transaction->buyer->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-600">No buyer information available</p>
                        @endif
                    </div>

                    <!-- Farmer Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Farmer Information</h3>
                        @if($transaction->farmer)
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $transaction->farmer->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $transaction->farmer->phone_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Address:</span>
                                <span class="font-medium">{{ $transaction->farmer->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-600">No farmer information available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection