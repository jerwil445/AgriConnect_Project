<!-- Transaction Header -->
<div class="p-4 border-b border-gray-200 bg-white">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                {{ $transaction->product->product_name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                with 
                @if(Auth::id() == $transaction->buyer_id)
                    <span class="font-medium">{{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}</span>
                @else
                    <span class="font-medium">{{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}</span>
                @endif
            </p>
        </div>
        <!-- <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
            #{{ $transaction->id }}
        </span> -->
    </div>
</div>

<!-- Messages Container -->
<div id="messages-container" class="flex-1 overflow-y-auto p-4 bg-gray-50">
    @if($messages->count() > 0)
        @foreach($messages as $message)
            <div class="mb-4 {{ $message->sender_id == Auth::id() ? 'text-right' : 'text-left' }}">
                <div class="inline-block max-w-xs md:max-w-md {{ $message->sender_id == Auth::id() ? 'bg-green-600 text-white rounded-l-xl rounded-tr-xl rounded-br-xl' : 'bg-white border border-gray-200 rounded-r-xl rounded-tl-xl rounded-bl-xl' }} px-4 py-3 shadow-sm">
                    <p class="text-sm whitespace-pre-line">{{ $message->message }}</p>
                    <p class="text-xs mt-1 {{ $message->sender_id == Auth::id() ? 'text-green-100' : 'text-gray-500' }}">
                        {{ $message->sender->first_name }} {{ $message->sender->last_name }} • {{ $message->created_at->format('M d, Y H:i') }}
                    </p>
                </div>
                @if($message->sender_id == Auth::id())
                    <div class="text-xs text-gray-400 mt-1 mr-1 text-right">
                        @if($message->is_read)
                            <span class="text-green-500">✓✓</span> Read
                        @else
                            <span class="text-gray-400">✓</span> Sent
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="text-center py-8 text-gray-500">
            <p>No messages yet. Start the conversation!</p>
        </div>
    @endif
</div>

<!-- Message Input -->
<form id="message-form" class="p-4 border-t border-gray-200 bg-white" data-transaction-id="{{ $transaction->id }}">
    @csrf
    <div class="flex items-center justify-between space-x-3 items-end">
        <div class="flex-1">
            <textarea id="message-input" name="message" placeholder="Type your message..." 
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none" 
                      rows="2"></textarea>
        </div>
        <button type="submit" 
                class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg px-6 py-3 transition-colors duration-300 h-full">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</form>