@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-5xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 pb-6 border-b border-gray-100 gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-600/20 text-3xl font-bold">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <div class="flex items-center mt-2 gap-3">
                            <span class="px-3 py-1 inline-flex text-xs font-bold uppercase tracking-wider rounded-full shadow-sm border
                                @if($user->role == 'admin') bg-purple-50 text-purple-700 border-purple-200
                                @elseif($user->role == 'farmer') bg-emerald-50 text-emerald-700 border-emerald-200
                                @else bg-blue-50 text-blue-700 border-blue-200 @endif">
                                <i class="fas @if($user->role == 'admin') fa-shield-alt @elseif($user->role == 'farmer') fa-tractor @else fa-store @endif mr-1.5 mt-0.5"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="text-sm text-gray-500 font-medium">Joined {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                        <i class="fas fa-arrow-left mr-2 text-gray-400 group-hover:text-gray-600"></i> Back
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-400 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md transition-all duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Main Info Grids -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative z-20">
                
                <!-- Personal Info Card -->
                <div class="bg-white/70 backdrop-blur-md border border-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-green-700 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-id-card"></i> Personal Information
                    </h3>
                    
                    <dl class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="text-sm font-semibold text-gray-900 col-span-2">{{ $user->first_name }} {{ $user->last_name }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="text-sm font-semibold text-gray-900 col-span-2 break-all">{{ $user->email }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="text-sm font-semibold text-gray-900 col-span-2">{{ $user->phone_number ?? 'Not provided' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Home Address</dt>
                            <dd class="text-sm font-semibold text-gray-900 col-span-2 leading-relaxed">{{ $user->address ?? 'Not provided' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Account & Status Card -->
                <div class="bg-white/70 backdrop-blur-md border border-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-green-700 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i> Account Status
                    </h3>
                    
                    <dl class="space-y-4">
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-500">KYC Status</dt>
                            <dd class="col-span-2">
                                <span class="px-3 py-1 inline-flex text-xs font-bold uppercase tracking-wide rounded-full border
                                    @if($user->kyc_status == 'verified') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @elseif($user->kyc_status == 'pending') bg-amber-50 text-amber-700 border-amber-200
                                    @elseif($user->kyc_status == 'rejected') bg-red-50 text-red-700 border-red-200
                                    @else bg-gray-50 text-gray-600 border-gray-200 @endif">
                                    {{ $user->kyc_status ? ucfirst($user->kyc_status) : 'Unverfied' }}
                                </span>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">System ID</dt>
                            <dd class="text-sm font-mono text-gray-700 col-span-2 pl-1">USR_{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Registered</dt>
                            <dd class="text-sm font-semibold text-gray-900 col-span-2">{{ $user->created_at ? $user->created_at->format('M d, Y h:i A') : 'N/A' }}</dd>
                        </div>
                        @if($user->updated_at)
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="text-sm font-semibold text-gray-600 col-span-2">{{ $user->updated_at->diffForHumans() }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Farmer Information Special Card -->
            @if($user->role === 'farmer' && isset($farmer))
            <div class="mt-8 bg-gradient-to-br from-green-50 to-emerald-50/50 backdrop-blur-md border border-green-100 rounded-2xl shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-green-200 opacity-40 transform rotate-12 pointer-events-none">
                    <i class="fas fa-leaf text-9xl"></i>
                </div>
                
                <div class="relative z-10 p-6 md:p-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-green-800 mb-6 pb-3 border-b border-green-200/50 flex items-center gap-2">
                        <i class="fas fa-tractor text-lg"></i> Authenticated Farmer Data
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-green-700/70 uppercase">Farm Name</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $farmer->farm_name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-green-700/70 uppercase">Product Type / Crop</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $farmer->product_type ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-green-700/70 uppercase">Land Size</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $farmer->farm_size ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-green-700/70 uppercase">Experience</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $farmer->experience_years ? $farmer->experience_years . ' Years' : 'N/A' }}</p>
                        </div>
                        <div class="space-y-1 lg:col-span-2">
                            <p class="text-xs font-semibold text-green-700/70 uppercase">Certifications</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $farmer->certification ?? 'None specified' }}</p>
                        </div>
                        <div class="space-y-1 md:col-span-2 lg:col-span-3">
                            <p class="text-xs font-semibold text-green-700/70 uppercase">Farm Location</p>
                            <p class="font-semibold text-gray-900 text-base flex items-start gap-2 mt-1">
                                <i class="fas fa-map-marker-alt text-green-500 mt-1"></i> {{ $farmer->farm_address ?? 'Location not provided' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Buyer Information Special Card -->
            @if($user->role === 'buyer' && isset($buyer))
            <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50/50 backdrop-blur-md border border-blue-100 rounded-2xl shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-blue-200 opacity-40 transform rotate-12 pointer-events-none">
                    <i class="fas fa-store text-9xl"></i>
                </div>
                
                <div class="relative z-10 p-6 md:p-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-blue-800 mb-6 pb-3 border-b border-blue-200/50 flex items-center gap-2">
                        <i class="fas fa-building text-lg"></i> Authenticated Buyer Data
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="space-y-1 lg:col-span-2">
                            <p class="text-xs font-semibold text-blue-700/70 uppercase">Company / Entity Name</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $buyer->company_name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-blue-700/70 uppercase">Business Verification</p>
                            <p class="font-semibold mt-1">
                                @if($buyer->verified)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm border border-blue-200">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm border border-gray-200">
                                        <i class="fas fa-hourglass-half"></i> Unverified
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-blue-700/70 uppercase">Type of Business</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $buyer->business_type ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1 lg:col-span-2">
                            <p class="text-xs font-semibold text-blue-700/70 uppercase">Preferred Demand Specs</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $buyer->preferred_products ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1 md:col-span-2 lg:col-span-3">
                            <p class="text-xs font-semibold text-blue-700/70 uppercase">Shipping / Branch Address</p>
                            <p class="font-semibold text-gray-900 text-base flex items-start gap-2 mt-1">
                                <i class="fas fa-truck text-blue-500 mt-1"></i> {{ $buyer->address ?? 'Location not provided' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Danger Zone -->
            <div class="mt-12 pt-6 border-t border-gray-200 flex justify-end">
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                      class="inline delete-form flex items-center" data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                    @csrf
                    @method('DELETE')
                    <div class="mr-4 text-right">
                        <p class="text-sm font-bold text-red-600">Danger Zone</p>
                        <p class="text-xs text-gray-500">This action cannot be undone.</p>
                    </div>
                    <button type="submit" 
                            class="bg-white border border-red-200 hover:bg-red-50 hover:border-red-300 hover:text-red-700 text-red-600 font-bold px-6 py-2.5 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-trash-alt"></i> Delete User
                    </button>
                </form>
            </div>
            
        </div>
    </main>
</div>

@vite('resources/js/admin/users/admin-users-show.js')
@endsection