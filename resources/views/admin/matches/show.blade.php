@extends('layouts.admin_page')

@section('content')
<div class="md:ml-64 mt-16 flex flex-col min-h-screen">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Match Details</h2>
                    <a href="javascript:history.back()" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Match Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Match ID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->id }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <p class="mt-1 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($match->status == 'Accepted') bg-green-100 text-green-800
                                        @elseif($match->status == 'Rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $match->status }}
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Matched Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->matched_date ? $match->matched_date->format('F d, Y g:i A') : 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Created At</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->created_at->format('F d, Y g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->updated_at->format('F d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Product Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->product->product_name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Quantity</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->product->quantity }} {{ $match->product->unit }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Price</label>
                                <p class="mt-1 text-sm text-gray-900">₱{{ number_format($match->product->price, 2) }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Harvest Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->product->harvest_date ? $match->product->harvest_date->format('F d, Y') : 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <p class="mt-1 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($match->product->status == 'available') bg-green-100 text-green-800
                                        @elseif($match->product->status == 'sold') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($match->product->status) }}
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
                                <p class="mt-1 text-sm text-gray-900">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->product->farmer->user->email }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Phone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->product->farmer->user->phone_number ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Farm Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->product->farmer->farm_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Buyer Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Buyer Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->demand->buyer->first_name }} {{ $match->demand->buyer->last_name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->demand->buyer->email }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Phone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->demand->buyer->phone_number ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Company Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $match->demand->buyer->buyer->company_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demand Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Product</label>
                            <p class="text-sm text-gray-900">{{ $match->demand->product_name }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Quantity</label>
                            <p class="text-sm text-gray-900">{{ $match->demand->quantity }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Target Price</label>
                            <p class="text-sm text-gray-900">₱{{ number_format($match->demand->target_price ?? 0, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Location</label>
                            <p class="text-sm text-gray-900">{{ $match->demand->location }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection