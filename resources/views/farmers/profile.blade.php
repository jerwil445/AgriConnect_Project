@extends('layouts.farmers_page')

@section('title', 'Farmer Profile • AgriConnect')

@section('content')
<div class="ml-64 p-10 max-w-6xl mx-auto">
    {{-- Profile Hero Section --}}
    <div class="relative mb-8">
        {{-- Hero Background with Farm-themed Gradient --}}
        <div class="h-48 w-full bg-gradient-to-r from-emerald-600 to-green-800 rounded-t-3xl shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <svg class="h-full w-full grayscale contrast-125" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,80 Q25,60 50,80 T100,80 V100 H0 Z" fill="currentColor" />
                    <path d="M0,90 Q30,70 60,90 T100,90 V100 H0 Z" fill="currentColor" opacity="0.5" />
                </svg>
            </div>
            <div class="absolute top-6 right-8">
                <span class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-widest border border-white/30">
                    Trusted Producer
                </span>
            </div>
        </div>

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-b-3xl shadow-xl border-x border-b border-gray-100 p-8 flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16 relative z-10">
            <div class="relative">
                <div class="w-36 h-36 rounded-3xl border-4 border-white shadow-2xl overflow-hidden bg-gray-100 group">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}" 
                         alt="Profile" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 rounded-2xl border-4 border-white flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-leaf"></i>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left">
                <div class="mb-2">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                    <p class="text-emerald-600 font-bold tracking-tight">{{ Auth::user()->email }}</p>
                </div>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                        <i class="fas fa-certificate text-[10px]"></i> Standard Farmer
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-gray-50 text-gray-600 text-xs font-bold border border-gray-200">
                        <i class="fas fa-user-clock text-[10px]"></i> Active Member since {{ Auth::user()->created_at->format('M Y') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-3 min-w-[200px]">
                <div class="flex justify-between items-center text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">
                    <span>Account Maturity</span>
                    <span class="text-emerald-600 font-black">{{ $completeness }}%</span>
                </div>
                <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-emerald-500 to-green-600 h-full rounded-full transition-all duration-[1500ms]" style="width: {{ $completeness }}%"></div>
                </div>
                @if($completeness < 100)
                    <p class="text-[10px] text-gray-400 font-medium italic text-right">Boost trust by completing data</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-seedling"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900 leading-none">{{ $totalProducts }}</div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Total Products</div>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900 leading-none">{{ $activeListings }}</div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Active Store Items</div>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900 leading-none">{{ $totalSales }}</div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Total Orders Closed</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Detailed Info Section --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">Agricultural Operation</h3>
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-300">
                        <i class="fas fa-tree text-xs"></i>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Farm Name/Entity</label>
                            <p class="text-gray-900 font-bold bg-emerald-50/50 px-4 py-3 rounded-xl border border-emerald-100/50">{{ Auth::user()->farmer->farm_name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Land Area Coverage</label>
                            <p class="text-gray-900 font-bold bg-emerald-50/50 px-4 py-3 rounded-xl border border-emerald-100/50">{{ Auth::user()->farmer->farm_size ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Primary Specialization</label>
                            <p class="text-gray-900 font-bold bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">{{ Auth::user()->farmer->product_type ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Agri Experience</label>
                            <p class="text-gray-900 font-bold bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">{{ Auth::user()->farmer->experience_years ?? '0' }} Years of Expertise</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Farm Operational Hub</label>
                            <div class="flex items-start gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                                <i class="fas fa-map-marked-alt text-emerald-600 mt-1 text-lg"></i>
                                <p class="text-gray-900 font-bold leading-relaxed text-sm">{{ Auth::user()->farmer->farm_address ?? 'No physical address listed' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">Personal Connection</h3>
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-300">
                        <i class="fas fa-hard-hat text-xs"></i>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Farmer Legal Name</label>
                            <p class="text-gray-900 font-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Contact Frequency</label>
                            <p class="text-gray-900 font-bold">{{ Auth::user()->phone_number ?? 'No contact listed' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions Card --}}
        <div class="lg:col-span-1 space-y-6 text-center">
            <div class="bg-gray-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                <h3 class="text-xl font-black mb-8 relative z-10 tracking-tight">Farm Management</h3>
                <div class="space-y-4 relative z-10">
                    <a href="{{ route('farmer.profile.edit') }}" 
                       class="flex items-center justify-center gap-3 w-full bg-emerald-500 text-white px-8 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-xs hover:bg-emerald-600 hover:-translate-y-1 transition-all shadow-lg shadow-emerald-900/40">
                        <i class="fas fa-sliders-h"></i> Adjust Farm Bio
                    </a>
                    <a href="{{ route('farmer.dashboard') }}" 
                       class="flex items-center justify-center gap-3 w-full bg-white/10 text-emerald-100 px-8 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-xs hover:bg-white/20 transition-all border border-white/5">
                        <i class="fas fa-home"></i> Back Home
                    </a>
                </div>
                <div class="mt-10 pt-10 border-t border-white/5 text-left">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-loose">
                        Your profile helps buyers understand your farm's scale and experience. A complete profile yields 65% more matches.
                    </p>
                </div>
            </div>

            <div class="bg-[#f2fcf2] rounded-[2.5rem] p-10 border border-emerald-100 shadow-sm relative overflow-hidden text-left">
                <div class="absolute -right-5 -bottom-5 opacity-5">
                    <i class="fas fa-tractor text-[150px] -rotate-12"></i>
                </div>
                <h4 class="text-lg font-black text-emerald-900 mb-2 italic">Standard of Excellence</h4>
                <p class="text-xs font-bold text-emerald-700/60 leading-relaxed mb-10">Consistently updating your location and farm size ensures higher quality matches with bulk buyers.</p>
                <div class="bg-white rounded-2xl p-6 border border-emerald-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 text-xl">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Level</div>
                        <div class="text-lg font-black text-gray-900 tracking-tight">Rising Producer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
