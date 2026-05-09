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

        <form id="profile-form" action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div class="p-3 rounded-2xl bg-gray-50 border border-gray-100">
                                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Response Rate</div>
                                <div class="text-base font-black text-indigo-600">{{ number_format(Auth::user()->buyer->response_rate, 0) }}%</div>
                            </div>
                            <div class="p-3 rounded-2xl bg-gray-50 border border-gray-100">
                                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Reputation</div>
                                <div class="text-base font-black text-orange-600">{{ Auth::user()->buyer->reputation_score }}</div>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-gray-50 space-y-3 text-left px-2">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Trust Indicators</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-gray-50/50">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Business Identity</span>
                                    @if(Auth::user()->kyc_status === 'verified')
                                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                                    @else
                                        <i class="fas fa-clock text-amber-400 text-xs"></i>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-gray-50/50">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Credentials</span>
                                    @php $hasApproved = Auth::user()->verifications->where('status', 'approved')->count(); @endphp
                                    @if($hasApproved > 0)
                                        <span class="text-[10px] font-black text-emerald-600">{{ $hasApproved }} Verified</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400">None Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-gray-50 space-y-3">
                            <button type="submit" form="profile-form"
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
                            <div class="space-y-2">
                                <label for="sex"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Biological Sex</label>
                                <select name="sex" id="sex" 
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all border outline-none">
                                    <option value="" disabled {{ !Auth::user()->sex ? 'selected' : '' }} hidden>Select Sex</option>
                                    <option value="Male" {{ old('sex', Auth::user()->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', Auth::user()->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('sex', Auth::user()->sex) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex')
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

                    {{-- Verification Documents Card --}}
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-indigo-50/10">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-gray-900 tracking-tight uppercase text-sm tracking-widest">
                                        Trust & Verification</h3>
                                    <p class="text-[10px] font-bold text-gray-400 mt-0.5">Upload business permits or IDs to gain "Verified Buyer" status.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 space-y-8">
                            {{-- Upload Form --}}
                            <div class="bg-gray-50/50 p-6 rounded-3xl border border-dashed border-gray-200">
                                <form action="{{ route('verifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="space-y-2">
                                            <label for="document_type" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Credential Type</label>
                                            <select name="document_type" id="document_type" class="w-full bg-white border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-900 focus:ring-4 focus:ring-indigo-50 transition-all border outline-none">
                                                <option value="Business Permit">Business Permit</option>
                                                <option value="SEC/DTI Registration">SEC/DTI Registration</option>
                                                <option value="Valid ID (Proprietor)">Valid ID (Proprietor)</option>
                                                <option value="Restaurant Verification">Restaurant Verification</option>
                                                <option value="Procurement Certification">Procurement Certification</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Document File</label>
                                            <div class="relative group">
                                                <input type="file" name="file" id="verification_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                                <label for="verification_file" class="flex items-center justify-between w-full bg-white border-gray-100 rounded-xl px-4 py-3.5 font-bold text-gray-400 cursor-pointer group-hover:border-indigo-400 transition-all border text-sm">
                                                    <span id="file-name">Choose PDF/Image...</span>
                                                    <i class="fas fa-upload text-[10px]"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest text-[9px] hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                                            Upload for Audit
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Records List --}}
                            <div class="space-y-3">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Verification Records</h4>
                                <div class="space-y-3">
                                    @forelse(Auth::user()->verifications as $v)
                                        <div class="flex items-center justify-between p-5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl {{ $v->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($v->status === 'rejected' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }} flex items-center justify-center shrink-0">
                                                    <i class="fas {{ $v->status === 'approved' ? 'fa-check' : ($v->status === 'rejected' ? 'fa-times' : 'fa-clock') }} text-xs"></i>
                                                </div>
                                                <div>
                                                    <h5 class="font-black text-gray-900 text-xs tracking-tight">{{ $v->document_type }}</h5>
                                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $v->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $v->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($v->status === 'rejected' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                                                    {{ $v->status }}
                                                </span>
                                                <a href="{{ asset('storage/' . $v->file_path) }}" target="_blank" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-gray-100 hover:text-gray-900 transition-all">
                                                    <i class="fas fa-eye text-[10px]"></i>
                                                </a>
                                                @if($v->status !== 'approved')
                                                    <form action="{{ route('verifications.destroy', $v) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-all">
                                                            <i class="fas fa-trash text-[10px]"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        @if($v->status === 'rejected' && $v->rejection_reason)
                                            <div class="mt-2 ml-14 p-3 rounded-xl bg-red-50/50 border border-red-100">
                                                <p class="text-[10px] font-bold text-red-700">Reason: {{ $v->rejection_reason }}</p>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-8 bg-gray-50/30 rounded-2xl border border-dashed border-gray-100">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">No documents found.</p>
                                        </div>
                                    @endforelse
                        </div>
                    </div>

                    {{-- Security & Credentials Card --}}
                    <div class="bg-gray-900 rounded-[2rem] border border-gray-800 shadow-xl overflow-hidden relative group">
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="px-10 py-8 border-b border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="w-10 h-10 rounded-2xl bg-white/5 text-indigo-400 flex items-center justify-center border border-white/10">
                                    <i class="fas fa-key text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-white tracking-tight uppercase text-xs tracking-[0.2em]">Security Protocol</h3>
                                    <p class="text-[10px] font-bold text-gray-500 mt-0.5">Update your password to maintain account integrity.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-10 space-y-8 relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label for="password" class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-2">New Security Key</label>
                                    <input type="password" name="password" id="password" placeholder="Leave empty to maintain current"
                                        class="w-full bg-white/5 border-white/10 rounded-2xl px-6 py-4 font-bold text-white focus:bg-white/10 focus:ring-4 focus:ring-indigo-500/20 transition-all border outline-none placeholder:text-gray-600">
                                    @error('password')
                                        <p class="text-[10px] text-red-400 font-bold mt-1 px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-3">
                                    <label for="password_confirmation" class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-2">Confirm Key</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat new security key"
                                        class="w-full bg-white/5 border-white/10 rounded-2xl px-6 py-4 font-bold text-white focus:bg-white/10 focus:ring-4 focus:ring-indigo-500/20 transition-all border outline-none placeholder:text-gray-600">
                                </div>
                            </div>
                        </div>
                    </div>

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

    <script>
        document.getElementById('verification_file')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Choose PDF/Image...';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>

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