@extends('layouts.farmers_page')

@section('title', 'Farmer Profile • AgriConnect')

@section('content')
<div class="shadow-sm  ml-64">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Your Profile</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your account information</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" 
                             alt="Profile" class="w-24 h-24 rounded-full mx-auto object-cover">
                        <h3 class="text-lg font-medium text-gray-900 mt-4">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                        <p class="text-gray-500 text-sm">Farmer</p>
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

                        @if(Auth::user()->farmer)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Farmer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Farm Name</label>
                                    <p class="text-gray-900">{{ Auth::user()->farmer->farm_name ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Farm Size</label>
                                    <p class="text-gray-900">{{ Auth::user()->farmer->farm_size ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                                    <p class="text-gray-900">{{ Auth::user()->farmer->product_type ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
                                    <p class="text-gray-900">{{ Auth::user()->farmer->experience_years ?? 'Not provided' }} years</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Farm Address</label>
                                    <p class="text-gray-900">{{ Auth::user()->address ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="flex justify-end">
                            <a href="{{ route('farmer.dashboard') }}" 
                               class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection