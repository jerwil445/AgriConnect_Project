<!-- Transaction Header -->
<div class="p-5 border-b border-gray-100 bg-white/80 backdrop-blur-md sticky top-0 z-10">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                <i class="fas fa-shopping-basket text-xs"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-gray-900 tracking-tight">
                    {{ $transaction->product->product_name }}
                </h2>
                <div class="product-display-name hidden">{{ $transaction->product->product_name }}</div>
                <div class="product-variety hidden">{{ $transaction->product->variety_size }}</div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    with 
                    @if(Auth::id() == $transaction->buyer_id)
                        <span class="text-green-600">{{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}</span>
                    @else
                        <span class="text-green-600">{{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Messages Container -->
<div id="messages-container" class="flex-1 overflow-y-auto p-6 bg-gray-50/30">
    @if($messages->count() > 0)
        @foreach($messages as $message)
            <div class="mb-6 {{ $message->sender_id == Auth::id() ? 'flex justify-end' : 'flex justify-start' }}">
                <div class="max-w-[80%] md:max-w-[70%]">
                    <div class="px-5 py-3.5 shadow-sm {{ $message->sender_id == Auth::id() ? 'bg-green-600 text-white rounded-2xl rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-none' }}">
                        <p class="text-sm leading-relaxed whitespace-pre-line">{{ $message->message }}</p>
                    </div>
                    <div class="flex items-center mt-2 px-1 {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                            {{ $message->created_at->format('g:i A') }}
                        </p>
                        @if($message->sender_id == Auth::id())
                            <div class="ml-2 flex items-center">
                                @if($message->is_read)
                                    <i class="fas fa-check-double text-[8px] text-green-500"></i>
                                @else
                                    <i class="fas fa-check text-[8px] text-gray-300"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="flex flex-col items-center justify-center h-full text-gray-400">
            <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center mb-4 shadow-sm border border-gray-50">
                <i class="fas fa-paper-plane text-gray-200"></i>
            </div>
            <p class="text-xs font-bold uppercase tracking-widest">Start the conversation</p>
        </div>
    @endif
</div>

<!-- Message Input -->
<form id="message-form" class="p-6 border-t border-gray-50 bg-white" data-transaction-id="{{ $transaction->id }}">
    @csrf
    <div class="flex items-end space-x-4">
        <div class="flex-1 relative">
            <textarea id="message-input" name="message" placeholder="Type your message..." 
                      class="w-full border border-gray-100 bg-gray-50/50 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all resize-none text-sm placeholder:text-gray-400" 
                      rows="1" style="min-height: 52px; max-height: 150px;"></textarea>
        </div>
        <button type="submit" 
                class="bg-green-600 hover:bg-green-700 text-white w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-300 shadow-lg shadow-green-200 active:scale-95 flex-shrink-0">
            <i class="fas fa-paper-plane text-lg"></i>
        </button>
    </div>
</form>
