@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Demand Details</h2>
                    <a href="{{ route('admin.demands.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Demands
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Demand Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Demand ID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->id }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Egg Type</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->egg_type }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Quantity</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->quantity }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Target Price</label>
                                <p class="mt-1 text-sm text-gray-900">₱{{ number_format($demand->target_price ?? 0, 2) }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Location</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->location }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Delivery Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->delivery_date ? $demand->delivery_date->format('F d, Y') : 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <p class="mt-1 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($demand->status == 'matched') bg-green-100 text-green-800
                                        @elseif($demand->status == 'unmatched') bg-yellow-100 text-yellow-800
                                        @elseif($demand->status == 'in negotiation') bg-blue-100 text-blue-800
                                        @elseif($demand->status == 'completed') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($demand->status ?? 'unmatched') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Buyer Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Buyer Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->buyer->first_name }} {{ $demand->buyer->last_name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->buyer->email }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Phone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->buyer->phone_number ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Company Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->buyer->buyer->company_name ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Business Type</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->buyer->buyer->business_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Match Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Total Matches</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->matches->count() }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Posted On</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->created_at->format('F d, Y g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $demand->updated_at->format('F d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('admin.demands.edit', $demand) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Demand
                    </a>
                    
                    <a href="{{ route('admin.demands.audit', $demand) }}" 
                       class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Audit Matches
                    </a>
                    
                    <form action="{{ route('admin.demands.delete', $demand) }}" method="POST" 
                          class="inline delete-form" data-demand-name="{{ $demand->product_name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Delete Demand
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
            const demandName = this.getAttribute('data-demand-name');
            if (confirm(`Are you sure you want to delete demand for ${demandName}?`)) {
                this.submit();
            }
        });
    });
</script>
@endsection