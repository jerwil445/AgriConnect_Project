@extends('layouts.admin_page')

@section('title', 'Verification Queue • AgriConnect Admin')

@section('content')
<div class="ml-64 p-12 max-w-7xl mx-auto">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Trust & Verification Audit</h1>
            <p class="text-gray-500 font-medium mt-1">Review professional credentials submitted by farmers and buyers.</p>
        </div>
        
        <div class="flex items-center gap-2 bg-white p-1 rounded-2xl border border-gray-100 shadow-sm">
            <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" 
               class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $status === 'pending' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-400 hover:text-gray-900' }}">
                Pending
            </a>
            <a href="{{ route('admin.verifications.index', ['status' => 'approved']) }}" 
               class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'text-gray-400 hover:text-gray-900' }}">
                Approved
            </a>
            <a href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}" 
               class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $status === 'rejected' ? 'bg-red-600 text-white shadow-lg shadow-red-100' : 'text-gray-400 hover:text-gray-900' }}">
                Rejected
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 animate-fade-in-down">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <i class="fas fa-check text-xs"></i>
            </div>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Producer/Buyer</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Document Details</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Submitted</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($verifications as $v)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $v->user->profile_picture ? asset('storage/' . $v->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . $v->user->first_name . '+' . $v->user->last_name }}" 
                                         class="w-10 h-10 rounded-xl object-cover border border-gray-100">
                                    <div>
                                        <div class="font-black text-gray-900 text-sm tracking-tight">{{ $v->user->first_name }} {{ $v->user->last_name }}</div>
                                        <div class="text-[10px] font-black {{ $v->user->role === 'farmer' ? 'text-emerald-500' : 'text-indigo-500' }} uppercase tracking-widest mt-0.5">{{ $v->user->role }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-gray-900 text-sm">{{ $v->document_type }}</div>
                                <a href="{{ asset('storage/' . $v->file_path) }}" target="_blank" class="text-[10px] font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-widest flex items-center gap-1 mt-1 group-hover:translate-x-1 transition-transform">
                                    <i class="fas fa-external-link-alt text-[8px]"></i>
                                    View Document
                                </a>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-gray-900">{{ $v->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] font-medium text-gray-400 mt-0.5">{{ $v->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest 
                                    {{ $v->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 
                                       ($v->status === 'rejected' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                                    {{ $v->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if($v->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.verifications.approve', $v) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center group/btn shadow-sm" title="Approve">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                        <button type="button" onclick="openRejectModal({{ $v->id }})" class="w-10 h-10 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center group/btn shadow-sm" title="Reject">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Audited</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 rounded-[2rem] bg-gray-50 flex items-center justify-center mx-auto mb-4 border border-dashed border-gray-200">
                                    <i class="fas fa-folder-open text-gray-300 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-900 tracking-tight">Audit Queue Empty</h3>
                                <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest mt-1">No {{ $status }} verifications found at this time.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($verifications->hasPages())
            <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50">
                {{ $verifications->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden animate-zoom-in">
        <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-red-50/10">
            <h3 class="font-black text-gray-900 tracking-tight uppercase text-xs tracking-widest">Reject Documentation</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-900 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="rejectForm" method="POST" class="p-10 space-y-6">
            @csrf
            <div class="space-y-3">
                <label for="rejection_reason" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Reason for Rejection</label>
                <textarea name="rejection_reason" id="rejection_reason" required rows="4" placeholder="Specify why the document was rejected (e.g., Expired, Blurred, Invalid Entity)..." 
                    class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-red-50 transition-all border outline-none min-h-[120px] text-sm"></textarea>
            </div>
            <div class="flex gap-4">
                <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-50 text-gray-400 px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-gray-100 hover:text-gray-900 transition-all">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-red-700 transition-all shadow-lg shadow-red-100">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/verifications/${id}/reject`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close on click outside
    window.onclick = function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target == modal) {
            closeRejectModal();
        }
    }
</script>

<style>
    @keyframes zoom-in {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    .animate-zoom-in { animation: zoom-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    
    .animate-fade-in-down {
        animation: fadeInDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
