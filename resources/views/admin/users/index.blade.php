@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 pb-6 border-b border-gray-100 gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-600/20 text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">User Management</h2>
                        <p class="text-sm text-gray-500 font-medium mt-1">Configure and monitor platform participants</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Statistics Summary (Compact) -->
                    <div class="hidden lg:flex items-center gap-6 px-6 py-2.5 bg-white/50 backdrop-blur-md rounded-2xl border border-white shadow-sm mr-4">
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total Users</p>
                            <p class="text-lg font-black text-gray-800 mt-1">{{ $users->total() }}</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.users.create') }}" 
                       class="bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-400 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-md transition-all duration-200 flex items-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fas fa-user-plus"></i> Add New User
                    </a>
                </div>
            </div>

            <!-- Toolbar / Filters -->
            <div class="relative z-[60] bg-white/60 backdrop-blur-lg rounded-[2rem] border border-white shadow-sm p-6 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                <div class="flex flex-wrap items-center gap-4 z-50">
                    <!-- Per Page Select -->
                    <div class="relative group z-50">
                        <button type="button" onclick="toggleDropdownById('per-page-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Show:</span> {{ request('per_page', 10) }}
                            <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <div id="per-page-dropdown" class="hidden absolute left-0 mt-2 w-32 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            @foreach([10, 25, 50, 100] as $count)
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => $count, 'page' => 1]) }}" 
                                   class="block px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-green-50 transition-colors {{ request('per_page') == $count ? 'bg-green-50/50 text-green-700' : '' }}">
                                    {{ $count }} rows
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Role Filter -->
                    <div class="relative group z-50">
                        <button type="button" onclick="toggleDropdownById('role-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">Role:</span> {{ request('role') ? ucfirst(request('role')) : 'All' }}
                            <i class="fas fa-user-tag text-[10px] text-gray-400"></i>
                        </button>
                        <div id="role-filter-dropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <a href="{{ request()->fullUrlWithoutQuery(['role', 'page']) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-green-50">All Roles</a>
                            @foreach(['admin', 'farmer', 'buyer'] as $role)
                                <a href="{{ request()->fullUrlWithQuery(['role' => $role, 'page' => 1]) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-green-50">
                                    <span class="w-2 h-2 rounded-full inline-block mr-2 @if($role=='admin') bg-purple-500 @elseif($role=='farmer') bg-green-500 @else bg-blue-500 @endif"></span>
                                    {{ ucfirst($role) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- KYC Status -->
                    <div class="relative group">
                        <button type="button" onclick="toggleDropdownById('kyc-filter-dropdown')" 
                                class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition-colors shadow-sm">
                            <span class="text-gray-400 uppercase font-medium">KYC:</span> {{ request('kyc_status') ? ucfirst(request('kyc_status')) : 'All' }}
                            <i class="fas fa-id-card text-[10px] text-gray-400"></i>
                        </button>
                        <div id="kyc-filter-dropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <a href="{{ request()->fullUrlWithoutQuery(['kyc_status', 'page']) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-green-50">All Staruses</a>
                            @foreach(['pending', 'verified', 'rejected'] as $status)
                                <a href="{{ request()->fullUrlWithQuery(['kyc_status' => $status, 'page' => 1]) }}" class="block px-4 py-3 text-xs font-bold text-gray-700 hover:bg-green-50">
                                    <span class="w-2 h-2 rounded-full inline-block mr-2 @if($status=='pending') bg-amber-500 @elseif($status=='verified') bg-emerald-500 @else bg-red-500 @endif"></span>
                                    {{ ucfirst($status) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="relative lg:w-80 group">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <input type="hidden" name="role" value="{{ request('role') }}">
                    <input type="hidden" name="kyc_status" value="{{ request('kyc_status') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user identity..."
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all shadow-sm text-sm font-medium">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </div>
                    @if(request('search'))
                        <a href="{{ request()->fullUrlWithoutQuery(['search', 'page']) }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Data Table -->
            <div class="bg-white/40 backdrop-blur-xl border border-white rounded-[2.5rem] shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">System Identity</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Contact Channel</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Platform Role</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">KYC Auth</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($users as $user)
                                <tr class="hover:bg-white/60 transition-all group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 border border-white shadow-sm flex items-center justify-center text-gray-400 font-black text-lg group-hover:from-green-50 group-hover:to-emerald-50 group-hover:text-green-600 transition-all duration-300">
                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</div>
                                                <div class="text-[10px] font-mono text-gray-400 font-bold uppercase tracking-tighter mt-0.5">UID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-600 flex items-center gap-2 group-hover:text-gray-900 transition-colors leading-none">
                                            <i class="fas fa-envelope text-[10px] text-gray-300"></i> {{ $user->email }}
                                        </div>
                                        <div class="text-[11px] font-medium text-gray-400 mt-1.5 flex items-center gap-2">
                                            <i class="fas fa-phone text-[10px] text-gray-300"></i> {{ $user->phone_number ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full border shadow-sm
                                            @if($user->role == 'admin') bg-purple-50 text-purple-700 border-purple-100
                                            @elseif($user->role == 'farmer') bg-emerald-50 text-emerald-700 border-emerald-100
                                            @else bg-blue-50 text-blue-700 border-blue-100 @endif">
                                            <i class="fas @if($user->role=='admin') fa-user-shield @elseif($user->role=='farmer') fa-tractor @else fa-store @endif mr-1.5"></i>
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        @if($user->kyc_status)
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full border shadow-sm
                                                @if($user->kyc_status == 'verified') bg-emerald-500 text-white border-emerald-400
                                                @elseif($user->kyc_status == 'pending') bg-amber-50 text-amber-700 border-amber-100
                                                @else bg-red-50 text-red-700 border-red-100 @endif">
                                                @if($user->kyc_status == 'verified') <i class="fas fa-check-circle mr-1"></i> @endif
                                                {{ $user->kyc_status }}
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Uninitiated</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-blue-100 shadow-sm" title="View Profile">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="w-9 h-9 rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-300 flex items-center justify-center border border-green-100 shadow-sm" title="Edit Data">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            
                                            <div class="relative">
                                                <button type="button" onclick="toggleDropdownById('actions-menu-{{ $user->id }}')" 
                                                        class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all duration-300 flex items-center justify-center border border-gray-200 shadow-sm">
                                                    <i class="fas fa-ellipsis-v text-xs"></i>
                                                </button>
                                                <!-- Context Menu for Quick Actions -->
                                                <div id="actions-menu-{{ $user->id }}" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200 text-left">
                                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Quick Auth Change</p>
                                                    </div>
                                                    @foreach(['verified' => ['color' => 'green', 'icon' => 'check-circle'], 'pending' => ['color' => 'amber', 'icon' => 'clock'], 'rejected' => ['color' => 'red', 'icon' => 'times-circle']] as $status => $meta)
                                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="block">
                                                            @csrf
                                                            @method('PUT')
                                                            @foreach(['first_name', 'last_name', 'email', 'role', 'phone_number', 'address'] as $field)
                                                                <input type="hidden" name="{{ $field }}" value="{{ $user->$field }}">
                                                            @endforeach
                                                            <input type="hidden" name="kyc_status" value="{{ $status }}">
                                                            <button type="submit" class="w-full text-left px-4 py-2.5 text-[11px] font-black uppercase tracking-tight text-gray-700 hover:bg-{{ $meta['color'] }}-50 hover:text-{{ $meta['color'] }}-700 transition-colors flex items-center gap-2">
                                                                <i class="fas fa-{{ $meta['icon'] }} text-{{ $meta['color'] }}-500"></i> Set {{ $status }}
                                                            </button>
                                                        </form>
                                                    @endforeach
                                                    <div class="border-t border-gray-100 my-1"></div>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form" data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left px-4 py-3 text-[11px] font-black uppercase tracking-tight text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                                                            <i class="fas fa-trash-alt"></i> Delete Account
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 mb-4 border-2 border-dashed border-gray-100">
                                                <i class="fas fa-user-slash text-3xl"></i>
                                            </div>
                                            <h4 class="text-lg font-black text-gray-300 uppercase tracking-widest">No Identities Located</h4>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">Try adjusting your search criteria or filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 relative overflow-hidden">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>
</div>

<script>
    function toggleDropdownById(id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = document.querySelectorAll('[id$="-dropdown"], [id^="actions-menu-"]');
        
        allDropdowns.forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    // Close logic
    window.addEventListener('click', function(e) {
        if (!e.target.closest('button') && !e.target.closest('form')) {
            document.querySelectorAll('[id$="-dropdown"], [id^="actions-menu-"]').forEach(d => d.classList.add('hidden'));
        }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const userName = this.getAttribute('data-user-name');
            if (confirm(`⚠️ FINAL WARNING: Permanently delete account for "${userName}"?\n\nThis will purge all associated data, farm records, and transaction history. This action IRREVERSIBLE.`)) {
                this.submit();
            }
        });
    });
</script>

<style>
/* Custom Pagination Styling Overrides */
.pagination { @apply flex items-center gap-1; }
.page-item { @apply rounded-xl border border-gray-200 bg-white text-gray-600 transition-all duration-300; }
.page-item.active { @apply bg-green-500 border-green-500 text-white shadow-md shadow-green-500/20; }
.page-link { @apply px-3.5 py-1.5 text-xs font-black block; }
</style>
@endsection