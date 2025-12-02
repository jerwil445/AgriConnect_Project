@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6" style="overflow: visible;">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
                    <a href="{{ route('admin.users.create') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add User
                    </a>
                </div>

                <!-- Search and Entries Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <!-- Show Entries -->
                    <div class="relative inline-block text-left">
                        <div>
                            <button type="button" 
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                    id="per-page-menu-button"
                                    aria-expanded="false" 
                                    aria-haspopup="true"
                                    onclick="togglePerPageDropdown()">
                                Show: {{ request('per_page', 10) }} entries
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div id="per-page-dropdown" 
                             class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                             style="position: absolute; z-index: 9999;">
                            <div class="py-1" role="none">
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 10 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    10 entries
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 25, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 25 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    25 entries
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 50 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    50 entries
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => 100, 'page' => 1]) }}" 
                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('per_page') == 100 ? 'bg-blue-50 font-semibold' : '' }}" 
                                   role="menuitem">
                                    100 entries
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Dropdowns -->
                    <div class="flex items-center space-x-4">
                        <!-- Role Filter -->
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" 
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                        id="role-filter-menu-button"
                                        aria-expanded="false" 
                                        aria-haspopup="true"
                                        onclick="toggleRoleFilterDropdown()">
                                    Role: {{ request('role') ? ucfirst(request('role')) : 'All Roles' }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div id="role-filter-dropdown" 
                                 class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="position: absolute; z-index: 9999;">
                                <div class="py-1" role="none">
                                    <a href="{{ request()->fullUrlWithoutQuery(['role', 'page']) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ !request('role') ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        All Roles
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['role' => 'admin', 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('role') == 'admin' ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        Admin
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['role' => 'farmer', 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('role') == 'farmer' ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        Farmer
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['role' => 'buyer', 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('role') == 'buyer' ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        Buyer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- KYC Status Filter -->
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" 
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                        id="kyc-status-filter-menu-button"
                                        aria-expanded="false" 
                                        aria-haspopup="true"
                                        onclick="toggleKycStatusFilterDropdown()">
                                    KYC Status: {{ request('kyc_status') ? ucfirst(request('kyc_status')) : 'All Statuses' }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div id="kyc-status-filter-dropdown" 
                                 class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="position: absolute; z-index: 9999;">
                                <div class="py-1" role="none">
                                    <a href="{{ request()->fullUrlWithoutQuery(['kyc_status', 'page']) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ !request('kyc_status') ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        All Statuses
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['kyc_status' => 'pending', 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('kyc_status') == 'pending' ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        Pending
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['kyc_status' => 'verified', 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('kyc_status') == 'verified' ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        Verified
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['kyc_status' => 'rejected', 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('kyc_status') == 'rejected' ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        Rejected
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center" id="search-form">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input type="hidden" name="role" value="{{ request('role') }}">
                        <input type="hidden" name="kyc_status" value="{{ request('kyc_status') }}">
                        <div class="relative">
                            <input type="text" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search users..."
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 sm:text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit"
                                class="ml-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="overflow-x-auto" style="overflow: visible;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KYC Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($user->role == 'admin') bg-purple-100 text-purple-800
                                        @elseif($user->role == 'farmer') bg-green-100 text-green-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->phone_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->kyc_status)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($user->kyc_status == 'verified') bg-green-100 text-green-800
                                        @elseif($user->kyc_status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($user->kyc_status) }}
                                    </span>
                                    @else
                                    <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Dropdown Actions -->
                                    <div class="relative inline-block text-left">
                                        <div>
                                            <button type="button" 
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                                    id="actions-menu-button-{{ $user->id }}"
                                                    aria-expanded="false" 
                                                    aria-haspopup="true"
                                                    onclick="toggleDropdown({{ $user->id }})">
                                                Actions
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="dropdown-menu-{{ $user->id }}" 
                                             class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                             role="menu" 
                                             aria-orientation="vertical" 
                                             aria-labelledby="actions-menu-button-{{ $user->id }}"
                                             style="position: absolute; z-index: 9999;">
                                            <div class="py-1" role="none">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-eye mr-2 text-blue-500"></i>View
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-edit mr-2 text-green-500"></i>Edit
                                                </a>
                                                
                                                <!-- KYC Status Update Options -->
                                                <div class="border-t border-gray-200 my-1"></div>
                                                <span class="block px-4 py-2 text-xs font-semibold text-gray-500">KYC Status</span>
                                                
                                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="kyc_status" value="pending">
                                                    <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                                                    <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                                    <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                                                    <input type="hidden" name="address" value="{{ $user->address }}">
                                                    <button type="submit" 
                                                            class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $user->kyc_status === 'pending' ? 'bg-blue-50 font-semibold' : '' }}" 
                                                            role="menuitem">
                                                        <i class="fas fa-clock mr-2 text-yellow-500"></i>Pending
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="kyc_status" value="rejected">
                                                    <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                                                    <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                                    <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                                                    <input type="hidden" name="address" value="{{ $user->address }}">
                                                    <button type="submit" 
                                                            class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $user->kyc_status === 'rejected' ? 'bg-red-50 font-semibold' : '' }}" 
                                                            role="menuitem">
                                                        <i class="fas fa-times-circle mr-2 text-red-500"></i>Rejected
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="kyc_status" value="verified">
                                                    <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                                                    <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                                    <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                                                    <input type="hidden" name="address" value="{{ $user->address }}">
                                                    <button type="submit" 
                                                            class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $user->kyc_status === 'verified' ? 'bg-green-50 font-semibold' : '' }}" 
                                                            role="menuitem">
                                                        <i class="fas fa-check-circle mr-2 text-green-500"></i>Verified
                                                    </button>
                                                </form>
                                                
                                                <!-- Delete Action -->
                                                <div class="border-t border-gray-200 my-1"></div>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                                      class="inline delete-form" data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100" 
                                                            role="menuitem">
                                                        <i class="fas fa-trash mr-2"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url;
    }

    // Confirm before deleting
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const userName = this.getAttribute('data-user-name');
            if (confirm(`Are you sure you want to delete user ${userName}?`)) {
                this.submit();
            }
        });
    });
    
    // Toggle dropdown visibility
    function toggleDropdown(userId) {
        const dropdown = document.getElementById('dropdown-menu-' + userId);
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id^="dropdown-menu-"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Toggle per page dropdown
    function togglePerPageDropdown() {
        const dropdown = document.getElementById('per-page-dropdown');
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Toggle role filter dropdown
    function toggleRoleFilterDropdown() {
        const dropdown = document.getElementById('role-filter-dropdown');
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Toggle KYC status filter dropdown
    function toggleKycStatusFilterDropdown() {
        const dropdown = document.getElementById('kyc-status-filter-dropdown');
        const isVisible = !dropdown.classList.contains('hidden');
        
        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    }
    
    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        if (!e.target.closest('[id$="-menu-button"]') && !e.target.closest('[id$="-dropdown"]')) {
            document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
    
    // Apply filters when dropdown values change
    function applyFilters() {
        const roleFilter = document.getElementById('role_filter').value;
        const kycStatusFilter = document.getElementById('kyc_status_filter').value;
        
        // Update hidden inputs in search form
        document.getElementById('role-hidden').value = roleFilter;
        document.getElementById('kyc-status-hidden').value = kycStatusFilter;
        
        // Submit the search form
        document.getElementById('search-form').submit();
    }
</script>
@endsection