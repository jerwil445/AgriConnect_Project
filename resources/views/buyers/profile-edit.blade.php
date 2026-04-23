@extends('layouts.buyers_page')

@section('title', 'Refine Buyer Profile • AgriConnect')

@section('content')
    <div class="container mx-auto px-4 py-12 max-w-5xl">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Refine Your Identity</h1>
                <p class="text-gray-500 font-medium mt-1">Keep your profile updated to build trust with our farmer network.
                </p>
            </div>
            <a href="{{ route('buyer.profile') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
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

        <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Avatar Selection --}}
                <div class="lg:col-span-1 sticky">
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm sticky top-8 text-center group">
                        <div class="relative inline-block mb-6">
                            <div
                                class="p-1 rounded-[2.5rem] border-2 border-dashed border-gray-200 group-hover:border-indigo-400 transition-colors">
                                <img id="profile-preview"
                                    src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}"
                                    alt="Profile" class="w-32 h-32 rounded-[2.2rem] object-cover shadow-2xl">
                            </div>
                            <label for="profile_picture"
                                class="absolute -bottom-2 -right-2 bg-indigo-600 text-white w-10 h-10 rounded-2xl border-4 border-white flex items-center justify-center cursor-pointer hover:bg-indigo-700 hover:scale-110 transition-all shadow-lg shadow-indigo-200">
                                <i class="fas fa-camera text-xs"></i>
                            </label>
                            <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*">
                        </div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ Auth::user()->first_name }}</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Verification Status:
                            Verified</p>

                        <div class="mt-8 pt-8 border-t border-gray-50 space-y-3">
                            <button type="submit"
                                class="w-full bg-gray-900 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-600 transition-all shadow-xl shadow-gray-200 active:scale-95">
                                Save Changes
                            </button>
                            <p class="text-[10px] text-gray-400 font-bold leading-relaxed px-4">
                                Changes are applied immediately after verification.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right: Form Data --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Personal Identity Card --}}
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                                    <i class="fas fa-user-circle text-xs"></i>
                                </div>
                                <h3 class="font-black text-gray-900 tracking-tight uppercase text-sm tracking-widest">
                                    Personal Identification</h3>
                            </div>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="first_name"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">First Legal
                                    Name</label>
                                <input type="text" name="first_name" id="first_name"
                                    value="{{ old('first_name', Auth::user()->first_name) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none placeholder-gray-300">
                                @error('first_name')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="last_name"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Last Family
                                    Name</label>
                                <input type="text" name="last_name" id="last_name"
                                    value="{{ old('last_name', Auth::user()->last_name) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none placeholder-gray-300">
                                @error('last_name')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="email"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Email
                                    Communication</label>
                                <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none placeholder-gray-300">
                                @error('email')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="phone_number"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Mobile
                                    Connectivity</label>
                                <input type="text" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number', Auth::user()->phone_number) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none placeholder-gray-300">
                                @error('phone_number')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Corporate Context Card --}}
                    @if(Auth::user()->buyer)
                        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                        <i class="fas fa-briefcase text-xs"></i>
                                    </div>
                                    <h3 class="font-black text-gray-900 tracking-tight uppercase text-sm tracking-widest">
                                        Business Information</h3>
                                </div>
                            </div>
                            <div class="p-8 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="company_name"
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Company
                                            Entity Name</label>
                                        <input type="text" name="company_name" id="company_name"
                                            value="{{ old('company_name', Auth::user()->buyer->company_name) }}"
                                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none">
                                        @error('company_name')
                                            <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label for="business_type"
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Industry
                                            Sector</label>
                                        <input type="text" name="business_type" id="business_type"
                                            value="{{ old('business_type', Auth::user()->buyer->business_type) }}"
                                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none">
                                        @error('business_type')
                                            <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="space-y-4" data-role-field="buyer">
                                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">
                                        Sourcing Interest Categories
                                    </h4>
                                    <div class="flex flex-wrap gap-2.5 px-2" id="buyer-categories">
                                        @php
                                            $selectedCats = Auth::user()->buyer->categories ? explode(',', Auth::user()->buyer->categories) : [];
                                        @endphp
                                        @foreach(['Grains & Cereals', 'Fruits & Berries', 'Vegetables', 'Livestock', 'Poultry & Eggs', 'Fisheries', 'Roots & Tubers', 'Herbs & Spices', 'Dairy', 'Industrial Crops'] as $category)
                                            @php $isActive = in_array($category, $selectedCats); @endphp
                                            <button type="button" data-category="{{ $category }}"
                                                class="category-chip px-6 py-3 rounded-2xl border-2 {{ $isActive ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-gray-100 bg-white text-gray-500' }} text-sm font-bold hover:border-indigo-400 hover:text-indigo-600 transition-all shadow-sm active:scale-95">
                                                {{ $category }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="categories" id="buyer-categories-input" value="{{ Auth::user()->buyer->categories }}">
                                </div>

                                <div class="space-y-2">
                                    <label for="preferred_products"
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Detailed Sourcing Preferences</label>
                                    <textarea name="preferred_products" id="preferred_products"
                                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none min-h-[120px]">{{ old('preferred_products', Auth::user()->buyer->preferred_products) }}</textarea>
                                    @error('preferred_products')
                                        <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label for="buyer_address"
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Core
                                        Operational Address</label>
                                    <textarea name="buyer_address" id="buyer_address" rows="3"
                                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none min-h-[100px]">{{ old('buyer_address', Auth::user()->buyer->address) }}</textarea>
                                    @error('buyer_address')
                                        <p class="text-[10px] text-red-500 font-bold mt-1 px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <div
                        class="flex items-center justify-between p-4 bg-gray-100/50 rounded-2xl border border-dashed border-gray-200">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">AgriConnect Ecosystem Trust
                            Protocols</p>
                        <i class="fas fa-lock text-gray-300"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @vite('resources/js/buyer/buyer-profile-edit.js')

    <style>
        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection