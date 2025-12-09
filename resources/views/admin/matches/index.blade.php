@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6" style="overflow: visible;">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Match Management</h2>
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
                        <!-- Status Filter -->
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" 
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                        id="status-filter-menu-button"
                                        aria-expanded="false" 
                                        aria-haspopup="true"
                                        onclick="toggleStatusFilterDropdown()">
                                    Status: {{ request('status') ? ucfirst(request('status')) : 'All Statuses' }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div id="status-filter-dropdown" 
                                 class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="position: absolute; z-index: 9999;">
                                <div class="py-1" role="none">
                                    <a href="{{ request()->fullUrlWithoutQuery(['status', 'page']) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ !request('status') ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        All Statuses
                                    </a>
                                    @foreach($statuses as $status)
                                    <a href="{{ request()->fullUrlWithQuery(['status' => $status, 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('status') == $status ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        {{ ucfirst($status) }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('admin.matches.index') }}" class="flex items-center" id="search-form">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="relative">
                            <input type="text" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search matches..."
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

                <!-- Matches Table -->
                <div class="overflow-x-auto" style="overflow: visible;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Match ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demand</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matched Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($matches as $match)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $match->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($match->product)
                                        @php
                                            $eggTypes = [
                                                'chicken' => 'Chicken',
                                                'duck' => 'Duck',
                                                'quail' => 'Quail',
                                                'native_chicken' => 'Native Chicken',
                                                'brown' => 'Brown Egg',
                                                'white' => 'White Egg'
                                            ];
                                        @endphp
                                        {{ $eggTypes[$match->product->egg_type] ?? ucfirst(str_replace('_', ' ', $match->product->egg_type)) }}
                                    @else
                                        <span class="text-red-500 italic">Product Deleted</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($match->demand)
                                        @php
                                            $eggTypes = [
                                                'chicken' => 'Chicken',
                                                'duck' => 'Duck',
                                                'quail' => 'Quail',
                                                'native_chicken' => 'Native Chicken',
                                                'brown' => 'Brown Egg',
                                                'white' => 'White Egg'
                                            ];
                                        @endphp
                                        {{ $eggTypes[$match->demand->egg_type] ?? ucfirst(str_replace('_', ' ', $match->demand->egg_type)) }}
                                    @else
                                        <span class="text-red-500 italic">Demand Deleted</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($match->product && $match->product->farmer && $match->product->farmer->user)
                                        {{ $match->product->farmer->user->first_name }} {{ $match->product->farmer->user->last_name }}
                                    @else
                                        <span class="text-gray-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($match->demand && $match->demand->buyer)
                                        {{ $match->demand->buyer->first_name }} {{ $match->demand->buyer->last_name }}
                                    @else
                                        <span class="text-gray-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($match->status == 'Accepted') bg-green-100 text-green-800
                                        @elseif($match->status == 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($match->status == 'Rejected') bg-red-100 text-red-800
                                        @elseif($match->status == 'Transaction Started') bg-indigo-100 text-indigo-800
                                        @elseif($match->status == 'Ordered') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($match->status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $match->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Dropdown Actions -->
                                    <div class="relative inline-block text-left">
                                        <div>
                                            <button type="button" 
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                                    id="actions-menu-button-{{ $match->id }}"
                                                    aria-expanded="false" 
                                                    aria-haspopup="true"
                                                    onclick="toggleDropdown({{ $match->id }})">
                                                Actions
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="dropdown-menu-{{ $match->id }}" 
                                             class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                             role="menu" 
                                             aria-orientation="vertical" 
                                             aria-labelledby="actions-menu-button-{{ $match->id }}"
                                             style="position: absolute; z-index: 9999;">
                                            <div class="py-1" role="none">
                                                <a href="{{ route('admin.matches.view', $match) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-eye mr-2 text-blue-500"></i>View Details
                                                </a>
                                                
                                                <!-- Delete Action -->
                                                <div class="border-t border-gray-200 my-1"></div>
                                                <form action="{{ route('admin.matches.delete', $match) }}" method="POST" 
                                                      class="inline delete-form" data-match-id="{{ $match->id }}">
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
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No matches found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $matches->links() }}
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Confirm before deleting
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const matchId = this.getAttribute('data-match-id');
            if (confirm(`Are you sure you want to delete match #${matchId}?`)) {
                this.submit();
            }
        });
    });
    
    // Toggle dropdown visibility
    function toggleDropdown(matchId) {
        const dropdown = document.getElementById('dropdown-menu-' + matchId);
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
    
    // Toggle status filter dropdown
    function toggleStatusFilterDropdown() {
        const dropdown = document.getElementById('status-filter-dropdown');
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
</script>
@endsection