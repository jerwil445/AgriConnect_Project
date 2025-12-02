@extends('layouts.buyers_page')

@section('title', 'Buyer Profile • AgriConnect')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Your Profile</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your account information</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}" 
                             alt="Profile" class="w-24 h-24 rounded-full mx-auto object-cover">
                        <h3 class="text-lg font-medium text-gray-900 mt-4">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                        <p class="text-gray-500 text-sm">Buyer</p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <p class="text-gray-900">{{ Auth::user()->first_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <p class="text-gray-900">{{ Auth::user()->last_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <p class="text-gray-900">{{ Auth::user()->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <p class="text-gray-900">{{ Auth::user()->phone_number ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->buyer)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Buyer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                                    <p class="text-gray-900">{{ Auth::user()->buyer->company_name ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                                    <p class="text-gray-900">{{ Auth::user()->buyer->business_type ?? 'Not provided' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Products</label>
                                    <p class="text-gray-900">{{ Auth::user()->buyer->preferred_products ?? 'Not provided' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <p class="text-gray-900">{{ Auth::user()->buyer->address ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('buyer.dashboard') }}" 
                               class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Back to Dashboard
                            </a>
                            <a href="{{ route('buyer.profile.edit') }}" 
                               class="rounded-lg border border-transparent bg-green-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection