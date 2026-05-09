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

        <form id="profile-form" action="{{ route('farmer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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

                        <div class="mt-8 grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Response Rate</div>
                                <div class="text-lg font-black text-emerald-600">{{ number_format(Auth::user()->farmer->response_rate, 0) }}%</div>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Reputation</div>
                                <div class="text-lg font-black text-amber-600">{{ Auth::user()->farmer->reputation_score }}</div>
                            </div>
                        </div>

                        <div class="mt-10 pt-10 border-t border-gray-50 space-y-4 text-left">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2 mb-4">Trust Indicators</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-gray-50/50">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Identity</span>
                                    @if(Auth::user()->kyc_status === 'verified')
                                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                                    @else
                                        <i class="fas fa-clock text-amber-400 text-xs"></i>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-gray-50/50">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Certifications</span>
                                    @php $hasApproved = Auth::user()->verifications->where('status', 'approved')->count(); @endphp
                                    @if($hasApproved > 0)
                                        <span class="text-[10px] font-black text-emerald-600">{{ $hasApproved }} Verified</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400">None Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-10 border-t border-gray-50 space-y-4">
                            <button type="submit" form="profile-form"
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
                            <div class="space-y-3">
                                <label for="sex"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Biological Sex</label>
                                <select name="sex" id="sex" 
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none">
                                    <option value="" disabled {{ !Auth::user()->sex ? 'selected' : '' }} hidden>Select Sex</option>
                                    <option value="Male" {{ old('sex', Auth::user()->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', Auth::user()->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('sex', Auth::user()->sex) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex')
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
                                    <div class="space-y-4" data-role-field="farmer">
                                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">
                                            Crops Category
                                        </h4>
                                        <div class="flex flex-wrap gap-2.5 px-2" id="farmer-categories">
                                            @php
                                                $selectedCats = Auth::user()->farmer->categories ? explode(',', Auth::user()->farmer->categories) : [];
                                            @endphp
                                            @foreach(['Grains & Cereals', 'Fruits & Berries', 'Vegetables', 'Livestock', 'Poultry & Eggs', 'Fisheries', 'Roots & Tubers', 'Herbs & Spices', 'Dairy', 'Industrial Crops'] as $category)
                                                @php $isActive = in_array($category, $selectedCats); @endphp
                                                <button type="button" data-category="{{ $category }}"
                                                    class="category-chip px-6 py-3 rounded-2xl border-2 {{ $isActive ? 'border-green-500 text-green-600 bg-green-50' : 'border-gray-100 bg-white text-gray-500' }} text-sm font-bold hover:border-green-400 hover:text-green-600 transition-all shadow-sm active:scale-95">
                                                    {{ $category }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="categories" id="categories-input" value="{{ Auth::user()->farmer->categories }}">
                                    </div>

                                    <div class="space-y-3">
                                        <label for="product_type"
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Specific Produce Details</label>
                                        <textarea name="product_type" id="product_type"
                                            class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-50 transition-all border outline-none min-h-[120px]">{{ old('product_type', Auth::user()->farmer->product_type) }}</textarea>
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

                    {{-- Verification Documents Card --}}
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-indigo-50/10">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-certificate text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-gray-900 tracking-tight uppercase text-xs tracking-[0.2em]">
                                        Professional Credentials</h3>
                                    <p class="text-[10px] font-bold text-gray-400 mt-0.5">Upload PhilGAP, Organic, or Business Permits to earn trust badges.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-10 space-y-10">
                            {{-- Upload Form --}}
                            <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-dashed border-gray-200">
                                <form action="{{ route('verifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-3">
                                            <label for="document_type" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Credential Type</label>
                                            <select name="document_type" id="document_type" class="w-full bg-white border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:ring-4 focus:ring-indigo-50 transition-all border outline-none">
                                                <option value="PhilGAP Certificate">PhilGAP Certificate</option>
                                                <option value="Organic Certification">Organic Certification</option>
                                                <option value="Business Permit">Business Permit</option>
                                                <option value="Farm Registration">Farm Registration</option>
                                                <option value="Other Certification">Other Certification</option>
                                            </select>
                                        </div>
                                        <div class="space-y-3">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Document File</label>
                                            <div class="relative group">
                                                <input type="file" name="file" id="verification_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                                <label for="verification_file" class="flex items-center justify-between w-full bg-white border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-400 cursor-pointer group-hover:border-indigo-400 transition-all border">
                                                    <span id="file-name">Select PDF or Image...</span>
                                                    <i class="fas fa-upload text-xs group-hover:scale-110 transition-transform"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                                            Upload for Review
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Existing Verifications List --}}
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Uploaded Records</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    @forelse(Auth::user()->verifications as $v)
                                        <div class="flex items-center justify-between p-6 rounded-[1.5rem] bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="flex items-center gap-5">
                                                <div class="w-12 h-12 rounded-xl {{ $v->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($v->status === 'rejected' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }} flex items-center justify-center shrink-0">
                                                    <i class="fas {{ $v->status === 'approved' ? 'fa-check' : ($v->status === 'rejected' ? 'fa-times' : 'fa-clock') }} text-sm"></i>
                                                </div>
                                                <div>
                                                    <h5 class="font-black text-gray-900 text-sm tracking-tight">{{ $v->document_type }}</h5>
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Submitted {{ $v->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $v->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($v->status === 'rejected' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                                                    {{ $v->status }}
                                                </span>
                                                <a href="{{ asset('storage/' . $v->file_path) }}" target="_blank" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-gray-100 hover:text-gray-900 transition-all border border-gray-50">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                                @if($v->status !== 'approved')
                                                    <form action="{{ route('verifications.destroy', $v) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-all border border-gray-50">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        @if($v->status === 'rejected' && $v->rejection_reason)
                                            <div class="mt-2 ml-16 p-4 rounded-2xl bg-red-50/50 border border-red-100">
                                                <p class="text-xs font-bold text-red-700"><i class="fas fa-exclamation-circle mr-2"></i>Reason: {{ $v->rejection_reason }}</p>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-10 bg-gray-50/30 rounded-[2rem] border border-dashed border-gray-100">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No documents uploaded yet.</p>
                                        </div>
                                    @endforelse
                        </div>
                    </div>

                    {{-- Security & Credentials Card --}}
                    <div class="bg-gray-900 rounded-[2.5rem] border border-gray-800 shadow-xl overflow-hidden relative group">
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="px-10 py-8 border-b border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="w-10 h-10 rounded-2xl bg-white/5 text-emerald-400 flex items-center justify-center border border-white/10">
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
                                        class="w-full bg-white/5 border-white/10 rounded-2xl px-6 py-4 font-bold text-white focus:bg-white/10 focus:ring-4 focus:ring-emerald-500/20 transition-all border outline-none placeholder:text-gray-600">
                                    @error('password')
                                        <p class="text-[10px] text-red-400 font-bold mt-1 px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-3">
                                    <label for="password_confirmation" class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-2">Confirm Key</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat new security key"
                                        class="w-full bg-white/5 border-white/10 rounded-2xl px-6 py-4 font-bold text-white focus:bg-white/10 focus:ring-4 focus:ring-emerald-500/20 transition-all border outline-none placeholder:text-gray-600">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @vite('resources/js/farmer/farmer-profile-edit.js')


    <script>
        document.getElementById('verification_file')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Select PDF or Image...';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>

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