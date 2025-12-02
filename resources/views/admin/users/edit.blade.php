@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Users
                    </a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" id="first_name" 
                                   value="{{ old('first_name', $user->first_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" id="last_name" 
                                   value="{{ old('last_name', $user->last_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (Leave blank to keep current)</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="role" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="farmer" {{ old('role', $user->role) == 'farmer' ? 'selected' : '' }}>Farmer</option>
                                <option value="buyer" {{ old('role', $user->role) == 'buyer' ? 'selected' : '' }}>Buyer</option>
                            </select>
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" 
                                   value="{{ old('phone_number', $user->phone_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="address" id="address" 
                                   value="{{ old('address', $user->address) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="kyc_status" class="block text-sm font-medium text-gray-700 mb-1">KYC Status</label>
                            <select name="kyc_status" id="kyc_status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('kyc_status', $user->kyc_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ old('kyc_status', $user->kyc_status) == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ old('kyc_status', $user->kyc_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Farmer Information -->
                    @if($user->role === 'farmer')
                    <div class="mt-6 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Farmer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="farm_name" class="block text-sm font-medium text-gray-700 mb-1">Farm Name</label>
                                <input type="text" name="farm_name" id="farm_name" 
                                       value="{{ old('farm_name', $farmer->farm_name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="farm_size" class="block text-sm font-medium text-gray-700 mb-1">Farm Size</label>
                                <input type="text" name="farm_size" id="farm_size" 
                                       value="{{ old('farm_size', $farmer->farm_size ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="product_type" class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                                <input type="text" name="product_type" id="product_type" 
                                       value="{{ old('product_type', $farmer->product_type ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-1">Experience (Years)</label>
                                <input type="number" name="experience_years" id="experience_years" 
                                       value="{{ old('experience_years', $farmer->experience_years ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="certification" class="block text-sm font-medium text-gray-700 mb-1">Certification</label>
                                <input type="text" name="certification" id="certification" 
                                       value="{{ old('certification', $farmer->certification ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="farm_address" class="block text-sm font-medium text-gray-700 mb-1">Farm Address</label>
                                <input type="text" name="farm_address" id="farm_address" 
                                       value="{{ old('farm_address', $farmer->farm_address ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Buyer Information -->
                    @if($user->role === 'buyer')
                    <div class="mt-6 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Buyer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                                <input type="text" name="company_name" id="company_name" 
                                       value="{{ old('company_name', $buyer->company_name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="business_type" class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                                <input type="text" name="business_type" id="business_type" 
                                       value="{{ old('business_type', $buyer->business_type ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="preferred_products" class="block text-sm font-medium text-gray-700 mb-1">Preferred Products</label>
                                <input type="text" name="preferred_products" id="preferred_products" 
                                       value="{{ old('preferred_products', $buyer->preferred_products ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="buyer_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" name="buyer_address" id="buyer_address" 
                                       value="{{ old('buyer_address', $buyer->address ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div>
                                <label for="verified" class="block text-sm font-medium text-gray-700 mb-1">Verified</label>
                                <select name="verified" id="verified" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="1" {{ old('verified', $buyer->verified ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('verified', $buyer->verified ?? '') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-6">
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection