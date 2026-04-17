@extends('layouts.farmers_page')

@section('title', 'Refine Farmer Profile • AgriConnect')

@section('content')
    <div class="ml-64 p-12 max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Farm Biography Refinement</h1>
                <p class="text-gray-500 font-medium mt-1">Enhance your farm's identity to attract more high-volume matches.
                </p>
            </div>
            <a href="{{ route('farmer.profile') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-emerald-600 transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i>
                Discard and Return
            </a>
        </div>

        @if(session('success'))
            <div
                class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 animate-fade-in-down">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('farmer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Avatar Selection --}}
                <div class="lg:col-span-1 text-center">
                    <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm  group">
                        <div class="relative inline-block mb-8">
                            <div
                                class="p-1 rounded-[3rem] border-2 border-dashed border-gray-200 group-hover:border-emerald-400 transition-colors">
                                <img id="profile-preview"
                                    src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}"
                                    alt="Profile"
                                    class="w-40 h-40 rounded-[2.8rem] object-cover shadow-2xl group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <label for="profile_picture"
                                class="absolute -bottom-2 -right-2 bg-emerald-600 text-white w-12 h-12 rounded-[1.2rem] border-4 border-white flex items-center justify-center cursor-pointer hover:bg-emerald-700 hover:scale-110 transition-all shadow-lg shadow-emerald-200">
                                <i class="fas fa-camera text-sm"></i>
                            </label>
                            <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*">
                        </div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight leading-none">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                        <div
                            class="mt-2 text-[10px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-3 py-1 rounded-full inline-block">
                            Registered Producer</div>

                        <div class="mt-10 pt-10 border-t border-gray-50 space-y-4">
                            <button type="submit"
                                class="w-full bg-emerald-600 text-white px-8 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-xs hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 active:scale-95">
                                Commit Bio Updates
                            </button>
                            <p class="text-[10px] text-gray-400 font-bold px-4 leading-relaxed line-clamp-2">
                                Your updated farm bio will be synchronized across our directory instantly.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right: Form Data --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Personal Identity Card --}}
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <i class="fas fa-fingerprint text-sm"></i>
                                </div>
                                <h3 class="font-black text-gray-900 tracking-tight uppercase text-xs tracking-[0.2em]">
                                    Personal Identity</h3>
                            </div>
                        </div>
                        <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label for="first_name"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">First
                                    Identification</label>
                                <input type="text" name="first_name" id="first_name"
                                    value="{{ old('first_name', Auth::user()->first_name) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none">
                                @error('first_name')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label for="last_name"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Family/Business
                                    Name</label>
                                <input type="text" name="last_name" id="last_name"
                                    value="{{ old('last_name', Auth::user()->last_name) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none">
                                @error('last_name')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label for="email"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Sourcing
                                    Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none">
                                @error('email')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label for="phone_number"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Mobile
                                    Connectivity</label>
                                <input type="text" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number', Auth::user()->phone_number) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none">
                                @error('phone_number')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Farm Operations Card --}}
                    @if(Auth::user()->farmer)
                        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-emerald-50/20">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                        <i class="fas fa-tractor text-sm"></i>
                                    </div>
                                    <h3 class="font-black text-gray-900 tracking-tight uppercase text-xs tracking-[0.2em]">
                                        Operational Specifications</h3>
                                </div>
                            </div>
                            <div class="p-10 space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-3">
                                        <label for="farm_name"
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Farm
                                            Operational Name</label>
                                        <input type="text" name="farm_name" id="farm_name"
                                            value="{{ old('farm_name', Auth::user()->farmer->farm_name) }}"
                                            class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none border-emerald-100/30">
                                        @error('farm_name')
                                            <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-3">
                                        <label for="farm_size"
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Land
                                            Area Size (Hectares/m²)</label>
                                        <input type="text" name="farm_size" id="farm_size"
                                            value="{{ old('farm_size', Auth::user()->farmer->farm_size) }}"
                                            class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none border-emerald-100/30">
                                        @error('farm_size')
                                            <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-3">
                                        <label for="product_type"
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Crop/Product
                                            Core Category</label>
                                        <input type="text" name="product_type" id="product_type"
                                            value="{{ old('product_type', Auth::user()->farmer->product_type) }}"
                                            placeholder="e.g. Fruits, Grains"
                                            class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none">
                                        @error('product_type')
                                            <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                                <div class="space-y-3">
                                    <label for="certification"
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Agricultural
                                        Certifications (GAP, Organic, etc.)</label>
                                    <input type="text" name="certification" id="certification"
                                        value="{{ old('certification', Auth::user()->farmer->certification) }}"
                                        class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none">
                                    @error('certification')
                                        <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-3">
                                    <label for="farm_address"
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Physical
                                        Location/Farm Hub</label>
                                    <textarea name="farm_address" id="farm_address" rows="3"
                                        class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none min-h-[120px]">{{ old('farm_address', Auth::user()->farmer->farm_address) }}</textarea>
                                    @error('farm_address')
                                        <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @vite('resources/js/farmer/farmer-profile-edit.js')


    <style>
        .animate-fade-in-down {
            animation: fadeInDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection