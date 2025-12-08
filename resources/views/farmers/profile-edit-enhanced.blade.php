@extends('layouts.farmers_page')

@section('title', 'Edit Profile')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
        <p class="text-gray-600 mt-1">Update your farm and business information</p>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
        <div class="flex">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <form action="{{ route('farmer.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tab Navigation -->
        <div class="bg-white rounded-xl shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" onclick="showTab('basic')" id="tab-basic" class="tab-button border-green-500 text-green-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Basic Information
                    </button>
                    <button type="button" onclick="showTab('farm')" id="tab-farm" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Farm Details
                    </button>
                    <button type="button" onclick="showTab('business')" id="tab-business" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Business & Certification
                    </button>
                    <button type="button" onclick="showTab('payment')" id="tab-payment" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Payment Information
                    </button>
                    <button type="button" onclick="showTab('operational')" id="tab-operational" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Operational
                    </button>
                </nav>
            </div>

            <!-- Tab Content Continues... -->
            <!-- BASIC INFORMATION TAB -->
            <div id="content-basic" class="tab-content p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('first_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('last_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('phone_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Phone</label>
                        <input type="text" name="secondary_phone" value="{{ old('secondary_phone', $user->farmer->secondary_phone ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $user->farmer->whatsapp_number ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        <input type="file" name="profile_picture" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Formats: JPG, PNG, GIF</p>
                    </div>
                </div>
            </div>

            <!-- FARM DETAILS TAB -->
            <div id="content-farm" class="tab-content p-6 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Farm Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Farm Name *</label>
                        <input type="text" name="farm_name" value="{{ old('farm_name', $user->farmer->farm_name ?? '') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Farm Size</label>
                        <input type="number" name="farm_size" value="{{ old('farm_size', $user->farmer->farm_size ?? '') }}" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                        <select name="farm_size_unit"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="hectares" {{ old('farm_size_unit', $user->farmer->farm_size_unit ?? '') == 'hectares' ? 'selected' : '' }}>Hectares</option>
                            <option value="acres" {{ old('farm_size_unit', $user->farmer->farm_size_unit ?? '') == 'acres' ? 'selected' : '' }}>Acres</option>
                            <option value="sq_meters" {{ old('farm_size_unit', $user->farmer->farm_size_unit ?? '') == 'sq_meters' ? 'selected' : '' }}>Square Meters</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Chickens</label>
                        <input type="number" name="total_chickens" value="{{ old('total_chickens', $user->farmer->total_chickens ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Farming Method</label>
                        <input type="text" name="farming_method" value="{{ old('farming_method', $user->farmer->farming_method ?? '') }}" 
                               placeholder="e.g., Free-range, Cage-free, Organic"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', $user->farmer->experience_years ?? '') }}" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                        <input type="number" name="latitude" value="{{ old('latitude', $user->farmer->latitude ?? '') }}" step="0.0000001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                        <input type="number" name="longitude" value="{{ old('longitude', $user->farmer->longitude ?? '') }}" step="0.0000001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Farm Address</label>
                        <textarea name="farm_address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('farm_address', $user->farmer->farm_address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Continues with other tabs... Business, Payment, Operational -->
            <!-- BUSINESS & CERTIFICATION TAB -->
            <div id="content-business" class="tab-content p-6 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Business & Certification</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Business Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                        <select name="business_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="Individual" {{ old('business_type', $user->farmer->business_type ?? '') == 'Individual' ? 'selected' : '' }}>Individual</option>
                            <option value="Partnership" {{ old('business_type', $user->farmer->business_type ?? '') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="Corporation" {{ old('business_type', $user->farmer->business_type ?? '') == 'Corporation' ? 'selected' : '' }}>Corporation</option>
                        </select>
                    </div>

                    <!-- Business Registration Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Business Registration Number</label>
                        <input type="text" name="business_registration_number" value="{{ old('business_registration_number', $user->farmer->business_registration_number ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Tax ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID Number (TIN)</label>
                        <input type="text" name="tax_id_number" value="{{ old('tax_id_number', $user->farmer->tax_id_number ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- General Certification -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certification</label>
                        <input type="text" name="certification" value="{{ old('certification', $user->farmer->certification ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Organic Certification -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Organic Certification</label>
                        <input type="text" name="organic_certification" value="{{ old('organic_certification', $user->farmer->organic_certification ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Certification Expiry -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certification Expiry Date</label>
                        <input type="date" name="certification_expiry_date" value="{{ old('certification_expiry_date', $user->farmer->certification_expiry_date ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Food Safety Certification -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Food Safety Certification</label>
                        <input type="text" name="food_safety_certification" value="{{ old('food_safety_certification', $user->farmer->food_safety_certification ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- GMP Certified -->
                    <div class="flex items-center">
                        <input type="checkbox" name="gmp_certified" value="1" {{ old('gmp_certified', $user->farmer->gmp_certified ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">GMP Certified (Good Manufacturing Practice)</label>
                    </div>

                    <!-- Halal Certified -->
                    <div class="flex items-center">
                        <input type="checkbox" name="halal_certified" value="1" {{ old('halal_certified', $user->farmer->halal_certified ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Halal Certified</label>
                    </div>
                </div>
            </div>

            <!-- PAYMENT INFORMATION TAB -->
            <div id="content-payment" class="tab-content p-6 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <p class="text-sm text-gray-600 mb-4">This information will be used for payouts</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-900 mb-3">Bank Account</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $user->farmer->bank_name ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $user->farmer->bank_account_name ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->farmer->bank_account_number ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <h4 class="font-medium text-gray-900 mb-3">Mobile Wallet (Optional)</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provider</label>
                        <input type="text" name="mobile_wallet_provider" value="{{ old('mobile_wallet_provider', $user->farmer->mobile_wallet_provider ?? '') }}" 
                               placeholder="e.g., GCash, PayMaya"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Wallet Number</label>
                        <input type="text" name="mobile_wallet_number" value="{{ old('mobile_wallet_number', $user->farmer->mobile_wallet_number ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- OPERATIONAL TAB -->
            <div id="content-operational" class="tab-content p-6 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Operational Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 flex items-center">
                        <input type="checkbox" name="accepting_orders" value="1" {{ old('accepting_orders', $user->farmer->accepting_orders ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Currently Accepting Orders</label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Operation Start Time</label>
                        <input type="time" name="operation_start_time" value="{{ old('operation_start_time', $user->farmer->operation_start_time ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Operation End Time</label>
                        <input type="time" name="operation_end_time" value="{{ old('operation_end_time', $user->farmer->operation_end_time ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Operation Days</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $selectedDays = old('operation_days', $user->farmer->operation_days ?? []);
                            @endphp
                            @foreach($days as $day)
                            <label class="flex items-center">
                                <input type="checkbox" name="operation_days[]" value="{{ $day }}" 
                                       {{ in_array($day, $selectedDays) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $day }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-end gap-3">
                <a href="{{ route('farmer.profile') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-green-500', 'text-green-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-green-500', 'text-green-600');
}
</script>
@endsection
