@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Audit Matches for "{{ $demand->egg_type }}"</h2>
                    <a href="{{ route('admin.demands.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Demands
                    </a>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Demand Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Product</label>
                            <p class="text-sm text-gray-900">{{ $demand->egg_type }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Quantity</label>
                            <p class="text-sm text-gray-900">{{ $demand->quantity }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Target Price</label>
                            <p class="text-sm text-gray-900">₱{{ number_format($demand->target_price ?? 0, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <label class="block text-sm font-medium text-gray-600">Buyer</label>
                            <p class="text-sm text-gray-900">{{ $demand->buyer->first_name }} {{ $demand->buyer->last_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Match ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matched Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($demand->matches as $match)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $match->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $match->product->egg_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $match->product->quantity }} {{ $match->product->unit }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱{{ number_format($match->product->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($match->status == 'Accepted') bg-green-100 text-green-800
                                        @elseif($match->status == 'Rejected') bg-red-100 text-red-800
                                        @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                        @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $match->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $match->matched_date ? $match->matched_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($match->status == 'Pending')
                                            <form action="{{ route('admin.matches.accept', $match) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.matches.reject', $match) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Reject
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.matches.view', $match) }}" class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No matches found for this demand.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection