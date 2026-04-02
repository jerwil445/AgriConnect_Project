@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-4xl mx-auto">
            
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-green-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600 shadow-inner">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Profile: {{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Manage user details and permissions</p>
                    </div>
                </div>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400 group-hover:text-gray-600 transition-colors" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Users
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50/80 backdrop-blur-sm border-l-4 border-red-500 text-red-800 px-5 py-4 rounded-r-xl shadow-sm mb-8">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <strong class="font-bold text-red-700">Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600/90 ml-6 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white/60 backdrop-blur-xl rounded-2xl border border-white shadow-sm p-6 lg:p-8">
                @csrf
                @method('PUT')
                
                <!-- Base Profile Section -->
                <div class="mb-6 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-700">Personal Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="relative w-full">
                        <input type="text" name="first_name" id="first_name" 
                               value="{{ old('first_name', $user->first_name) }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="first_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                            First Name
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="text" name="last_name" id="last_name" 
                               value="{{ old('last_name', $user->last_name) }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="last_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                            Last Name
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="email" name="email" id="email" 
                               value="{{ old('email', $user->email) }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="email" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                            Email Address
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="text" name="phone_number" id="phone_number" 
                               value="{{ old('phone_number', $user->phone_number) }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="phone_number" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                            Phone Number
                        </label>
                    </div>

                    <div class="relative w-full md:col-span-2">
                        <input type="text" name="address" id="address" 
                               value="{{ old('address', $user->address) }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="address" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                            Full Address
                        </label>
                    </div>
                </div>

                <!-- Account Security Section -->
                <div class="mt-10 mb-6 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-700">Account Security</h3>
                    <span class="text-xs text-gray-400 ml-2 font-normal">(Leave blank to keep current password)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="relative w-full">
                        <input type="password" name="password" id="password" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="password" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                            New Password
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="password_confirmation" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                            Confirm New Password
                        </label>
                    </div>
                </div>

                <!-- Status & Roles Section -->
                <div class="mt-10 mb-6 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-700">Permissions & Status</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="relative w-full group">
                        <select name="role" id="role" 
                                class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent shadow-sm appearance-none">
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="farmer" {{ old('role', $user->role) == 'farmer' ? 'selected' : '' }}>Farmer</option>
                            <option value="buyer" {{ old('role', $user->role) == 'buyer' ? 'selected' : '' }}>Buyer</option>
                        </select>
                        <label for="role" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded transition-all">
                            System Role
                        </label>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                            <i class="fas fa-chevron-down text-sm"></i>
                        </div>
                    </div>

                    <div class="relative w-full group">
                        <select name="kyc_status" id="kyc_status" 
                                class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent shadow-sm appearance-none">
                            <option value="pending" {{ old('kyc_status', $user->kyc_status) == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                            <option value="verified" {{ old('kyc_status', $user->kyc_status) == 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                            <option value="rejected" {{ old('kyc_status', $user->kyc_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <label for="kyc_status" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded transition-all">
                            KYC Status
                        </label>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                            <i class="fas fa-chevron-down text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Farmer Extra Information -->
                @if($user->role === 'farmer')
                <div class="mt-10 mb-6 p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100/50 shadow-sm relative overflow-hidden">
                    <!-- Subtle Leaf decoration -->
                    <div class="absolute -right-4 -top-4 text-green-200 opacity-50 transform rotate-12">
                        <i class="fas fa-leaf text-8xl"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-green-800 mb-6 pb-2 border-b border-green-200/50 flex items-center gap-2">
                            <i class="fas fa-tractor"></i> Farmer Sub-Profile
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="relative w-full">
                                <input type="text" name="farm_name" id="farm_name" 
                                       value="{{ old('farm_name', $farmer->farm_name ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="farm_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white rounded cursor-text">
                                    Farm Name
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="farm_size" id="farm_size" 
                                       value="{{ old('farm_size', $farmer->farm_size ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="farm_size" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white rounded cursor-text">
                                    Farm Size
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="product_type" id="product_type" 
                                       value="{{ old('product_type', $farmer->product_type ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="product_type" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white rounded cursor-text">
                                    Primary Crop / Product Type
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="number" name="experience_years" id="experience_years" 
                                       value="{{ old('experience_years', $farmer->experience_years ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="experience_years" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white rounded cursor-text">
                                    Experience (Years)
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="certification" id="certification" 
                                       value="{{ old('certification', $farmer->certification ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="certification" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white rounded cursor-text">
                                    Organic/Farm Certification
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="farm_address" id="farm_address" 
                                       value="{{ old('farm_address', $farmer->farm_address ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="farm_address" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white rounded cursor-text">
                                    Farm Address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Buyer Extra Information -->
                @if($user->role === 'buyer')
                <div class="mt-10 mb-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100/50 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 text-blue-200 opacity-50 transform rotate-12">
                        <i class="fas fa-store text-8xl"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-blue-800 mb-6 pb-2 border-b border-blue-200/50 flex items-center gap-2">
                            <i class="fas fa-building"></i> Buyer Sub-Profile
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="relative w-full">
                                <input type="text" name="company_name" id="company_name" 
                                       value="{{ old('company_name', $buyer->company_name ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="company_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-indigo-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-700 peer-focus:bg-white rounded cursor-text">
                                    Company / Store Name
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="business_type" id="business_type" 
                                       value="{{ old('business_type', $buyer->business_type ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="business_type" class="absolute left-4 -top-2.5 text-xs font-medium bg-indigo-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-700 peer-focus:bg-white rounded cursor-text">
                                    Business Type (e.g. Retail, Wholesale)
                                </label>
                            </div>

                            <div class="relative w-full">
                                <input type="text" name="preferred_products" id="preferred_products" 
                                       value="{{ old('preferred_products', $buyer->preferred_products ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="preferred_products" class="absolute left-4 -top-2.5 text-xs font-medium bg-indigo-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-700 peer-focus:bg-white rounded cursor-text">
                                    Preferred Demands
                                </label>
                            </div>

                            <div class="relative w-full group">
                                <select name="verified" id="verified" 
                                        class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)] appearance-none">
                                    <option value="1" {{ old('verified', $buyer->verified ?? '') == '1' ? 'selected' : '' }}>Yes (Verified Business)</option>
                                    <option value="0" {{ old('verified', $buyer->verified ?? '') == '0' ? 'selected' : '' }}>No (Unverified)</option>
                                </select>
                                <label for="verified" class="absolute left-4 -top-2.5 text-xs font-medium bg-indigo-50 px-1 text-blue-700 rounded transition-all">
                                    Business Verification
                                </label>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                            
                            <div class="relative w-full md:col-span-2">
                                <input type="text" name="buyer_address" id="buyer_address" 
                                       value="{{ old('buyer_address', $buyer->address ?? '') }}" placeholder=" "
                                       class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                <label for="buyer_address" class="absolute left-4 -top-2.5 text-xs font-medium bg-indigo-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-700 peer-focus:bg-white rounded cursor-text">
                                    Shipping / Business Address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-10 pt-6 flex justify-end border-t border-gray-100">
                    <button type="submit" 
                            class="bg-gradient-to-r from-green-600 to-emerald-500 text-white font-bold px-8 py-3.5 rounded-xl shadow-[0_10px_20px_-10px_rgba(16,185,129,0.5)] hover:shadow-[0_15px_25px_-10px_rgba(16,185,129,0.6)] transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection