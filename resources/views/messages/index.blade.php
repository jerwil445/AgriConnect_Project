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
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer conversation-item {{ isset($selectedTransaction) && $selectedTransaction->id == $transaction->id ? 'bg-indigo-100 border-l-4 border-l-indigo-500' : '' }}" 
                                 data-transaction-id="{{ $transaction->id }}"
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
                                        <p class="text-sm text-gray-600 truncate">
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
                                            {{ $eggTypes[$transaction->product->egg_type] ?? ucfirst(str_replace('_', ' ', $transaction->product->egg_type)) }}
                                        </p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle conversation selection
    const conversationItems = document.querySelectorAll('.conversation-item');
    conversationItems.forEach(item => {
        item.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            
            // Update active state
            conversationItems.forEach(i => i.classList.remove('bg-indigo-50'));
            this.classList.add('bg-indigo-50');
            
            // Load conversation via AJAX
            Promise.all([
                fetch(`/messages/conversation/${transactionId}`).then(response => response.text()),
                fetch(`/messages/transaction-details/${transactionId}`).then(response => response.text())
            ])
            .then(([conversationHtml, detailsHtml]) => {
                document.getElementById('conversation-container').innerHTML = conversationHtml;
                document.getElementById('transaction-details').innerHTML = detailsHtml;
                                            
                // Re-initialize message functionality
                initializeMessaging();
                
                // Initialize order modal functionality
                if (typeof initializeOrderModal === 'function') {
                    initializeOrderModal();
                }
                
                // Refresh unread message counts after opening a conversation
                refreshUnreadCounts();
            })
            .catch(error => {
                console.error('Error loading conversation:', error);
            });
        });
    });
    
    // Check if there's a selected transaction from session or URL parameter
    @if(session('selected_transaction_id'))
        const selectedTransactionId = {{ session('selected_transaction_id') }};
        const selectedConversationItem = document.querySelector(`.conversation-item[data-transaction-id="${selectedTransactionId}"]`);
        if (selectedConversationItem) {
            // Trigger click event on the selected conversation
            selectedConversationItem.click();
        }
    @elseif(isset($selectedTransaction))
        const selectedTransactionId = {{ $selectedTransaction->id }};
        const selectedConversationItem = document.querySelector(`.conversation-item[data-transaction-id="${selectedTransactionId}"]`);
        if (selectedConversationItem) {
            // Trigger click event on the selected conversation
            selectedConversationItem.click();
        }
    @else
        // Check URL parameters for transaction_id
        const urlParams = new URLSearchParams(window.location.search);
        const transactionIdParam = urlParams.get('transaction_id');
        if (transactionIdParam) {
            const selectedConversationItem = document.querySelector(`.conversation-item[data-transaction-id="${transactionIdParam}"]`);
            if (selectedConversationItem) {
                // Trigger click event on the selected conversation
                selectedConversationItem.click();
            }
        }
    @endif
    
    // Function to refresh unread message counts
    function refreshUnreadCounts() {
        // Refresh sidebar/header message count
        fetch('/messages/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update farmer sidebar count
                    const farmerMessageCount = document.querySelector('.farmer-sidebar .messages-count');
                    if (farmerMessageCount) {
                        if (data.count > 0) {
                            farmerMessageCount.textContent = data.count;
                            farmerMessageCount.classList.remove('hidden');
                        } else {
                            farmerMessageCount.classList.add('hidden');
                        }
                    }
                    
                    // Update buyer header count
                    const buyerMessageCount = document.querySelector('.buyer-header .messages-count');
                    if (buyerMessageCount) {
                        if (data.count > 0) {
                            buyerMessageCount.textContent = data.count;
                            buyerMessageCount.classList.remove('hidden');
                        } else {
                            buyerMessageCount.classList.add('hidden');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing message counts:', error);
            });
            
        // Refresh conversation list counts
        fetch('/messages/unread-count-by-conversation')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update each conversation item with unread count
                    document.querySelectorAll('.conversation-item').forEach(item => {
                        const conversationThreadId = item.getAttribute('data-conversation-thread-id');
                        const countElement = item.querySelector('.unread-count');
                        
                        if (countElement) {
                            if (data.counts[conversationThreadId] && data.counts[conversationThreadId] > 0) {
                                countElement.textContent = data.counts[conversationThreadId];
                                countElement.classList.remove('hidden');
                            } else {
                                countElement.classList.add('hidden');
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error refreshing conversation counts:', error);
            });
    }
    
    // Initialize messaging functionality
    function initializeMessaging() {
        const messageForm = document.getElementById('message-form');
        if (!messageForm) return;
        
        const messageInput = document.getElementById('message-input');
        const messagesContainer = document.getElementById('messages-container');
        
        // Scroll to bottom of messages
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Handle message submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (message === '') return;
            
            const transactionId = this.getAttribute('data-transaction-id');
            
            // Disable form while sending
            messageInput.disabled = true;
            messageForm.querySelector('button').disabled = true;
            
            // Send message via AJAX
            fetch(`/transactions/${transactionId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to container
                    const isSender = data.data.is_sender;
                    const messageElement = document.createElement('div');
                    messageElement.className = 'mb-4 ' + (isSender ? 'text-right' : 'text-left');
                    messageElement.innerHTML = `
                        <div class="inline-block max-w-xs md:max-w-md ${isSender ? 'bg-indigo-500 text-white rounded-l-lg rounded-tr-lg' : 'bg-white border border-gray-200 rounded-r-lg rounded-tl-lg'} px-4 py-2 rounded-lg">
                            <p class="text-sm whitespace-pre-line">${data.data.message}</p>
                            <p class="text-xs mt-1 ${isSender ? 'text-indigo-200' : 'text-gray-500'}">
                                ${data.data.sender} • ${data.data.created_at}
                            </p>
                        </div>
                    `;
                    messagesContainer.appendChild(messageElement);
                    
                    // Clear input and scroll to bottom
                    messageInput.value = '';
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    
                    // Refresh message counts after sending a message
                    refreshUnreadCounts();
                    
                    // If this is the first message, send an automatic follow-up
                    if (data.data.message.toLowerCase().includes('is this available?')) {
                        // Don't send another follow-up
                        return;
                    }
                    
                    // Check if this is the first message in the conversation
                    const messageCount = messagesContainer.querySelectorAll('.mb-4').length;
                    if (messageCount === 1) {
                        // Send automatic follow-up message
                        setTimeout(() => {
                            sendFollowUpMessage(transactionId);
                        }, 1000);
                    }
                } else {
                    alert('Error sending message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the message.');
            })
            .finally(() => {
                // Re-enable form
                messageInput.disabled = false;
                messageForm.querySelector('button').disabled = false;
                messageInput.focus();
            });
        });
    }
    
    // Function to send automatic follow-up message
    function sendFollowUpMessage(transactionId) {
        // Get the transaction details from the conversation header
        const conversationHeader = document.querySelector('#conversation-container .p-4.border-b');
        let productName = 'this product';
        
        if (conversationHeader) {
            const productTitle = conversationHeader.querySelector('h2.text-lg.font-bold');
            if (productTitle) {
                productName = productTitle.textContent.trim();
            }
        }
        
        // Get egg type display name
        let eggTypeName = 'Eggs';
        const eggTypes = {
            'chicken': 'Chicken Eggs',
            'duck': 'Duck Eggs',
            'quail': 'Quail Eggs',
            'native_chicken': 'Native Chicken Eggs',
            'brown': 'Brown Eggs',
            'white': 'White Eggs'
        };
        
        if (conversationHeader) {
            const productElement = conversationHeader.querySelector('.product-egg-type');
            if (productElement) {
                eggTypeName = productElement.textContent.trim();
            }
        }
        
        // Extract quantity and price from the transaction details
        let quantity = '';
        let unit = '';
        let price = '';
        
        // Try to get actual values from the transaction details panel if available
        const transactionDetails = document.getElementById('transaction-details');
        if (transactionDetails) {
            const quantityElement = transactionDetails.querySelector('.quantity-value');
            const priceElement = transactionDetails.querySelector('.price-value');
            if (quantityElement) {
                const quantityText = quantityElement.textContent.trim();
                const quantityParts = quantityText.split(' ');
                if (quantityParts.length >= 2) {
                    quantity = quantityParts[0];
                    unit = quantityParts.slice(1).join(' ');
                }
            }
            if (priceElement) {
                price = priceElement.textContent.replace('₱', '').trim();
            }
        }
        
        // Build message text with available information
        let messageLines = ['Is this available?'];
        if (eggTypeName && eggTypeName !== 'Eggs') {
            messageLines.push(`Egg Type: ${eggTypeName}`);
        }
        if (quantity && unit) {
            messageLines.push(`Quantity: ${quantity} ${unit}`);
        }
        if (price && unit) {
            messageLines.push(`Price: ₱${price}/${unit}`);
        }
        
        const messageText = messageLines.join('\n');
        
        fetch(`/transactions/${transactionId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: messageText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add follow-up message to container
                const messagesContainer = document.getElementById('messages-container');
                if (messagesContainer) {
                    const isSender = data.data.is_sender;
                    const messageElement = document.createElement('div');
                    messageElement.className = 'mb-4 ' + (isSender ? 'text-right' : 'text-left');
                    messageElement.innerHTML = `
                        <div class="inline-block max-w-xs md:max-w-md ${isSender ? 'bg-indigo-500 text-white rounded-l-lg rounded-tr-lg' : 'bg-white border border-gray-200 rounded-r-lg rounded-tl-lg'} px-4 py-2 rounded-lg">
                            <p class="text-sm whitespace-pre-line">${data.data.message}</p>
                            <p class="text-xs mt-1 ${isSender ? 'text-indigo-200' : 'text-gray-500'}">
                                ${data.data.sender} • ${data.data.created_at}
                            </p>
                        </div>
                    `;
                    messagesContainer.appendChild(messageElement);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }
        })
        .catch(error => {
            console.error('Error sending follow-up message:', error);
        });
    }
    
    // Initialize messaging if there's already a conversation loaded
    initializeMessaging();
    
    // Auto-resize textarea
    document.addEventListener('input', function(e) {
        if (e.target.id === 'message-input') {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight > 100 ? 100 : e.target.scrollHeight) + 'px';
        }
    });
    
    // Periodically refresh message counts (every 30 seconds)
    setInterval(refreshUnreadCounts, 30000);
});
</script>
@endsection
