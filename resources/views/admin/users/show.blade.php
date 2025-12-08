@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">User Details</h2>
                    <div>
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md mr-2">
                            Edit User
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                            Back to Users
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">First Name:</span>
                                <span class="text-gray-900">{{ $user->first_name }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">Last Name:</span>
                                <span class="text-gray-900">{{ $user->last_name }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">Email:</span>
                                <span class="text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">Phone:</span>
                                <span class="text-gray-900">{{ $user->phone_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">Address:</span>
                                <span class="text-gray-900">{{ $user->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">Role:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->role == 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role == 'farmer') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">KYC Status:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->kyc_status == 'verified') bg-green-100 text-green-800
                                    @elseif($user->kyc_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($user->kyc_status == 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->kyc_status ? ucfirst($user->kyc_status) : 'N/A' }}
                                </span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">Created At:</span>
                                <span class="text-gray-900">{{ $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>
                            @if($user->updated_at)
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-32">Updated At:</span>
                                <span class="text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Farmer Information -->
                @if($user->role === 'farmer' && isset($farmer))
                <div class="mt-6 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Farmer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Farm Name:</span>
                            <span class="text-gray-900">{{ $farmer->farm_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Farm Size:</span>
                            <span class="text-gray-900">{{ $farmer->farm_size ?? 'N/A' }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Experience:</span>
                            <span class="text-gray-900">{{ $farmer->experience_years ?? 'N/A' }} years</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Certification:</span>
                            <span class="text-gray-900">{{ $farmer->certification ?? 'N/A' }}</span>
                        </div>
                        <div class="flex md:col-span-2">
                            <span class="font-medium text-gray-700 w-32">Farm Address:</span>
                            <span class="text-gray-900">{{ $farmer->farm_address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Buyer Information -->
                @if($user->role === 'buyer' && isset($buyer))
                <div class="mt-6 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Buyer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Company Name:</span>
                            <span class="text-gray-900">{{ $buyer->company_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Business Type:</span>
                            <span class="text-gray-900">{{ $buyer->business_type ?? 'N/A' }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Preferred Products:</span>
                            <span class="text-gray-900">{{ $buyer->preferred_products ?? 'N/A' }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium text-gray-700 w-32">Verified:</span>
                            <span class="text-gray-900">{{ $buyer->verified ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="flex md:col-span-2">
                            <span class="font-medium text-gray-700 w-32">Address:</span>
                            <span class="text-gray-900">{{ $buyer->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-6">
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                          class="inline delete-form" data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                            Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Confirm before deleting
    document.querySelector('.delete-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const userName = this.getAttribute('data-user-name');
        if (confirm(`Are you sure you want to delete user ${userName}?`)) {
            this.submit();
        }
    });
</script>
@endsection