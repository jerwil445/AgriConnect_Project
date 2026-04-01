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

    <div class="min-h-screen ">
        <div class="container mx-auto px-4 py-10 max-w-6xl">

            <!-- Page Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-green-500 mb-1">Buyer Portal</p>
                    <h1 class="text-3xl font-extrabold text-gray-900">My Demands</h1>
                </div>
                <button id="openDemandModal" type="button"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 shadow-lg shadow-green-600/25 hover:shadow-xl hover:shadow-green-600/35 hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-plus text-xs"></i>
                    Post New Demand
                </button>
            </div>

            <!-- Success Alert -->
            @if (session('success'))
                <div
                    class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6 shadow-sm">
                    <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Empty State -->
            @if ($demands->isEmpty())
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-16 text-center">
                    <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-clipboard-list text-green-400 text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-800 mb-2">No Demands Yet</h2>
                    <p class="text-gray-400 text-sm mb-6 max-w-xs mx-auto">Post your first demand and let farmers come to
                        you with matching products.</p>
                    <button id="openDemandModalEmpty" type="button"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 shadow-lg shadow-green-600/25 hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus text-xs"></i> Post Your First Demand
                    </button>
                </div>
            @else
                <!-- Demand Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($demands as $demand)
                        @php
                            $demandName =
                                $demand->product_name ?:
                                $eggTypes[$demand->egg_type] ??
                                    ucfirst(str_replace('_', ' ', $demand->egg_type ?? 'product demand'));
                            $demandVariety = $demand->variety_size ?: $demand->egg_size;
                            $sizeOptions = ['Small', 'Medium', 'Large', 'Extra-Large', 'Jumbo'];
                            $label = $demandVariety && in_array($demandVariety, $sizeOptions) ? 'Size' : 'Variety';
                            $matchCount = $demand->matches->count();
                        @endphp

                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden">

                            <!-- Card Top accent -->
                            <div class="h-1 w-full bg-gradient-to-r from-green-400 to-emerald-500"></div>

                            <div class="p-5 flex flex-col flex-1 gap-4">

                                <!-- Title + Quantity -->
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h2 class="text-lg font-extrabold text-gray-900 leading-tight">{{ $demandName }}
                                        </h2>
                                        @if ($demandVariety)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $label }}: <span
                                                    class="font-semibold text-gray-600">{{ $demandVariety }}</span></p>
                                        @endif
                                    </div>
                                    <span
                                        class="shrink-0 inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-100 text-xs font-bold px-3 py-1.5 rounded-xl">
                                        <i class="fas fa-cubes text-[10px]"></i>
                                        {{ $demand->quantity }} {{ $demand->unit ?? 'units' }}
                                    </span>
                                </div>

                                <!-- Info Rows -->
                                <div class="space-y-2">
                                    @if ($demand->purok_street || $demand->barangay || $demand->municipality_city || $demand->province)
                                        <div class="flex items-start gap-2.5">
                                            <div
                                                class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                                <i class="fas fa-map-marker-alt text-gray-400 text-[10px]"></i>
                                            </div>
                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                {{ trim(implode(', ', array_filter([$demand->purok_street, $demand->barangay, $demand->municipality_city, $demand->province])), ', ') }}
                                            </p>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-6 h-6 bg-amber-50 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-calendar-alt text-amber-400 text-[10px]"></i>
                                        </div>
                                        <p class="text-xs text-gray-600">
                                            Delivery: <span
                                                class="font-semibold text-gray-800">{{ $demand->delivery_date->format('M d, Y') }}</span>
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                        {{ $matchCount > 0 ? 'bg-green-50' : 'bg-gray-100' }}">
                                            <i
                                                class="fas fa-link text-[10px] {{ $matchCount > 0 ? 'text-green-500' : 'text-gray-400' }}"></i>
                                        </div>
                                        <p class="text-xs text-gray-600">
                                            Matches: <span
                                                class="font-bold {{ $matchCount > 0 ? 'text-green-600' : 'text-gray-500' }}">{{ $matchCount }}
                                                found</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Match Previews -->
                                @if ($demand->matches->isNotEmpty())
                                    <div class="space-y-1.5">
                                        @foreach ($demand->matches->take(3) as $match)
                                            <div
                                                class="flex items-center justify-between bg-gray-50 rounded-xl px-3 py-2 border border-gray-100">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <div
                                                        class="w-7 h-7 bg-green-200 border border-green-400 rounded-lg flex items-center justify-center shrink-0">
                                                        <i class="fas fa-user text-green-600 text-[10px]"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-gray-800 truncate">
                                                            {{ $match->product->farmer->user->first_name }}
                                                            {{ $match->product->farmer->user->last_name }}
                                                        </p>
                                                        <p class="text-[10px] text-gray-400 truncate">
                                                            {{ $match->product->product_name ?: 'Product' }}
                                                            @if ($match->product->variety_size)
                                                                · {{ $match->product->variety_size }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <span
                                                    class="shrink-0 ml-2 px-2 py-0.5 text-[10px] font-bold rounded-lg
                                                @if ($match->status == 'Matched') bg-green-100 text-green-700
                                                @elseif($match->status == 'Pending') bg-amber-100 text-amber-700
                                                @elseif($match->status == 'New') bg-blue-100 text-blue-700
                                                @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-700
                                                @elseif($match->status == 'Ordered') bg-purple-100 text-purple-700
                                                @else bg-red-100 text-red-700 @endif">
                                                    {{ $match->status }}
                                                </span>
                                            </div>
                                        @endforeach

                                        @if ($demand->matches->count() > 3)
                                            <p class="text-[10px] text-gray-400 text-center font-medium pt-0.5">
                                                +{{ $demand->matches->count() - 3 }} more matches
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <!-- Card Actions -->
                                <div class="mt-auto flex gap-2 pt-2">
                                    <a href="{{ route('demands.show', $demand) }}"
                                        class="flex-1 inline-flex justify-center items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-400 hover:to-indigo-500 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                                        <i class="fas fa-eye text-xs"></i> View Details
                                    </a>
                                    <form action="{{ route('demands.destroy', $demand) }}" method="POST"
                                        class="delete-demand-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Demand"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 border border-red-100 text-red-400 hover:bg-red-100 hover:text-red-600 hover:border-red-200 transition-all duration-200">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- ══ DEMAND MODAL ══ -->
    <div id="demandModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60" id="modalBackdrop"></div>

        <!-- Modal Panel -->
        <div class="fixed inset-0 flex items-start justify-center p-4 overflow-y-auto">
            <div class="relative w-full max-w-4xl my-8 bg-white rounded-3xl shadow-2xl border border-gray-100">

                <!-- Modal top accent -->
                <div class="h-1.5 w-full bg-gradient-to-r from-green-400 via-emerald-500 to-teal-400 rounded-t-3xl"></div>

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-8 pt-6 pb-4 border-b border-gray-100">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-green-500 mb-0.5">New Request</p>
                        <h3 class="text-xl font-extrabold text-gray-900">Post a Demand</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Tell farmers exactly what product you need.</p>
                    </div>
                    <button id="closeModal" type="button"
                        class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-times text-gray-500 text-sm"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form action="{{ route('demands.store') }}" method="POST" class="px-8 py-6">
                    @csrf

                    @if ($errors->any())
                        <div
                            class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                            <span class="font-semibold">Please review the fields below and try again.</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <!-- Left Column -->
                        <div class="space-y-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Product Details</p>

                            <!-- Product Name -->
                            <div>
                                <label for="modal_product_name" class="block text-sm font-bold text-gray-700 mb-1.5">
                                    Product Name <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="product_name" id="modal_product_name"
                                    value="{{ old('product_name') }}" placeholder="Banana, Tomatoes, Rice, etc."
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                                    required>
                                @error('product_name')
                                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Variety/Size -->
                            <div>
                                <label for="modal_variety_size"
                                    class="block text-sm font-bold text-gray-700 mb-1.5">Variety / Size</label>
                                <input type="text" name="variety_size" id="modal_variety_size"
                                    value="{{ old('variety_size') }}" placeholder="Lakatan, Large, Grade A, etc."
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all">
                                @error('variety_size')
                                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity + Unit side by side -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="modal_quantity" class="block text-sm font-bold text-gray-700 mb-1.5">
                                        Quantity <span class="text-red-400">*</span>
                                    </label>
                                    <input type="number" name="quantity" id="modal_quantity"
                                        value="{{ old('quantity') }}" min="1"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                                        required>
                                    @error('quantity')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="modal_unit"
                                        class="block text-sm font-bold text-gray-700 mb-1.5">Unit</label>
                                    <select name="unit" id="modal_unit"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all bg-white">
                                        <option value="">Select</option>
                                        <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces
                                        </option>
                                        <option value="trays" {{ old('unit') == 'trays' ? 'selected' : '' }}>Trays
                                        </option>
                                        <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen
                                        </option>
                                        <option value="kilos" {{ old('unit') == 'kilos' ? 'selected' : '' }}>Kilos
                                        </option>
                                        <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>Boxes
                                        </option>
                                        <option value="bunches" {{ old('unit') == 'bunches' ? 'selected' : '' }}>Bunches
                                        </option>
                                        <option value="sacks" {{ old('unit') == 'sacks' ? 'selected' : '' }}>Sacks
                                        </option>
                                    </select>
                                    @error('unit')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Delivery Date + Deadline side by side -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="modal_delivery_date" class="block text-sm font-bold text-gray-700 mb-1.5">
                                        Delivery Date <span class="text-red-400">*</span>
                                    </label>
                                    <input type="date" name="delivery_date" id="modal_delivery_date"
                                        value="{{ old('delivery_date') }}" min="{{ date('Y-m-d') }}"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all"
                                        required>
                                    @error('delivery_date')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="modal_deadline"
                                        class="block text-sm font-bold text-gray-700 mb-1.5">Deadline</label>
                                    <input type="date" name="deadline" id="modal_deadline"
                                        value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all">
                                    @error('deadline')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Delivery Address</p>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="modal_purok_street"
                                        class="block text-sm font-bold text-gray-700 mb-1.5">Purok / Street</label>
                                    <input type="text" name="purok_street" id="modal_purok_street"
                                        value="{{ old('purok_street') }}" placeholder="Purok 3 / Rizal St."
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all">
                                    @error('purok_street')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="modal_barangay"
                                        class="block text-sm font-bold text-gray-700 mb-1.5">Barangay</label>
                                    <input type="text" name="barangay" id="modal_barangay"
                                        value="{{ old('barangay') }}" placeholder="Brgy. San Jose"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all">
                                    @error('barangay')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="modal_municipality_city"
                                        class="block text-sm font-bold text-gray-700 mb-1.5">Municipality / City</label>
                                    <input type="text" name="municipality_city" id="modal_municipality_city"
                                        value="{{ old('municipality_city') }}" placeholder="Cagayan de Oro"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all">
                                    @error('municipality_city')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="modal_province"
                                        class="block text-sm font-bold text-gray-700 mb-1.5">Province</label>
                                    <input type="text" name="province" id="modal_province"
                                        value="{{ old('province') }}" placeholder="Misamis Oriental"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all">
                                    @error('province')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tip Box -->
                            <div class="mt-2 flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-2xl p-4">
                                <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fas fa-lightbulb text-amber-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-amber-700">Tip for better matching</p>
                                    <p class="text-xs text-amber-600 mt-0.5 leading-relaxed">Use the same product name and
                                        variety/size that farmers list so the system can find accurate matches for you.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 mt-6">
                        <button id="cancelModal" type="button"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 shadow-md shadow-green-600/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-paper-plane text-xs"></i> Post Demand
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('demandModal');
            const backdrop = document.getElementById('modalBackdrop');
            const openBtns = [
                document.getElementById('openDemandModal'),
                document.getElementById('openDemandModalEmpty'),
            ].filter(Boolean);

            function openModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            document.getElementById('closeModal')?.addEventListener('click', closeModal);
            document.getElementById('cancelModal')?.addEventListener('click', closeModal);
            backdrop?.addEventListener('click', closeModal);

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });

            document.querySelectorAll('.delete-demand-form').forEach(form => {
                form.addEventListener('submit', e => {
                    if (!confirm('Are you sure you want to delete this demand?')) e
                        .preventDefault();
                });
            });

            @if ($errors->any())
                openModal();
            @endif
        });
    </script>

@endsection
