@extends('layouts.buyers_page')

@section('content')
@php
    $eggTypes = [
        'chicken' => 'Chicken Eggs',
        'duck' => 'Duck Eggs',
        'quail' => 'Quail Eggs',
        'native_chicken' => 'Native Chicken Eggs',
        'brown' => 'Brown Eggs',
        'white' => 'White Eggs',
    ];
@endphp

<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Demands</h1>
        <button id="openDemandModal" type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Post New Demand
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($demands->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-gray-600">You haven't posted any demands yet.</p>
            <button id="openDemandModalEmpty" type="button" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Post Your First Demand
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($demands as $demand)
                @php
                    $demandName = $demand->product_name ?: ($eggTypes[$demand->egg_type] ?? ucfirst(str_replace('_', ' ', $demand->egg_type ?? 'product demand')));
                    $demandVariety = $demand->egg_size;
                @endphp
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $demandName }}</h2>
                                @if($demandVariety)
                                    <p class="text-sm text-gray-500 mt-1">Variety/Size: {{ $demandVariety }}</p>
                                @endif
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $demand->quantity }} units
                            </span>
                        </div>

                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            @if($demand->purok_street || $demand->barangay || $demand->municipality_city || $demand->province)
                                <div>
                                    <span class="font-medium text-gray-700">Address:</span>
                                    {{ trim(implode(', ', array_filter([
                                        $demand->purok_street,
                                        $demand->barangay,
                                        $demand->municipality_city,
                                        $demand->province,
                                    ])), ', ') }}
                                </div>
                            @endif

                            <div>
                                <span class="font-medium text-gray-700">Delivery Date:</span>
                                {{ $demand->delivery_date->format('M d, Y') }}
                            </div>

                            <div>
                                <span class="font-medium text-gray-700">Matches Found:</span>
                                {{ $demand->matches->count() }}
                            </div>
                        </div>

                        @if($demand->matches->isNotEmpty())
                            <div class="mt-4 space-y-2">
                                @foreach($demand->matches->take(3) as $match)
                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                        <div>
                                            <p class="text-sm font-medium">{{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $match->product->product_name ?: 'Product' }}
                                                @if($match->product->variety_size)
                                                    • {{ $match->product->variety_size }}
                                                @endif
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded
                                            @if($match->status == 'Matched') bg-green-100 text-green-800
                                            @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($match->status == 'New') bg-blue-100 text-blue-800
                                            @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                            @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $match->status }}
                                        </span>
                                    </div>
                                @endforeach

                                @if($demand->matches->count() > 3)
                                    <p class="text-sm text-gray-500 text-center">+{{ $demand->matches->count() - 3 }} more matches</p>
                                @endif
                            </div>
                        @endif

                        <div class="mt-6 flex space-x-2">
                            <a href="{{ route('demands.show', $demand) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                View Details
                            </a>
                            <form action="{{ route('demands.destroy', $demand) }}" method="POST" class="delete-demand-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 border border-transparent rounded-md text-white bg-red-200 hover:bg-red-300" title="Delete Demand">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="demandModal" class="fixed inset-0 bg-gray-600 bg-opacity-70 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Post New Demand</h3>
                <p class="text-sm text-gray-500 mt-1">Tell farmers exactly what product you need.</p>
            </div>
            <button id="closeModal" type="button" class="text-gray-400 hover:text-gray-500 bg-transparent hover:bg-gray-200 rounded-full p-1 transition duration-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('demands.store') }}" method="POST" class="space-y-6">
            @csrf

            @if($errors->any())
                <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Please review the demand form fields and try again.
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="modal_product_name" class="block text-gray-700 font-medium mb-2">Product Name</label>
                        <input type="text" name="product_name" id="modal_product_name"
                               value="{{ old('product_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Banana, Chicken Eggs, Tomatoes, Rice, etc." required>
                        @error('product_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="modal_variety_size" class="block text-gray-700 font-medium mb-2">Variety/Size</label>
                        <input type="text" name="variety_size" id="modal_variety_size"
                               value="{{ old('variety_size') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Lakatan, Large, Grade A, Bundle, etc.">
                        @error('variety_size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="modal_quantity" class="block text-gray-700 font-medium mb-2">Quantity</label>
                        <input type="number" name="quantity" id="modal_quantity"
                               value="{{ old('quantity') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                               min="1" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="modal_delivery_date" class="block text-gray-700 font-medium mb-2">Delivery Date</label>
                        <input type="date" name="delivery_date" id="modal_delivery_date"
                               value="{{ old('delivery_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                               required min="{{ date('Y-m-d') }}">
                        @error('delivery_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Delivery Address</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="modal_purok_street" class="block text-xs text-gray-500 mb-1">Purok/Street</label>
                                <input type="text" name="purok_street" id="modal_purok_street"
                                       value="{{ old('purok_street') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                       placeholder="Enter purok or street">
                                @error('purok_street')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modal_barangay" class="block text-xs text-gray-500 mb-1">Barangay</label>
                                <input type="text" name="barangay" id="modal_barangay"
                                       value="{{ old('barangay') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                       placeholder="Enter barangay">
                                @error('barangay')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modal_municipality_city" class="block text-xs text-gray-500 mb-1">Municipality/City</label>
                                <input type="text" name="municipality_city" id="modal_municipality_city"
                                       value="{{ old('municipality_city') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                       placeholder="Enter municipality or city">
                                @error('municipality_city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modal_province" class="block text-xs text-gray-500 mb-1">Province</label>
                                <input type="text" name="province" id="modal_province"
                                       value="{{ old('province') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                       placeholder="Enter province">
                                @error('province')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <p class="text-sm text-gray-700 font-medium">Tip</p>
                        <p class="mt-1 text-sm text-gray-600">Use the same product name and variety/size that farmers list so matching is more accurate.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t pt-4">
                <button id="cancelModal" type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Post Demand
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('demandModal');
        const openButtons = [
            document.getElementById('openDemandModal'),
            document.getElementById('openDemandModalEmpty'),
        ].filter(Boolean);
        const closeModalBtn = document.getElementById('closeModal');
        const cancelModalBtn = document.getElementById('cancelModal');

        function openModal() {
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        openButtons.forEach((button) => {
            button.addEventListener('click', openModal);
        });

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        if (cancelModalBtn) {
            cancelModalBtn.addEventListener('click', closeModal);
        }

        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        document.querySelectorAll('.delete-demand-form').forEach((form) => {
            form.addEventListener('submit', function(event) {
                if (!confirm('Are you sure you want to delete this demand?')) {
                    event.preventDefault();
                }
            });
        });

        @if($errors->any())
            openModal();
        @endif
    });
</script>
@endsection
