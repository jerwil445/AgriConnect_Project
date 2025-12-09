@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6" style="overflow: visible;">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Demand Management</h2>
                    <!-- Add Demand button could go here if needed -->
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
                        <!-- Buyer Filter -->
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" 
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                        id="buyer-filter-menu-button"
                                        aria-expanded="false" 
                                        aria-haspopup="true"
                                        onclick="toggleBuyerFilterDropdown()">
                                    Buyer: {{ request('buyer') ? 'Selected' : 'All Buyers' }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div id="buyer-filter-dropdown" 
                                 class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="position: absolute; z-index: 9999;">
                                <div class="py-1" role="none">
                                    <a href="{{ request()->fullUrlWithoutQuery(['buyer', 'page']) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ !request('buyer') ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        All Buyers
                                    </a>
                                    @foreach($buyers as $buyer)
                                    <a href="{{ request()->fullUrlWithQuery(['buyer' => $buyer->id, 'page' => 1]) }}" 
                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 {{ request('buyer') == $buyer->id ? 'bg-blue-50 font-semibold' : '' }}" 
                                       role="menuitem">
                                        {{ $buyer->first_name }} {{ $buyer->last_name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('admin.demands.index') }}" class="flex items-center" id="search-form">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input type="hidden" name="buyer" value="{{ request('buyer') }}">
                        <div class="relative">
                            <input type="text" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search demands..."
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

                <!-- Demands Table -->
                <div class="overflow-x-auto" style="overflow: visible;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demand ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matches</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted On</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($demands as $demand)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $demand->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $demand->egg_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $demand->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $demand->buyer->first_name }} {{ $demand->buyer->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $demand->matches->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $demand->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Dropdown Actions -->
                                    <div class="relative inline-block text-left">
                                        <div>
                                            <button type="button" 
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                                    id="actions-menu-button-{{ $demand->id }}"
                                                    aria-expanded="false" 
                                                    aria-haspopup="true"
                                                    onclick="toggleDropdown({{ $demand->id }})">
                                                Actions
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="dropdown-menu-{{ $demand->id }}" 
                                             class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                             role="menu" 
                                             aria-orientation="vertical" 
                                             aria-labelledby="actions-menu-button-{{ $demand->id }}"
                                             style="position: absolute; z-index: 9999;">
                                            <div class="py-1" role="none">
                                                <a href="{{ route('admin.demands.view', $demand) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-eye mr-2 text-blue-500"></i>View Details
                                                </a>
                                                <a href="{{ route('admin.demands.edit', $demand) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-edit mr-2 text-green-500"></i>Edit
                                                </a>
                                                
                                                <!-- Audit Matches -->
                                                <div class="border-t border-gray-200 my-1"></div>
                                                <a href="{{ route('admin.demands.audit', $demand) }}" 
                                                   class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" 
                                                   role="menuitem">
                                                    <i class="fas fa-clipboard-list mr-2 text-purple-500"></i>Audit Matches
                                                </a>
                                                
                                                <!-- Delete Action -->
                                                <div class="border-t border-gray-200 my-1"></div>
                                                <form action="{{ route('admin.demands.delete', $demand) }}" method="POST" 
                                                      class="inline delete-form" data-demand-name="{{ $demand->egg_type }}">
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
                                    No demands found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $demands->links() }}
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
            const demandName = this.getAttribute('data-demand-name');
            if (confirm(`Are you sure you want to delete demand for ${demandName}?`)) {
                this.submit();
            }
        });
    });
    
    // Toggle dropdown visibility
    function toggleDropdown(demandId) {
        const dropdown = document.getElementById('dropdown-menu-' + demandId);
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
    
    // Toggle buyer filter dropdown
    function toggleBuyerFilterDropdown() {
        const dropdown = document.getElementById('buyer-filter-dropdown');
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