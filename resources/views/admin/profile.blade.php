@extends('layouts.admin_page')

@section('title', 'Administrative Identity • AgriConnect')

@section('content')
<div class="ml-72 p-10 max-w-6xl mx-auto">
    {{-- Profile Hero Section --}}
    <div class="relative mb-8">
        {{-- Hero Background with Admin-themed Gradient --}}
        <div class="h-48 w-full bg-gradient-to-r from-gray-900 via-slate-800 to-indigo-950 rounded-t-[3rem] shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="h-full w-full grayscale contrast-125" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,80 Q25,60 50,80 T100,80 V100 H0 Z" fill="currentColor" />
                    <path d="M0,90 Q30,70 60,90 T100,90 V100 H0 Z" fill="currentColor" opacity="0.5" />
                </svg>
            </div>
            <div class="absolute top-6 right-8 text-right">
                <span class="px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/20">
                    System Administrator
                </span>
                <p class="text-indigo-400/60 text-[9px] font-bold uppercase tracking-widest mt-2">AgriConnect Hub Console</p>
            </div>
        </div>

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-b-[3rem] shadow-2xl shadow-gray-200/50 border-x border-b border-gray-100 p-8 flex flex-col md:flex-row items-center md:items-end gap-10 -mt-16 relative z-10">
            <div class="relative group">
                <div class="w-40 h-40 rounded-[2.5rem] border-8 border-white shadow-2xl overflow-hidden bg-gray-50 relative z-10 transition-transform duration-500 group-hover:scale-105">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name . ' ' . $user->last_name) . '&background=f1f5f9&color=475569&size=256' }}" 
                         alt="Profile" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-indigo-600 rounded-2xl border-4 border-white flex items-center justify-center text-white shadow-xl z-20">
                    <i class="fas fa-shield-halved text-sm"></i>
                </div>
                <div class="absolute -inset-4 bg-indigo-500/5 rounded-[3rem] blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>
            </div>

            <div class="flex-1 text-center md:text-left">
                <div class="mb-4">
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-1">
                        <span class="w-8 h-1 bg-indigo-500 rounded-full"></span>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600">Administrative Identity</p>
                    </div>
                    <h2 class="text-4xl font-black text-gray-950 tracking-tighter leading-none">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-gray-400 font-medium mt-2 italic text-sm">Managing the ecosystem since {{ $user->created_at->format('M Y') }}</p>
                </div>
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-gray-900/20">
                        <i class="fas fa-user-shield text-[10px] text-indigo-400"></i> Root Admin
                    </span>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest border border-indigo-100">
                        <i class="fas fa-check-circle text-[10px]"></i> Active Session
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-3 min-w-[240px] bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                <div class="flex justify-between items-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <span>Registry Completion</span>
                    <span class="text-indigo-600 font-black">{{ $completeness }}%</span>
                </div>
                <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden border border-white">
                    <div class="bg-gradient-to-r from-indigo-500 to-slate-900 h-full rounded-full transition-all duration-[1500ms]" style="width: {{ $completeness }}%"></div>
                </div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter text-right">Security Compliance Level</p>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-950 leading-none tracking-tight">{{ number_format($totalUsers) }}</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Manageable Users</div>
                </div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-950 leading-none tracking-tight">{{ number_format($pendingVerifications) }}</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Pending KYC</div>
                </div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-box-open"></i>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-950 leading-none tracking-tight">{{ number_format($activeProducts) }}</div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Live Inventory</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
        {{-- Detailed Info Section --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-10 py-7 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                    <h3 class="text-xs font-black text-gray-950 uppercase tracking-[0.2em]">Personal Identity Consol</h3>
                    <div class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-300">
                        <i class="fas fa-id-card text-[10px]"></i>
                    </div>
                </div>
                <div class="p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Registry Full Name</label>
                            <p class="text-gray-950 font-bold text-lg tracking-tight">{{ $user->first_name }} {{ $user->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Sourcing Email</label>
                            <p class="text-indigo-600 font-bold text-lg tracking-tight">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Secure Line</label>
                            <p class="text-gray-950 font-bold text-lg tracking-tight">{{ $user->phone_number ?: 'Not Configured' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Access Level</label>
                            <p class="text-gray-950 font-bold text-lg tracking-tight">Privileged System Administrator</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Station Address</label>
                            <div class="flex items-start gap-4 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                <i class="fas fa-map-location-dot text-indigo-600 mt-1"></i>
                                <p class="text-gray-950 font-bold leading-relaxed">{{ $user->address ?: 'AgriConnect Operational Headquarters Not Set' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                    <div class="w-20 h-20 bg-white/10 rounded-[1.5rem] backdrop-blur-md border border-white/20 flex items-center justify-center text-3xl">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-black tracking-tight mb-2">Security Advisory</h4>
                        <p class="text-indigo-100/60 text-xs font-medium leading-relaxed">
                            Your administrative profile is shielded behind multi-layered encryption. Ensure your station contact details are updated periodically to maintain seamless ecosystem oversight.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gray-950 rounded-[2.5rem] p-10 text-center text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -left-20 -bottom-20 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                <h3 class="text-xl font-black mb-8 relative z-10 tracking-tight uppercase text-xs tracking-[0.3em] opacity-50">Admin Console</h3>
                <div class="space-y-4 relative z-10">
                    <a href="{{ route('admin.profile.edit') }}" 
                       class="flex items-center justify-center gap-3 w-full bg-white text-gray-950 px-8 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] hover:bg-indigo-50 hover:-translate-y-1 transition-all shadow-xl shadow-indigo-900/40">
                        <i class="fas fa-sliders-h text-indigo-600"></i> Modify Parameters
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center justify-center gap-3 w-full bg-white/5 text-indigo-100 px-8 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] hover:bg-white/10 transition-all border border-white/10">
                        <i class="fas fa-chart-line"></i> Operational Feed
                    </a>
                </div>
                <div class="mt-8 pt-8 border-t border-white/5">
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest leading-loose">
                        Your identity governs the ecosystem trust framework. Maintaining a complete profile enhances platform transparency.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-10 border border-indigo-100 shadow-sm relative overflow-hidden text-left">
                <div class="absolute -right-5 -bottom-5 opacity-[0.03] text-indigo-950">
                    <i class="fas fa-server text-[150px] -rotate-12"></i>
                </div>
                <h4 class="text-sm font-black text-gray-950 mb-3 italic tracking-tight">Platform Integrity</h4>
                <p class="text-[11px] font-medium text-gray-500 leading-relaxed mb-10">Administrative oversight requires consistent uptime and verified contact registry across all operational sectors.</p>
                
                <div class="space-y-3">
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center gap-4 group hover:bg-white hover:shadow-lg transition-all">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <div>
                            <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Status</div>
                            <div class="text-sm font-black text-gray-900 tracking-tight tracking-tight">Verified Authority</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection