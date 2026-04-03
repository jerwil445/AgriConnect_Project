@extends('layouts.farmers_page')

@section('content')

    <div class="min-h-screen bg-gray-50 py-10 ml-64">
        <div class="container mx-auto px-4 py-4 max-w-6xl">
            <div class="flex items-center justify-between text-center mb-8">

                <!-- Title & Context -->
                <div>
                    <h1
                        class="text-4xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent leading-tight text-left">
                        Manage Listing
                    </h1>
                    <p class="text-sm text-gray-400 font-medium text-left mt-1 tracking-wide uppercase">Product Intelligence
                        Dashboard</p>
                </div>

                <!-- Action Toolbar -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('farmer.products.edit', $product) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all duration-200 shadow-sm group">
                        <i class="fas fa-edit text-xs text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                        Edit Listing
                    </a>

                    <form action="{{ route('farmer.products.destroy', $product) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this listing?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-50 border border-red-100 text-sm font-bold text-red-600 hover:bg-red-100 hover:border-red-200 transition-all duration-200 shadow-sm group">
                            <i class="fas fa-trash-alt text-xs text-red-400 group-hover:text-red-500 transition-colors"></i>
                            Delete
                        </button>
                    </form>

                    <div class="h-8 w-px bg-gray-200 mx-1"></div>

                    <a href="{{ route('farmer.products.index') }}"
                        class="inline-flex items-center gap-2.5 text-sm font-bold text-gray-500 hover:text-green-700 transition-colors group">
                        <span
                            class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:border-green-400 group-hover:bg-green-50 transition-all duration-200">
                            <i class="fas fa-arrow-left text-xs text-gray-500 group-hover:text-green-600"></i>
                        </span>
                    </a>
                </div>

            </div>

            <!-- Main Listing Console -->
            <div
                class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/70 overflow-hidden border border-gray-100 relative">

                {{-- Top accent bar --}}
                <div class="h-2 w-full bg-gradient-to-r from-green-400 via-emerald-500 to-teal-400"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2">

                    <!-- ══ LEFT: Visual Inspection ══ -->
                    <div
                        class="p-8 lg:p-12 bg-gradient-to-br from-green-50/50 via-emerald-50/30 to-green-100/50 border-b lg:border-b-0 lg:border-r border-green-100/50">
                        <div class="lg:sticky lg:top-8">

                            <!-- Primary View -->
                            <div
                                class="relative rounded-3xl overflow-hidden bg-white shadow-2xl border border-white p-2 aspect-square cursor-zoom-in group ring-1 ring-gray-100">
                                @if ($product->images->count() > 0)
                                    <img id="mainImage" src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        alt="{{ $product->product_name }}"
                                        class="w-full h-full object-cover rounded-2xl transition-transform duration-700 group-hover:scale-105"
                                        onclick="openImageModal(this.src)">
                                @elseif($product->image)
                                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->product_name }}"
                                        class="w-full h-full object-cover rounded-2xl transition-transform duration-700 group-hover:scale-105"
                                        onclick="openImageModal(this.src)">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 rounded-2xl">
                                        <i class="fas fa-seedling text-green-200 text-7xl mb-4 opacity-50"></i>
                                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Awaiting Visual</p>
                                    </div>
                                @endif

                                {{-- Live Badge Overlay --}}
                                <div class="absolute top-5 left-5">
                                    <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg backdrop-blur-md border inline-flex items-center gap-2
                                                            @if ($product->status == 'Available') bg-green-500 text-white border-green-400
                                                            @elseif($product->status == 'Sold Out') bg-red-500 text-white border-red-400
                                                            @else bg-amber-500 text-white border-amber-400 @endif">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        {{ $product->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Inspection Gallery -->
                            @if ($product->images->count() > 1)
                                <div class="mt-8 flex gap-3.5 flex-wrap">
                                    @foreach ($product->images as $index => $image)
                                        <button
                                            class="w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all duration-300 shadow-sm hover:shadow-lg focus:outline-none {{ $index === 0 ? 'border-green-500 ring-4 ring-green-500/10' : 'border-white opacity-60 hover:opacity-100 hover:border-green-200' }}"
                                            onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery Item"
                                                class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Market Progress Card --}}
                            <div class="mt-10 p-6 rounded-3xl bg-white border border-gray-100 shadow-sm">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-400">Inventory Health
                                    </h4>
                                    @php
                                        $orig = (float) ($product->quantity ?: 1);
                                        $rem = (float) ($product->remainingInventory?->remaining_quantity ?? $product->quantity);
                                        $percent = ($rem / $orig) * 100;
                                    @endphp
                                    <span class="text-lg font-black text-green-600">{{ round($percent) }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-full transition-all duration-1000"
                                        style="width: {{ $percent }}%"></div>
                                </div>
                                <p class="mt-4 text-[10px] text-gray-400 font-medium italic">Calculated based on current
                                    remaining stock vs original listing.</p>
                            </div>
                        </div>
                    </div>

                    <!-- ══ RIGHT: Metadata & Intelligence ══ -->
                    <div class="p-8 lg:p-12 flex flex-col gap-10">

                        <!-- Identity Section -->
                        <div>
                            <div class="flex items-center gap-2.5 mb-2">
                                <span class="w-8 h-px bg-green-500 rounded-full"></span>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-green-500">Live Agriculture
                                    Listing</p>
                            </div>
                            <h2 class="text-5xl font-black text-gray-950 leading-tight tracking-tighter">
                                {{ $product->product_name }}
                            </h2>
                            @if ($product->variety_size)
                                <span
                                    class="mt-4 inline-flex items-center gap-2 text-indigo-600 font-black text-xs bg-indigo-50 px-4 py-2 rounded-xl uppercase tracking-widest border border-indigo-100">
                                    <i class="fas fa-layer-group"></i>
                                    {{ $product->variety_size }}
                                </span>
                            @endif
                        </div>

                        <!-- Commercial Valuation Block -->
                        <div
                            class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                            {{-- Decorative Background --}}
                            <div
                                class="absolute -right-10 -bottom-10 w-40 h-40 bg-green-500/10 rounded-full blur-3xl transition-all duration-700 group-hover:scale-150">
                            </div>
                            <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>

                            <div class="relative flex items-center justify-between">
                                <div>
                                    <p class="text-green-200 text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                                        Commercial Valuation</p>
                                    <div class="flex items-baseline gap-2">
                                        <span
                                            class="text-5xl font-black text-white leading-none tracking-tighter">₱{{ number_format($product->price, 2) }}</span>
                                        <span class="text-green-500 text-lg font-bold">/ {{ $product->unit }}</span>
                                    </div>
                                    <p class="text-green-200 text-xs mt-3 font-medium">Remaining Asset Value: <span
                                            class="text-white font-black">₱{{ number_format($product->remainingInventory?->remaining_price ?? ($product->total_amount ?? $product->quantity * $product->price), 2) }}</span>
                                    </p>
                                </div>
                                <div
                                    class="w-16 h-16 bg-white/20 rounded-3xl flex items-center justify-center border border-white/10 group-hover:rotate-12 transition-transform shadow-inner">
                                    <i class="fas fa-sack-dollar text-white text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Core Attributes Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Harvest Intel -->
                            <div
                                class="p-6 rounded-3xl bg-amber-50/50 border border-amber-100/50 group hover:bg-amber-50 transition-colors">
                                <div
                                    class="w-10 h-10 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 mb-4 shadow-sm">
                                    <i class="fas fa-calendar-check text-sm"></i>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-amber-400 mb-1">Harvest
                                    Timeline</p>
                                <p class="text-lg font-black text-amber-900">
                                    {{ optional($product->harvest_date)->format('M d, Y') ?? 'Not defined' }}
                                </p>
                            </div>

                            <!-- Stock Intel -->
                            <div
                                class="p-6 rounded-3xl bg-sky-50/50 border border-sky-100/50 group hover:bg-sky-50 transition-colors">
                                <div
                                    class="w-10 h-10 bg-sky-100 rounded-2xl flex items-center justify-center text-sky-600 mb-4 shadow-sm">
                                    <i class="fas fa-warehouse text-sm"></i>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sky-400 mb-1">Current
                                    Inventory</p>
                                <p class="text-lg font-black text-sky-900">{{ $rem }} <span
                                        class="text-sm font-bold opacity-40 uppercase">{{ $product->unit }}</span></p>
                            </div>
                        </div>

                        <!-- Transactional Metadata -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-3xl border border-gray-100">
                                <div
                                    class="w-10 h-10 bg-white shadow-sm border border-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                                    <i class="fas fa-clock text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Listing
                                        Maturity</p>
                                    <p class="text-sm font-bold text-gray-700">Posted on
                                        {{ $product->created_at->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-3xl border border-gray-100">
                                <div
                                    class="w-10 h-10 bg-white shadow-sm border border-gray-200 rounded-xl flex items-center justify-center text-red-400">
                                    <i class="fas fa-location-dot text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Logistics
                                        Origin</p>
                                    <p class="text-sm font-bold text-gray-700 leading-tight">
                                        {{ collect([$product->purok_street, $product->barangay, $product->municipality_city, $product->province])->filter()->implode(', ') ?: 'Origin not specified' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Engagement Footer --}}
                        <div class="mt-auto pt-6 border-t border-gray-100 flex flex-col gap-4">
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Communication & Matches
                            </h4>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('farmer.messages') }}"
                                    class="flex-1 py-5 rounded-[1.5rem] bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black uppercase tracking-widest text-center shadow-xl shadow-indigo-200 transition-all duration-300 hover:-translate-y-1">
                                    Jump to Messages
                                </a>
                                {{-- Matching indicator --}}
                                @if (isset($product->matches) && $product->matches->count() > 0)
                                    <div class="px-6 py-5 rounded-[1.5rem] bg-white border border-gray-200 text-center">
                                        <p class="text-xs font-black text-indigo-600">{{ $product->matches->count() }}</p>
                                        <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">Active Matches
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ══ BOTTOM: Expanded Intelligence ══ -->
                @if ($product->description)
                    <div class="border-t border-gray-100 p-12 bg-gray-50/30">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 bg-white shadow-sm border border-gray-100 rounded-2xl flex items-center justify-center text-green-600">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <h3
                                class="text-xl font-black text-gray-900 tracking-tight leading-none uppercase tracking-widest text-xs">
                                Merchant Notes & Description</h3>
                        </div>
                        <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
                            <p class="text-gray-600 leading-relaxed text-lg font-medium">{{ $product->description }}</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- ══ PERSPECTIVE MODAL ══ -->
    <div id="imageModal" class="fixed inset-0 z-[100] hidden">
        <div class="fixed inset-0 bg-gray-950/95 backdrop-blur-xl" onclick="closeImageModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-8">
            <button onclick="closeImageModal()"
                class="absolute top-8 right-8 z-[110] w-14 h-14 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 border border-white/10 group">
                <i class="fas fa-times text-xl group-hover:rotate-90 transition-transform duration-300"></i>
            </button>
            <img id="modalImage" src="" alt="Inspection Zoom"
                class="relative z-[105] max-w-full max-h-[85vh] object-contain rounded-3xl shadow-2xl border border-white/10 shadow-black/80">
        </div>
    </div>

    <script>
        function changeMainImage(src, btn) {
            const mainImg = document.getElementById('mainImage');
            mainImg.classList.add('opacity-0');

            // Handle active state for thumbnails
            document.querySelectorAll('[onclick^="changeMainImage"]').forEach(b => {
                b.classList.remove('border-green-500', 'ring-4', 'ring-green-500/10');
                b.classList.add('border-white', 'opacity-60');
            });

            if (btn) {
                btn.classList.add('border-green-500', 'ring-4', 'ring-green-500/10');
                btn.classList.remove('border-white', 'opacity-60');
            }

            setTimeout(() => {
                mainImg.src = src;
                mainImg.classList.remove('opacity-0');
            }, 300);
        }

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animate modal entry
            anime({
                targets: '#imageModal',
                opacity: [0, 1],
                duration: 400,
                easing: 'easeOutQuart'
            });
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeImageModal();
        });
    </script>

    <style>
        #mainImage {
            transition: opacity 0.3s ease-in-out, transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    </style>

@endsection