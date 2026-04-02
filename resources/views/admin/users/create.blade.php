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
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Add New User</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Create a new administrator, farmer, or buyer</p>
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

            <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white/60 backdrop-blur-xl rounded-2xl border border-white shadow-sm p-6 lg:p-8">
                @csrf
                
                <div class="mb-6 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-700">Personal Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="relative w-full">
                        <input type="text" name="first_name" id="first_name" 
                               value="{{ old('first_name') }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="first_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded">
                            First Name
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="text" name="last_name" id="last_name" 
                               value="{{ old('last_name') }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="last_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded">
                            Last Name
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="email" name="email" id="email" 
                               value="{{ old('email') }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="email" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded">
                            Email Address
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="text" name="phone_number" id="phone_number" 
                               value="{{ old('phone_number') }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="phone_number" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded">
                            Phone Number
                        </label>
                    </div>

                    <div class="relative w-full md:col-span-2">
                        <input type="text" name="address" id="address" 
                               value="{{ old('address') }}" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="address" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded">
                            Full Address
                        </label>
                    </div>
                </div>

                <div class="mt-10 mb-6 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-700">Account Security</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="relative w-full">
                        <input type="password" name="password" id="password" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="password" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded">
                            Password
                        </label>
                    </div>

                    <div class="relative w-full">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" "
                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                        <label for="password_confirmation" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded">
                            Confirm Password
                        </label>
                    </div>
                </div>

                <div class="mt-10 mb-6 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-700">Permissions & Status</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="relative w-full group">
                        <select name="role" id="role" 
                                class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent shadow-sm appearance-none">
                            <option value="disabled" disabled {{ !old('role') ? 'selected' : '' }} hidden></option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
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
                            <option value="" {{ !old('kyc_status') ? 'selected' : '' }}>Select Verification Status</option>
                            <option value="pending" {{ old('kyc_status') == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                            <option value="verified" {{ old('kyc_status') == 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                            <option value="rejected" {{ old('kyc_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <label for="kyc_status" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-green-600 rounded transition-all">
                            KYC Status
                        </label>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500">
                            <i class="fas fa-chevron-down text-sm"></i>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" 
                            class="bg-gradient-to-r from-green-600 to-emerald-500 text-white font-bold px-8 py-3.5 rounded-xl shadow-[0_10px_20px_-10px_rgba(16,185,129,0.5)] hover:shadow-[0_15px_25px_-10px_rgba(16,185,129,0.6)] transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection