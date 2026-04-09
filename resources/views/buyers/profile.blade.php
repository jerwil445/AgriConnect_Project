@extends('layouts.buyers_page')

@section('title', 'Buyer Profile • AgriConnect')

@section('content')
    <div class="container mx-auto px-4 py-10 max-w-6xl">
        {{-- Profile Hero Section --}}
        <div class="relative mb-8">
            {{-- Hero Background --}}
            <div
                class="h-48 w-full bg-gradient-to-r from-blue-600 to-indigo-700 rounded-t-3xl shadow-lg relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <polygon points="0,100 100,0 100,100" />
                    </svg>
                </div>
                <div class="absolute top-6 right-8">
                    <span
                        class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-widest border border-white/30">
                        Sourcing Partner
                    </span>
                </div>
            </div>

            {{-- Profile Header Card --}}
            <div
                class="bg-white rounded-b-3xl shadow-xl border-x border-b border-gray-100 p-8 flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16 relative z-10">
                <div class="relative">
                    <div class="w-36 h-36 rounded-3xl border-4 border-white shadow-2xl overflow-hidden bg-gray-100">
                        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}"
                            alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <div
                        class="absolute -bottom-2 -right-2 w-10 h-10 bg-blue-500 rounded-2xl border-4 border-white flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <div class="mb-2">
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ Auth::user()->first_name }}
                            {{ Auth::user()->last_name }}</h2>
                        <p class="text-gray-500 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-4">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-700 text-xs font-bold border border-green-100">
                            <i class="fas fa-shield-alt"></i> Verified Account
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-gray-50 text-gray-600 text-xs font-bold border border-gray-200">
                            <i class="fas fa-calendar-alt"></i> Joined {{ Auth::user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-3 min-w-[200px]">
                    <div
                        class="flex justify-between items-center text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">
                        <span>Profile Completeness</span>
                        <span class="text-blue-600 font-black">{{ $completeness }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden border border-gray-100">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-[1500ms]"
                            style="width: {{ $completeness }}%"></div>
                    </div>
                    @if ($completeness < 100)
                        <p class="text-[10px] text-gray-400 font-medium italic text-right">Add missing info to reach 100%</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900 leading-none">{{ $totalDemands }}</div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Total Demands</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900 leading-none">{{ $activeDemands }}</div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Active Listings</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900 leading-none">{{ $totalOrders }}</div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Successful Orders</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Detailed Info Section --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">Enterprise Identity</h3>
                        <div
                            class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-300">
                            <i class="fas fa-building text-xs"></i>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Company
                                    Entity</label>
                                <p class="text-gray-900 font-bold bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">
                                    {{ Auth::user()->buyer->company_name ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Business
                                    Sector</label>
                                <p class="text-gray-900 font-bold bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">
                                    {{ Auth::user()->buyer->business_type ?? 'Not specified' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Sourcing
                                    Interests</label>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $interests = explode(',', Auth::user()->buyer->preferred_products ?? '');
                                    @endphp
                                    @forelse($interests as $interest)
                                        @if(trim($interest))
                                            <span
                                                class="px-4 py-2 bg-blue-50 text-blue-700 rounded-xl font-bold text-sm border border-blue-100">{{ trim($interest) }}</span>
                                        @endif
                                    @empty
                                        <p
                                            class="text-gray-400 italic font-medium p-4 bg-gray-50 w-full rounded-xl border border-dashed border-gray-200">
                                            No interests specified yet.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Operational
                                    Hub</label>
                                <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <i class="fas fa-map-marker-alt text-blue-500 mt-1"></i>
                                    <p class="text-gray-900 font-bold leading-relaxed">
                                        {{ Auth::user()->buyer->address ?? 'No address registered' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">Personal Contact</h3>
                        <div
                            class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-300">
                            <i class="fas fa-user-shield text-xs"></i>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Full
                                    Name</label>
                                <p class="text-gray-900 font-bold">{{ Auth::user()->first_name }}
                                    {{ Auth::user()->last_name }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Mobile
                                    Connectivity</label>
                                <p class="text-gray-900 font-bold">{{ Auth::user()->phone_number ?? 'No number listed' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions Card --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-gray-900 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden group">
                    <div
                        class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-black mb-6 relative z-10">Account Control</h3>
                    <div class="space-y-4 relative z-10">
                        <a href="{{ route('buyer.profile.edit') }}"
                            class="flex items-center justify-center gap-3 w-full bg-white text-gray-900 px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-500 hover:text-white transition-all hover:-translate-y-1 shadow-lg shadow-black/20">
                            <i class="fas fa-user-edit"></i> Edit Bio Info
                        </a>
                        <a href="{{ route('buyer.dashboard') }}"
                            class="flex items-center justify-center gap-3 w-full bg-white/10 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-white/20 transition-all border border-white/10">
                            <i class="fas fa-arrow-left"></i> To Marketplace
                        </a>
                    </div>
                    <div class="mt-8 pt-8 border-t border-white/5">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-loose">
                            Your profile is visible to verified farmers during the matching process. Keep it updated for
                            better trust.
                        </p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-400 to-emerald-600 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <i class="fas fa-leaf text-[120px] -rotate-12 absolute -right-5 -bottom-5"></i>
                    </div>
                    <h4 class="text-lg font-black mb-2 italic">Grow with AgriConnect</h4>
                    <p class="text-sm font-medium opacity-90 leading-relaxed mb-6 italic">The more detailed your sourcing
                        interests, the better farmers can serve you.</p>
                    <div class="bg-white/20 backdrop-blur-md rounded-2xl p-4 border border-white/30 text-center">
                        <div class="text-2xl font-black">TOP BUYER</div>
                        <div class="text-[10px] font-black uppercase tracking-tighter opacity-80 mt-1">Tier 1 Recognition
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection