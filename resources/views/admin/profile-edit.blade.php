@extends('layouts.admin_page')

@section('title', 'Refine Administrative Parameters • AgriConnect')

@section('content')
    <div class="ml-72 p-12 max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6 text-left">
            <div>
                <h1 class="text-3xl font-black text-gray-950 tracking-tight">Administrative Parameter Refinement</h1>
                <p class="text-gray-500 font-medium mt-1">Enhance your administrative identity to ensure platform-wide transparency.
                </p>
            </div>
            <a href="{{ route('admin.profile') }}"
                class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i>
                Discard and Return
            </a>
        </div>

        @if(session('success'))
            <div
                class="mb-8 p-4 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center gap-3 text-indigo-700 animate-fade-in-down">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 text-left">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Avatar Selection --}}
                <div class="lg:col-span-1 text-center">
                    <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm group">
                        <div class="relative inline-block mb-8">
                            <div
                                class="p-1 rounded-[3.2rem] border-2 border-dashed border-gray-200 group-hover:border-indigo-400 transition-colors">
                                <img id="profile-preview"
                                    src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name . ' ' . $user->last_name) . '&background=f1f5f9&color=475569&size=256' }}"
                                    alt="Profile"
                                    class="w-44 h-44 rounded-[3rem] object-cover shadow-2xl group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <label for="profile_picture"
                                class="absolute -bottom-2 -right-2 bg-gray-950 text-white w-12 h-12 rounded-[1.2rem] border-4 border-white flex items-center justify-center cursor-pointer hover:bg-slate-800 hover:scale-110 transition-all shadow-lg">
                                <i class="fas fa-camera text-sm"></i>
                            </label>
                            <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*">
                        </div>
                        <h3 class="text-xl font-black text-gray-950 tracking-tight leading-none">
                            {{ $user->first_name }} {{ $user->last_name }}</h3>
                        <div
                            class="mt-3 text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-4 py-1.5 rounded-full inline-block border border-indigo-100">
                            Registry Administrator</div>

                        <div class="mt-10 pt-10 border-t border-gray-50 space-y-4">
                            <button type="submit"
                                class="w-full bg-gray-950 text-white px-8 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] hover:bg-slate-800 transition-all shadow-xl shadow-gray-200 active:scale-95 flex items-center justify-center gap-3">
                                <i class="fas fa-save opacity-50"></i> Commit Registry Updates
                            </button>
                            <p class="text-[9px] text-gray-400 font-bold px-4 leading-relaxed uppercase tracking-widest">
                                Your administrative identity will be synchronized across the ecosystem instantly.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right: Form Data --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Personal Identity Card --}}
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-fingerprint text-xs"></i>
                                </div>
                                <h3 class="font-black text-gray-950 tracking-tight uppercase text-xs tracking-[0.2em]">
                                    Registry Information</h3>
                            </div>
                        </div>
                        <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label for="first_name"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">First
                                    Identification</label>
                                <input type="text" name="first_name" id="first_name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-6 py-4 font-bold text-gray-950 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all border outline-none">
                                @error('first_name')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label for="last_name"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Family/Legal Name</label>
                                <input type="text" name="last_name" id="last_name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-6 py-4 font-bold text-gray-950 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all border outline-none">
                                @error('last_name')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label for="email"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Registry Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-6 py-4 font-bold text-gray-950 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all border outline-none">
                                @error('email')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label for="phone_number"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Secure Terminal Line</label>
                                <input type="text" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-6 py-4 font-bold text-gray-950 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all border outline-none">
                                @error('phone_number')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2 space-y-3">
                                <label for="address"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Administrative Hub Address</label>
                                <textarea name="address" id="address" rows="3"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-6 py-4 font-bold text-gray-950 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all border outline-none min-h-[100px] resize-none">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="text-[10px] text-red-500 font-bold mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Security Credentials Card --}}
                    <div class="bg-indigo-950 rounded-[2.5rem] border border-gray-800 shadow-2xl shadow-indigo-900/20 overflow-hidden relative group">
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                        
                        <div class="px-10 py-8 border-b border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-4 relative z-10">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-white/5 text-indigo-400 flex items-center justify-center border border-white/10">
                                    <i class="fas fa-lock text-xs"></i>
                                </div>
                                <h3 class="font-black text-white tracking-tight uppercase text-xs tracking-[0.2em]">
                                    Credential Rotation</h3>
                            </div>
                        </div>
                        <div class="p-10 space-y-8 relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label for="password"
                                        class="text-[10px] font-black text-indigo-300/50 uppercase tracking-widest px-2">New Security Key</label>
                                    <input type="password" name="password" id="password" placeholder="Leave empty to maintain"
                                        class="w-full bg-white/5 border-white/10 rounded-2xl px-6 py-4 font-bold text-white focus:bg-white/10 focus:ring-4 focus:ring-indigo-500/20 transition-all border outline-none placeholder:text-gray-600">
                                    @error('password')
                                        <p class="text-[10px] text-red-400 font-bold mt-1 px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-3">
                                    <label for="password_confirmation"
                                        class="text-[10px] font-black text-indigo-300/50 uppercase tracking-widest px-2">Verify Configuration</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Verify security key"
                                        class="w-full bg-white/5 border-white/10 rounded-2xl px-6 py-4 font-bold text-white focus:bg-white/10 focus:ring-4 focus:ring-indigo-500/20 transition-all border outline-none placeholder:text-gray-600">
                                </div>
                            </div>
                            <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                                <p class="text-[9px] text-indigo-200/40 font-bold uppercase tracking-[0.2em] leading-relaxed">
                                    Rotation of credentials will require re-authentication across all active administrative terminals for security persistence.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Premium Profile Preview with smooth scaling
        document.getElementById('profile_picture').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('profile-preview');
                reader.onload = function (e) {
                    preview.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    preview.style.transform = 'scale(0.8)';
                    preview.style.opacity = '0.5';

                    setTimeout(() => {
                        preview.src = e.target.result;
                        preview.style.transform = 'scale(1)';
                        preview.style.opacity = '1';
                    }, 300);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <style>
        .animate-fade-in-down {
            animation: fadeInDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection