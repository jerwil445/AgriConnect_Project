@extends('layouts.messages')

@section('title', 'Messages • AgriConnect')

@section('content')
<div class="h-screen flex flex-col">
    <div class="bg-white shadow-md flex-shrink-0">
        <!-- Header -->
        <div class="bg-green-700 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-white">Messages</h1>
                <span class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm">
                    {{ count($transactions ?? []) }} Conversations
                </span>
            </div>
        </div>
    </div>

    <!-- Three-column layout -->
    <div class="flex flex-1 overflow-hidden">
            <!-- Left Column - Conversations List -->
            <div class="w-1/4 border-r border-gray-200 flex flex-col">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Conversations</h2>
                </div>
                <div class="flex-1 overflow-y-auto">
                    @if(count($transactions ?? []) > 0)
                        @foreach($transactions as $transaction)
                            @php
                                // Ensure this sidebar item is highlighted if it belongs to the same thread as the selected transaction
                                $isActiveThread = isset($selectedTransaction) && $selectedTransaction->conversation_thread_id == $transaction->conversation_thread_id;
                                $displayTransactionId = $isActiveThread ? $selectedTransaction->id : $transaction->id;
                            @endphp
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer conversation-item {{ $isActiveThread ? 'bg-indigo-100 border-l-4 border-l-indigo-500' : '' }}" 
                                 data-transaction-id="{{ $displayTransactionId }}"
                                 data-conversation-thread-id="{{ $transaction->conversation_thread_id }}"
                                 data-other-party-id="{{ Auth::id() == $transaction->buyer_id ? $transaction->farmer_id : $transaction->buyer_id }}">
                                <div class="flex items-start">
                                    <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-800 font-bold">
                                            @if(Auth::id() == $transaction->buyer_id)
                                                {{ substr($transaction->farmer->first_name, 0, 1) }}
                                            @else
                                                {{ substr($transaction->buyer->first_name, 0, 1) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <div class="flex justify-between">
                                            <h3 class="font-medium text-gray-900 truncate">
                                                @if(Auth::id() == $transaction->buyer_id)
                                                    {{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}
                                                @else
                                                    {{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}
                                                @endif
                                            </h3>
                                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                                {{ $transaction->updated_at->format('M d') }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate">{{ $transaction->product->product_name }}</p>
                                        <div class="flex justify-between items-center mt-1">
                                            <!-- <span class="text-xs text-gray-500">
                                                #{{ $transaction->id }}
                                            </span> -->
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                ₱{{ number_format($transaction->total_amount, 0) }}
                                            </span>
                                            
                                            <!-- Unread message indicator -->
                                            @if(isset($unreadCounts[$transaction->conversation_thread_id]) && $unreadCounts[$transaction->conversation_thread_id] > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 unread-count">
                                                    {{ $unreadCounts[$transaction->conversation_thread_id] }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 unread-count hidden">
                                                    0
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <p>No conversations yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Middle Column - Messages Conversation -->
            <div class="w-2/4 flex flex-col" id="conversation-container">
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center p-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Select a conversation</h3>
                        <p class="mt-1 text-sm text-gray-500">Choose a conversation from the list to start messaging</p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Transaction Details -->
            <div class="w-1/4 border-l border-gray-200 flex flex-col" id="transaction-details">
                <div class="flex-1 flex items-center justify-center p-8">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Transaction Details</h3>
                        <p class="mt-1 text-sm text-gray-500">Select a conversation to view transaction details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/messages/messages-index.js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('selected_transaction_id'))
            const id = {{ session('selected_transaction_id') }};
            const item = document.querySelector(`.conversation-item[data-transaction-id="${id}"]`);
            if (item) item.click();
        @elseif(isset($selectedTransaction))
            const id = {{ $selectedTransaction->id }};
            const item = document.querySelector(`.conversation-item[data-transaction-id="${id}"]`);
            if (item) item.click();
        @else
            const urlParams = new URLSearchParams(window.location.search);
            const idParam = urlParams.get('transaction_id');
            if (idParam) {
                const item = document.querySelector(`.conversation-item[data-transaction-id="${idParam}"]`);
                if (item) item.click();
            }
        @endif
    });
</script>

<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(8px); }
    to   { opacity: 1; transform: scale(1)   translateY(0); }
}
.animate-modal-in { animation: modalIn 0.2s ease-out both; }
</style>
@endsection
