<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Product;
use App\Models\DemandMatch;
use App\Models\Transaction;
use App\Models\Message;
use App\Models\ConversationThread;
use App\Notifications\FarmerMatchNotification;
use App\Notifications\BuyerFarmerAcceptNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandMatchingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if the user has a buyer profile
        if (!Auth::user() || !Auth::user()->buyer) {
            return redirect()->route('buyer.dashboard')->with('error', 'You must have a buyer profile to view demands.');
        }
        
        // Get all demands for the authenticated buyer
        $demands = Demand::where('buyer_id', Auth::id())->with('matches.product.farmer.user')->get();
        
        return view('buyers.demands.index', compact('demands'));
    }

    /**
     * Store a newly created demand in storage.
     */
    public function store(Request $request)
    {
        // Check if the user has a buyer profile
        if (!Auth::user() || !Auth::user()->buyer) {
            // Return JSON response for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must have a buyer profile to create demands.'
                ]);
            }
            return redirect()->route('buyer.dashboard')->with('error', 'You must have a buyer profile to create demands.');
        }
        
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:today',
        ]);

        $validatedData['buyer_id'] = Auth::id();

        $demand = Demand::create($validatedData);

        // Automatically run matching engine
        $this->runMatchingEngine($demand);

        // Return JSON response for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Demand created successfully and matching process initiated.'
            ]);
        }

        return redirect()->route('demands.index')
            ->with('success', 'Demand created successfully and matching process initiated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Demand $demand)
    {
        // Check if the user has a buyer profile
        if (!Auth::user() || !Auth::user()->buyer) {
            return redirect()->route('buyer.dashboard')->with('error', 'You must have a buyer profile to view demands.');
        }
        
        // Ensure the demand belongs to the authenticated user
        if ($demand->buyer_id !== Auth::id()) {
            abort(403);
        }

        $demand->load('matches.product.farmer.user');

        return view('buyers.demands.show', compact('demand'));
    }

    /**
     * Show matches for a farmer's products
     */
    public function farmerMatches()
    {
        // Get the authenticated user's farmer profile
        $farmer = Auth::user()->farmer;
        
        if (!$farmer) {
            abort(403);
        }

        // Get all products for this farmer with their matches
        $products = $farmer->products()->with('matches.demand.buyer')->get();

        return view('farmers.matches.index', compact('products'));
    }

    /**
     * Show all matches for a specific farmer product
     */
    public function farmerProductMatches(Product $product)
    {
        // Check if the authenticated user is the owner of this product
        if (Auth::id() != $product->farmer->user_id) {
            abort(403);
        }
        
        // Load the matches with demand and buyer information
        $product->load('matches.demand.buyer');
        
        return view('farmers.matches.product_matches', compact('product'));
    }

    /**
     * Run the matching engine to find suitable products for a demand
     */
    public function runMatchingEngine(Demand $demand)
    {
        // Find products that match the demand criteria
        $matchingProducts = Product::where('product_name', 'LIKE', '%' . $demand->product_name . '%')
            ->where('quantity', '>=', $demand->quantity)
            ->where('status', 'Available')
            ->get();

        // For each matching product, create a match record
        foreach ($matchingProducts as $product) {
            // Check if a match already exists
            $existingMatch = DemandMatch::where('product_id', $product->id)
                ->where('demand_id', $demand->id)
                ->first();

            if (!$existingMatch) {
                DemandMatch::create([
                    'product_id' => $product->id,
                    'demand_id' => $demand->id,
                    'status' => 'New', // Changed from 'Pending' to 'New' for initial state
                    'matched_date' => now(),
                ]);
            }
        }
    }

    /**
     * Match a specific product with existing demands that have zero matches
     */
    public function matchNewProductWithZeroMatchDemands(Product $product)
    {
        // Find demands that match the product criteria and have zero matches
        $matchingDemands = Demand::where('product_name', 'LIKE', '%' . $product->product_name . '%')
            ->where('quantity', '<=', $product->quantity)
            ->where('status', 'Available')
            ->whereDoesntHave('matches') // Only demands with zero matches
            ->get();

        // For each matching demand, create a match record
        foreach ($matchingDemands as $demand) {
            // Check if a match already exists (shouldn't be needed but just in case)
            $existingMatch = DemandMatch::where('product_id', $product->id)
                ->where('demand_id', $demand->id)
                ->first();

            if (!$existingMatch) {
                DemandMatch::create([
                    'product_id' => $product->id,
                    'demand_id' => $demand->id,
                    'status' => 'New',
                    'matched_date' => now(),
                ]);
            }
        }
    }

    /**
     * Accept a match (by either buyer or farmer)
     */
    public function acceptMatch(DemandMatch $demandMatch)
    {
        // Load the necessary relationships
        $demandMatch->load('demand.buyer', 'product.farmer.user', 'product.images');
        
        // Check if the authenticated user is authorized to accept this match
        if (Auth::id() != $demandMatch->demand->buyer_id && Auth::id() != $demandMatch->product->farmer->user_id) {
            abort(403);
        }

        // Check if the user is a buyer accepting the match
        if (Auth::id() == $demandMatch->demand->buyer_id) {
            // Update the match status
            $demandMatch->update([
                'status' => 'Pending'
            ]);
        
            // Notify the farmer about the buyer's interest with buyer information
            $farmerUser = $demandMatch->product->farmer->user;
            if ($farmerUser) {
                // Create a more detailed message including buyer information
                $buyerName = $demandMatch->demand->buyer->first_name . ' ' . $demandMatch->demand->buyer->last_name;
                $productName = $demandMatch->product->product_name;
                $message = "{$buyerName} is interested in your product \"{$productName}\". Please review and accept the match to start negotiation.";
                
                $data = [
                'message' => $message,
                'transaction_id' => $transaction->id,
                'product_name' => $productName,
                'quantity' => $orderedQuantity,
                'total_amount' => $orderedQuantity * $transaction->final_price
            ];
            $farmerUser->notify(new FarmerMatchNotification($data));
            }
        
            // Return JSON response for AJAX requests to redirect to messages
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('buyer.messages')
                ]);
            }
            
            return redirect()->route('buyer.messages')->with('success', 'Match request sent! You can now communicate with the farmer.');
        }
        // Check if the user is a farmer accepting the match
        else if (Auth::id() == $demandMatch->product->farmer->user_id) {
            // Update the match status to Matched
            $demandMatch->update([
                'status' => 'Matched'
            ]);
        
            // Send notification to the buyer that the farmer has accepted
            $buyerUser = $demandMatch->demand->buyer;
            if ($buyerUser) {
                // Prepare notification data with product details
                $product = $demandMatch->product;
                $farmerName = $product->farmer->user->first_name . ' ' . $product->farmer->user->last_name;
                $notificationData = [
                    'message' => "{$farmerName} accepted your request for \"{$product->product_name}\". You can now negotiate.",
                    'data' => [
                        'product_name' => $product->product_name,
                        'product_id' => $product->id,
                        'quantity' => $product->quantity,
                        'unit' => $product->unit ?? 'units',
                        'price' => $product->price,
                        'harvest_date' => $product->harvest_date,
                        'status' => $product->status,
                        'farmer_name' => $product->farmer->user->first_name . ' ' . $product->farmer->user->last_name,
                        'buyer_name' => $demandMatch->demand->buyer->first_name . ' ' . $demandMatch->demand->buyer->last_name,
                        'action' => 'accepted',
                        'actor' => 'farmer',
                        'request_date' => $demandMatch->created_at->format('F d, Y'),
                        'images' => $product->images->map(function($image) {
                            return asset('storage/' . $image->image_path);
                        })->toArray(),
                        'main_image' => $product->images->count() > 0 ? asset('storage/' . $product->images->first()->image_path) : 
                                   ($product->image ? asset('storage/' . $product->image) : asset('images/placeholder.png'))
                    ]
                ];
                
                $buyerUser->notify(new BuyerFarmerAcceptNotification($notificationData));
            }
            
            return back()->with('success', 'Match accepted successfully!');
        }
    }

    /**
     * Reject a match (by either buyer or farmer)
     */
    public function rejectMatch(DemandMatch $demandMatch)
    {
        // Check if the authenticated user is authorized to reject this match
        if (Auth::id() != $demandMatch->demand->buyer_id && Auth::id() != $demandMatch->product->farmer->user_id) {
            abort(403);
        }

        $demandMatch->update([
            'status' => 'Rejected'
        ]);

        return back()->with('success', 'Match rejected successfully!');
    }

    /**
     * Remove the specified match from storage.
     */
    public function destroyMatch(DemandMatch $demandMatch)
    {
        // Check if the authenticated user is authorized to delete this match
        if (Auth::id() != $demandMatch->demand->buyer_id && Auth::id() != $demandMatch->product->farmer->user_id) {
            abort(403);
        }

        // Delete the match
        $demandMatch->delete();

        return back()->with('success', 'Match deleted successfully!');
    }

    /**
     * Get unread message count for the authenticated user
     */
    public function getUnreadMessagesCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Get unread message count for each conversation for the authenticated user
     */
    public function getUnreadMessagesCountByConversation()
    {
        $unreadCounts = Message::select('conversation_thread_id')
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->groupBy('conversation_thread_id')
            ->selectRaw('conversation_thread_id, count(*) as unread_count')
            ->get()
            ->keyBy('conversation_thread_id');

        return response()->json([
            'success' => true,
            'counts' => $unreadCounts
        ]);
    }

    /**
     * List all transactions for the authenticated user
     */
    public function listTransactions(Request $request)
    {
        // Get all transactions where the user is either the buyer or farmer
        // But only show conversations where:
        // 1. The user initiated the conversation (clicked "Message")
        // 2. OR the user has received at least one message in the conversation
        
        $userId = Auth::id();
        
        $transactions = Transaction::where(function($query) use ($userId) {
            // User is the initiator
            $query->where('initiator_id', $userId)
                  // OR user has received messages in this conversation
                  ->orWhereHas('messages', function($subQuery) use ($userId) {
                      $subQuery->where('receiver_id', $userId);
                  });
        })
        ->where(function($query) use ($userId) {
            $query->where('buyer_id', $userId)
                  ->orWhere('farmer_id', $userId);
        })
        ->with(['buyer', 'farmer', 'product', 'demand', 'conversationThread'])
        ->orderBy('updated_at', 'desc')
        ->get();

        // Check if a specific transaction was requested to be selected
        $selectedTransaction = null;
        if ($request->has('transaction_id')) {
            $transactionId = $request->get('transaction_id');
            // Find the transaction in our data
            foreach ($transactions as $transaction) {
                if ($transaction->id == $transactionId) {
                    $selectedTransaction = $transaction;
                    break;
                }
            }
        }
        // Check if a farmer was specified to start a new conversation
        elseif ($request->has('farmer_id')) {
            $farmerId = $request->get('farmer_id');
            // Check if there's already a conversation with this farmer
            $existingTransaction = Transaction::where(function($query) use ($userId, $farmerId) {
                $query->where('buyer_id', $userId)
                      ->where('farmer_id', $farmerId);
            })->orWhere(function($query) use ($userId, $farmerId) {
                $query->where('buyer_id', $farmerId)
                      ->where('farmer_id', $userId);
            })->orderBy('updated_at', 'desc')->first();
            
            if ($existingTransaction) {
                // Use existing transaction
                $selectedTransaction = $existingTransaction;
            } else {
                // Create a new conversation with the farmer
                // First, check if a conversation thread already exists between these users
                $conversationThread = ConversationThread::where(function($query) use ($userId, $farmerId) {
                    $query->where('buyer_id', min($userId, $farmerId))
                          ->where('farmer_id', max($userId, $farmerId));
                })->first();
                
                // If no conversation thread exists, create one
                if (!$conversationThread) {
                    $conversationThread = ConversationThread::create([
                        'buyer_id' => min($userId, $farmerId),
                        'farmer_id' => max($userId, $farmerId)
                    ]);
                }
                
                // Create a new transaction for this conversation
                $selectedTransaction = Transaction::create([
                    'buyer_id' => $userId,
                    'farmer_id' => $farmerId,
                    'product_id' => null, // No specific product for general messaging
                    'demand_id' => null, // No specific demand for general messaging
                    'final_quantity' => 0,
                    'final_price' => 0,
                    'total_amount' => 0,
                    'payment_status' => 'Pending',
                    'delivery_status' => 'Scheduled',
                    'status' => 'Active',
                    'initiator_id' => $userId, // Track who initiated the conversation
                    'conversation_thread_id' => $conversationThread->id
                ]);
                
                // Send an automatic "Hello" message to start the conversation
                Message::create([
                    'transaction_id' => $selectedTransaction->id,
                    'sender_id' => $userId,
                    'receiver_id' => $farmerId,
                    'message' => 'Hello, I\'m interested in your products. Let\'s discuss!',
                    'conversation_thread_id' => $conversationThread->id
                ]);
                
                // Add the new transaction to the transactions collection
                $transactions->push($selectedTransaction);
            }
        }
        // Check if a product was specified to start a new conversation about a specific product
        elseif ($request->has('product_id')) {
            $productId = $request->get('product_id');
            // Get the product details
            $product = Product::find($productId);
            if ($product) {
                $farmerId = $product->farmer->user_id;
                
                // Check if there's already a transaction for this specific product with this farmer
                $existingTransaction = Transaction::where('buyer_id', $userId)
                    ->where('farmer_id', $farmerId)
                    ->where('product_id', $productId)
                    ->first();
                
                if ($existingTransaction) {
                    // Use existing transaction
                    $selectedTransaction = $existingTransaction;
                } else {
                    // Check if there's already a conversation with this farmer (regardless of product)
                    $existingConversationTransaction = Transaction::where(function($query) use ($userId, $farmerId) {
                        $query->where('buyer_id', $userId)
                              ->where('farmer_id', $farmerId);
                    })->orWhere(function($query) use ($userId, $farmerId) {
                        $query->where('buyer_id', $farmerId)
                              ->where('farmer_id', $userId);
                    })->orderBy('updated_at', 'desc')->first();
                    
                    if ($existingConversationTransaction) {
                        // Use existing conversation thread
                        $conversationThread = $existingConversationTransaction->conversationThread;
                        $isNewConversationThread = false;
                    } else {
                        // Create a new conversation thread
                        $conversationThread = ConversationThread::create([
                            'buyer_id' => min($userId, $farmerId),
                            'farmer_id' => max($userId, $farmerId)
                        ]);
                        $isNewConversationThread = true;
                    }
                    
                    // Calculate the available quantity for this buyer
                    // Check if this buyer has already ordered some of this product
                    $orderedQuantity = Transaction::where('buyer_id', $userId)
                        ->where('product_id', $productId)
                        ->where('status', 'Ordered')
                        ->sum('final_quantity');
                    
                    
                    // Define available quantity as the product's quantity minus what this buyer has already ordered
                    $availableQuantity = $product->quantity - $orderedQuantity;
                    
                    // Create a new transaction for this product conversation
                    $selectedTransaction = Transaction::create([
                        'buyer_id' => $userId,
                        'farmer_id' => $farmerId,
                        'product_id' => $productId,
                        'demand_id' => null, // No specific demand for product inquiry
                        'final_quantity' => $availableQuantity,
                        'final_price' => $product->price,
                        'total_amount' => $availableQuantity * $product->price, // Will be updated during order placement
                        'payment_status' => 'Pending',
                        'delivery_status' => 'Scheduled',
                        'status' => 'Active',
                        'initiator_id' => $userId, // Track who initiated the conversation
                        'conversation_thread_id' => $conversationThread->id
                    ]);
                    
                    // Send an automatic "Is this available?" message with product details
                    $messageText = "Is this available?\n\nProduct: {$product->product_name}\nQuantity: {$product->quantity} {$product->unit}\nPrice: ₱" . number_format($product->price, 2) . "/{$product->unit}";
                    Message::create([
                        'transaction_id' => $selectedTransaction->id,
                        'sender_id' => $userId,
                        'receiver_id' => $farmerId,
                        'message' => $messageText,
                        'conversation_thread_id' => $conversationThread->id
                    ]);
                    
                    // Add the new transaction to the transactions collection
                    $transactions->push($selectedTransaction);
                    
                    // Set this as the selected transaction for auto-opening
                    $selectedTransaction = $selectedTransaction;
                }
            }
        }

        // Group transactions by conversation thread
        $groupedTransactions = [];
        $conversationThreads = [];

        foreach ($transactions as $transaction) {
            $threadId = $transaction->conversation_thread_id;
            
            // Group transactions by conversation thread
            if (!isset($groupedTransactions[$threadId])) {
                $groupedTransactions[$threadId] = [];
            }
            $groupedTransactions[$threadId][] = $transaction;
            
            // Store conversation thread information
            if (!isset($conversationThreads[$threadId])) {
                $conversationThreads[$threadId] = $transaction->conversationThread;
            }
        }

        // For each conversation thread, keep only the most recent transaction in the main list
        $mainTransactions = [];
        $allGroupedTransactions = [];

        foreach ($groupedTransactions as $threadId => $threadTransactions) {
            // Sort transactions by updated_at descending to get the most recent first
            usort($threadTransactions, function($a, $b) {
                return strtotime($b->updated_at) <=> strtotime($a->updated_at);
            });
            
            // Keep the most recent transaction in the main list
            $mainTransactions[] = $threadTransactions[0];
            
            // Store all transactions for this thread
            $allGroupedTransactions[$threadId] = $threadTransactions;
        }

        // Sort main transactions by updated_at descending
        usort($mainTransactions, function($a, $b) {
            return strtotime($b->updated_at) <=> strtotime($a->updated_at);
        });

        // Get unread message counts for each conversation thread
        $unreadCounts = Message::select('conversation_thread_id')
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->groupBy('conversation_thread_id')
            ->selectRaw('conversation_thread_id, count(*) as unread_count')
            ->get()
            ->keyBy('conversation_thread_id');

        return view('messages.index', compact('transactions', 'mainTransactions', 'allGroupedTransactions', 'unreadCounts', 'conversationThreads', 'selectedTransaction'));
    }
    
    /**
     * Load a conversation via AJAX
     */
    public function loadConversation(Transaction $transaction)
    {
        // Check if the authenticated user is part of this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Load messages for this conversation thread (not just this transaction)
        $messages = Message::where('conversation_thread_id', $transaction->conversation_thread_id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read for the current user
        Message::where('conversation_thread_id', $transaction->conversation_thread_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('messages.conversation', compact('transaction', 'messages'))->render();
    }
    
    /**
     * Load transaction details via AJAX
     */
    public function loadTransactionDetails(Transaction $transaction)
    {
        // Check if the authenticated user is part of this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        return view('messages.transaction-details', compact('transaction'))->render();
    }

    /**
     * Start a transaction between buyer and farmer
     */
    public function startTransaction(DemandMatch $demandMatch)
    {
        // Check if the authenticated user is authorized to start this transaction
        if (Auth::id() != $demandMatch->demand->buyer_id && Auth::id() != $demandMatch->product->farmer->user_id) {
            abort(403);
        }

        // Check if a transaction already exists for this match
        $existingTransaction = Transaction::where('demand_id', $demandMatch->demand_id)
            ->where('product_id', $demandMatch->product_id)
            ->first();

        if ($existingTransaction) {
            return redirect()->route('transactions.show', $existingTransaction)
                ->with('success', 'Transaction already exists.');
        }

        // Create a new transaction
        $transaction = Transaction::create([
            'buyer_id' => $demandMatch->demand->buyer_id,
            'farmer_id' => $demandMatch->product->farmer->user_id,
            'product_id' => $demandMatch->product_id,
            'demand_id' => $demandMatch->demand_id,
            'final_quantity' => $demandMatch->demand->quantity,
            'final_price' => $demandMatch->product->price,
            'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price,
            'payment_status' => 'Pending',
            'delivery_status' => 'Scheduled',
            'status' => 'Active',
            'initiator_id' => Auth::id() // Track who initiated the conversation
        ]);

        // Update match status to Transaction Started
        $demandMatch->update([
            'status' => 'Transaction Started'
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction started successfully! You can now negotiate with the other party.');
    }

    /**
     * Show transaction details
     */
    public function showTransaction(Transaction $transaction)
    {
        // Check if the authenticated user is part of this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Load messages for this transaction
        $messages = $transaction->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read for the current user
        $transaction->messages()
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Redirect to the appropriate messages view based on user role
        if (Auth::user()->farmer) {
            return redirect()->route('farmer.messages', ['transaction_id' => $transaction->id]);
        } else {
            return redirect()->route('buyer.messages', ['transaction_id' => $transaction->id]);
        }
    }

    /**
     * Send a message in a transaction
     */
    public function sendMessage(Request $request, Transaction $transaction)
    {
        \Log::info('SendMessage called', [
            'user_id' => Auth::id(),
            'transaction_id' => $transaction->id,
            'buyer_id' => $transaction->buyer_id,
            'farmer_id' => $transaction->farmer_id
        ]);
        
        // Check if the authenticated user is part of this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            \Log::warning('Unauthorized access attempt to send message', [
                'user_id' => Auth::id(),
                'transaction_id' => $transaction->id
            ]);
            abort(403);
        }

        $validatedData = $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        \Log::info('Message validation passed', $validatedData);

        // Determine receiver (the other party in the transaction)
        $receiverId = Auth::id() == $transaction->buyer_id ? $transaction->farmer_id : $transaction->buyer_id;
        \Log::info('Receiver determined', [
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId
        ]);

        // Create the message
        $message = Message::create([
            'transaction_id' => $transaction->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'message' => $validatedData['message'],
            'conversation_thread_id' => $transaction->conversation_thread_id // Associate with conversation thread
        ]);
        
        \Log::info('Message created', [
            'message_id' => $message->id,
            'message_content' => $message->message
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'data' => [
                'id' => $message->id,
                'message' => $message->message,
                'sender' => $message->sender->first_name . ' ' . $message->sender->last_name,
                'created_at' => $message->created_at->format('M d, Y H:i')
            ]
        ]);
    }

    /**
     * Start a conversation between buyer and farmer
     */
    public function startConversation(DemandMatch $demandMatch)
    {
        // Check if the authenticated user is authorized to start this conversation
        if (Auth::id() != $demandMatch->demand->buyer_id && Auth::id() != $demandMatch->product->farmer->user_id) {
            abort(403);
        }
        
        // Check if the product is sold out
        if ($demandMatch->product->status == 'Sold Out') {
            return back()->with('error', 'This product is sold out and no longer available for purchase.');
        }

        // Check if a transaction already exists for this match
        $existingTransaction = Transaction::where('demand_id', $demandMatch->demand_id)
            ->where('product_id', $demandMatch->product_id)
            ->first();

        $isNewTransaction = false;

        // If no transaction exists, create one
        if (!$existingTransaction) {
            $isNewTransaction = true;
            
            // Check if a conversation thread already exists between these users
            $conversationThread = ConversationThread::where(function($query) use ($demandMatch) {
                $query->where('buyer_id', $demandMatch->demand->buyer_id)
                      ->where('farmer_id', $demandMatch->product->farmer->user_id);
            })->orWhere(function($query) use ($demandMatch) {
                $query->where('buyer_id', $demandMatch->product->farmer->user_id)
                      ->where('farmer_id', $demandMatch->demand->buyer_id);
            })->first();

            // If no conversation thread exists, create one
            if (!$conversationThread) {
                $conversationThread = ConversationThread::create([
                    'buyer_id' => min($demandMatch->demand->buyer_id, $demandMatch->product->farmer->user_id),
                    'farmer_id' => max($demandMatch->demand->buyer_id, $demandMatch->product->farmer->user_id)
                ]);
            }

            $existingTransaction = Transaction::create([
                'buyer_id' => $demandMatch->demand->buyer_id,
                'farmer_id' => $demandMatch->product->farmer->user_id,
                'product_id' => $demandMatch->product_id,
                'demand_id' => $demandMatch->demand_id,
                'final_quantity' => $demandMatch->demand->quantity,
                'final_price' => $demandMatch->product->price,
                'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price,
                'payment_status' => 'Pending',
                'delivery_status' => 'Scheduled',
                'status' => 'Active',
                'initiator_id' => Auth::id(), // Track who initiated the conversation
                'conversation_thread_id' => $conversationThread->id // Associate with conversation thread
            ]);

            // Update match status to Transaction Started
            $demandMatch->update([
                'status' => 'Transaction Started'
            ]);
        }

        // If this is a new transaction, send an automatic "Is this available?" message with product details
        if ($isNewTransaction && Auth::id() == $demandMatch->demand->buyer_id) {
            // Create the automatic message with product details
            $product = $demandMatch->product;
            
            // Calculate the available quantity for this buyer
            // Check if this buyer has already ordered some of this product
            $orderedQuantity = Transaction::where('buyer_id', Auth::id())
                ->where('product_id', $product->id)
                ->where('status', 'Ordered')
                ->sum('final_quantity');
            
            $messageText = "Is this available?\n\nProduct: {$product->product_name}\nQuantity: {$product->quantity} {$product->unit}\nPrice: ₱" . number_format($product->price, 2) . "/{$product->unit}";
            
            // Determine receiver (the farmer)
            $receiverId = $product->farmer->user_id;
            
            // Create the message
            Message::create([
                'transaction_id' => $existingTransaction->id,
                'sender_id' => Auth::id(),
                'receiver_id' => $receiverId,
                'message' => $messageText,
                'conversation_thread_id' => $existingTransaction->conversation_thread_id
            ]);
        }

        // Redirect to the messages page with the transaction
        if (Auth::id() == $demandMatch->demand->buyer_id) {
            return redirect()->route('buyer.messages')
                ->with('selected_transaction_id', $existingTransaction->id);
        } else {
            return redirect()->route('farmer.messages')
                ->with('selected_transaction_id', $existingTransaction->id);
        }
    }

    /**
     * Place an order for a transaction
     */
    public function placeOrder(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the buyer in this transaction
        if (Auth::id() != $transaction->buyer_id) {
            abort(403);
        }
        
        // Load the required relationships
        $transaction->load(['product', 'demand', 'farmer']);
        
        // Check if the required relationships exist
        if (!$transaction->product) {
            return back()->with('error', 'Product not found.');
        }
        
        // Check if the product is sold out
        if ($transaction->product->status == 'Sold Out') {
            return back()->with('error', 'This product is sold out and no longer available for purchase. You cannot place any more orders for this product.');
        }
        
        // Validate the request data
        $validatedData = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'buyer_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:cash_on_delivery,bank_transfer,credit_card,e_wallet',
            'order_quantity' => 'nullable|integer|min:1',
        ]);

        // Determine the ordered quantity
        // If order_quantity is provided, use that, otherwise fall back to demand quantity or product quantity
        $orderedQuantity = $validatedData['order_quantity'] ?? 
                          ($transaction->demand ? $transaction->demand->quantity : $transaction->product->quantity);
        
        // Validate ordered quantity
        if (!is_numeric($orderedQuantity) || $orderedQuantity <= 0) {
            return back()->with('error', 'Invalid order quantity.');
        }

        // Create a new transaction with order details including final quantity, price and total amount
        $newTransaction = Transaction::create([
            'buyer_id' => $transaction->buyer_id,
            'farmer_id' => $transaction->farmer_id,
            'product_id' => $transaction->product_id,
            'demand_id' => $transaction->demand_id,
            'buyer_name' => $validatedData['buyer_name'],
            'buyer_email' => $validatedData['buyer_email'],
            'buyer_phone' => $validatedData['buyer_phone'],
            'buyer_address' => $validatedData['buyer_address'],
            'payment_method' => $validatedData['payment_method'],
            'status' => 'Ordered',
            'final_quantity' => $orderedQuantity,
            'final_price' => $transaction->product->price,
            'total_amount' => $orderedQuantity * $transaction->product->price
        ]);
        
        // Deduct the ordered quantity from the product
        $product = $transaction->product;
        
        // Check if ordered quantity exceeds available quantity
        if ($orderedQuantity > $product->quantity) {
            return back()->with('error', 'You cannot order more than the available quantity of ' . $product->quantity . ' ' . $product->unit . '.');
        }
        
        // Check if there's enough quantity available
        if ($product->quantity >= $orderedQuantity) {
            // Deduct the quantity
            $newQuantity = $product->quantity - $orderedQuantity;
            $product->update([
                'quantity' => $newQuantity
            ]);
            
            // If the product quantity reaches 0, mark it as sold out
            if ($newQuantity <= 0) {
                $product->update([
                    'status' => 'Sold Out'
                ]);
            }
            
            // Update the "Is this available?" message with the new quantity
            $this->updateAvailabilityMessage($newTransaction, $newQuantity);
        } else {
            // Not enough quantity available, rollback the order
            // Since we're creating a new transaction, we don't need to update the original
            // Just return the error
            
            return back()->with('error', 'Not enough quantity available for this product. Only ' . $product->quantity . ' ' . $product->unit . ' remaining. You cannot place any more orders for this product as it is now sold out.');
        }

        // Update the match status to 'Ordered' (only if there's a demand)
        if ($transaction->demand_id) {
            $demandMatch = DemandMatch::where('demand_id', $transaction->demand_id)
                ->where('product_id', $transaction->product_id)
                ->first();
                
            if ($demandMatch) {
                $demandMatch->load(['demand', 'product']); // Load relationships for the match
                $demandMatch->update([
                    'status' => 'Ordered'
                ]);
            }
        }

        // Send notification to farmer about the order
        $farmerUser = $transaction->farmer;
        if ($farmerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "A buyer has placed an order for your product \"{$productName}\". Please check the order details.";
            $data = [
                'message' => $message,
                'transaction_id' => $newTransaction->id,
                'product_name' => $productName,
                'quantity' => $orderedQuantity,
                'total_amount' => $orderedQuantity * $transaction->final_price
            ];
            $farmerUser->notify(new FarmerMatchNotification($data));
        }
        
        // Send notification to buyer about their order
        $buyerUser = $transaction->buyer;
        if ($buyerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "You have placed an order for \"{$productName}\". The farmer will review your order shortly.";
            $data = [
                'message' => $message,
                'transaction_id' => $newTransaction->id,
                'product_name' => $productName,
                'quantity' => $orderedQuantity,
                'total_amount' => $orderedQuantity * $transaction->final_price
            ];
            $buyerUser->notify(new \App\Notifications\BuyerFarmerAcceptNotification($data));
        }

        return redirect()->back()->with('success', 'Order placed successfully! You can place another order for this product as long as there is quantity available. ' . $newQuantity . ' ' . $product->unit . ' remaining.');
    }

    /**
     * List all orders for a user (buyer or farmer)
     */
    public function listOrders()
    {
        $userId = Auth::id();
        
        // Check if the user is a farmer or buyer
        $isFarmer = Auth::user()->farmer ? true : false;
        
        // Get all transactions where status is 'Ordered', 'Accepted', or 'Rejected' and user is either buyer or farmer
        $orders = Transaction::whereIn('status', ['Ordered', 'Accepted', 'Rejected'])
            ->where(function($query) use ($userId) {
                $query->where('buyer_id', $userId)
                      ->orWhere('farmer_id', $userId);
            })
            ->with(['buyer', 'farmer', 'product', 'demand'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Return different views based on user type
        if ($isFarmer) {
            return view('farmers.orders.index', compact('orders'));
        } else {
            return view('buyers.orders.index', compact('orders'));
        }
    }

    /**
     * Show a specific order
     */
    public function showOrder(Transaction $transaction)
    {
        $userId = Auth::id();
        
        // Check if the user is authorized to view this order (either buyer or farmer in the transaction)
        if ($userId != $transaction->buyer_id && $userId != $transaction->farmer_id) {
            abort(403);
        }
        
        // Load the required relationships
        $transaction->load(['buyer', 'farmer', 'product', 'demand']);
        
        // Check if the user is a farmer or buyer
        $isFarmer = Auth::user()->farmer ? true : false;
        
        // Return the order view
        $order = $transaction; // Alias for clarity in the view
        
        if ($isFarmer) {
            return view('orders.show', compact('order'));
        } else {
            return view('orders.show', compact('order'));
        }
    }

    /**
     * Mark an order as paid
     */
    public function markOrderAsPaid(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the buyer in this transaction
        if (Auth::id() != $transaction->buyer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Update payment status
        $transaction->update([
            'payment_status' => 'Paid'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as paid successfully!'
        ]);
    }

    /**
     * Mark an order as delivered
     */
    public function markOrderAsDelivered(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the buyer in this transaction
        if (Auth::id() != $transaction->buyer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Update delivery status
        $transaction->update([
            'delivery_status' => 'Delivered'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as delivered successfully!'
        ]);
    }

    /**
     * Accept an order (farmer action)
     */
    public function acceptOrder(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the farmer in this transaction
        if (Auth::id() != $transaction->farmer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Update order status
        $transaction->update([
            'status' => 'Accepted'
        ]);

        // Send notification to buyer about order acceptance
        $buyerUser = $transaction->buyer;
        if ($buyerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "Your order for \"{$productName}\" has been accepted by the farmer.";
            $data = [
                'message' => $message,
                'transaction_id' => $transaction->id,
                'product_name' => $productName
            ];
            $buyerUser->notify(new \App\Notifications\OrderAcceptedNotification($data));
        }

        return response()->json([
            'success' => true,
            'message' => 'Order accepted successfully!'
        ]);
    }

    /**
     * Reject an order (farmer action)
     */
    public function rejectOrder(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the farmer in this transaction
        if (Auth::id() != $transaction->farmer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Update order status
        $transaction->update([
            'status' => 'Rejected'
        ]);

        // Send notification to buyer about order rejection
        $buyerUser = $transaction->buyer;
        if ($buyerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "Your order for \"{$productName}\" has been rejected by the farmer.";
            $data = [
                'message' => $message,
                'transaction_id' => $transaction->id,
                'product_name' => $productName
            ];
            $buyerUser->notify(new \App\Notifications\OrderAcceptedNotification($data));
        }

        return response()->json([
            'success' => true,
            'message' => 'Order rejected successfully!'
        ]);
    }

    /**
     * Mark an order as prepared (farmer action)
     */
    public function markOrderAsPrepared(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the farmer in this transaction
        if (Auth::id() != $transaction->farmer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Update order status
        $transaction->update([
            'status' => 'Prepared'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as prepared successfully!'
        ]);
    }

    /**
     * Assign logistics to an order (farmer action)
     */
    public function assignLogistics(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the farmer in this transaction
        if (Auth::id() != $transaction->farmer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Update delivery status
        $transaction->update([
            'delivery_status' => 'In Transit'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Logistics assigned successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demand $demand)
    {
        // Check if the user has a buyer profile
        if (!Auth::user() || !Auth::user()->buyer) {
            return response()->json([
                'success' => false,
                'message' => 'You must have a buyer profile to delete demands.'
            ]);
        }
        
        // Ensure the demand belongs to the authenticated user
        if ($demand->buyer_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        // Delete the demand (this will also delete related matches due to foreign key constraints)
        $demand->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Demand deleted successfully.'
        ]);
    }

    /**
     * Update the "Is this available?" message with the new product quantity
     */
    private function updateAvailabilityMessage(Transaction $transaction, $newQuantity)
    {
        // Find the "Is this available?" message for this transaction
        $availabilityMessage = Message::where('transaction_id', $transaction->id)
            ->where('message', 'LIKE', 'Is this available?%')
            ->first();

        // If the message exists, update it with the new quantity
        if ($availabilityMessage) {
            $product = $transaction->product;
            $messageText = "Is this available?\n\nProduct: {$product->product_name}\nQuantity: {$newQuantity} {$product->unit}\nPrice: ₱" . number_format($product->price, 2) . "/{$product->unit}";
            
            $availabilityMessage->update([
                'message' => $messageText
            ]);
        }
    }
    
    /**
     * Message farmer about a specific product
     */
    public function messageFarmer(Product $product)
    {
        // Check if the authenticated user is a buyer
        if (!Auth::user() || !Auth::user()->buyer) {
            return redirect()->route('buyer.dashboard')->with('error', 'You must have a buyer profile to message farmers.');
        }
        
        // Check if the product is sold out
        if ($product->status == 'Sold Out') {
            return back()->with('error', 'This product is sold out and no longer available for purchase.');
        }
        
        $userId = Auth::id();
        $farmerId = $product->farmer->user_id;
        
        // Check if there's already a transaction for this specific product with this farmer
        $existingTransaction = Transaction::where('buyer_id', $userId)
            ->where('farmer_id', $farmerId)
            ->where('product_id', $product->id)
            ->first();
        
        // If no transaction exists, create one
        if (!$existingTransaction) {
            // Check if there's already a conversation with this farmer (regardless of product)
            $existingConversationTransaction = Transaction::where(function($query) use ($userId, $farmerId) {
                $query->where('buyer_id', $userId)
                      ->where('farmer_id', $farmerId);
            })->orWhere(function($query) use ($userId, $farmerId) {
                $query->where('buyer_id', $farmerId)
                      ->where('farmer_id', $userId);
            })->orderBy('updated_at', 'desc')->first();
            
            if ($existingConversationTransaction) {
                // Use existing conversation thread
                $conversationThread = $existingConversationTransaction->conversationThread;
            } else {
                // Create a new conversation thread
                $conversationThread = ConversationThread::create([
                    'buyer_id' => min($userId, $farmerId),
                    'farmer_id' => max($userId, $farmerId)
                ]);
            }
            
            // Calculate the available quantity for this buyer
            // Check if this buyer has already ordered some of this product
            $orderedQuantity = Transaction::where('buyer_id', $userId)
                ->where('product_id', $product->id)
                ->where('status', 'Ordered')
                ->sum('final_quantity');
            
            // Define available quantity as the product's quantity minus what this buyer has already ordered
            $availableQuantity = $product->quantity - $orderedQuantity;
            
            // Create a new transaction for this product conversation
            $transaction = Transaction::create([
                'buyer_id' => $userId,
                'farmer_id' => $farmerId,
                'product_id' => $product->id,
                'demand_id' => null, // No specific demand for product inquiry
                'final_quantity' => $availableQuantity,
                'final_price' => $product->price,
                'total_amount' => $availableQuantity * $product->price, // Will be updated during order placement
                'payment_status' => 'Pending',
                'delivery_status' => 'Scheduled',
                'status' => 'Active',
                'initiator_id' => $userId, // Track who initiated the conversation
                'conversation_thread_id' => $conversationThread->id
            ]);
            
            // Send an automatic "Is this available?" message with product details
            $messageText = "Is this available?\n\nProduct: {$product->product_name}\nQuantity: {$product->quantity} {$product->unit}\nPrice: ₱" . number_format($product->price, 2) . "/{$product->unit}";
            Message::create([
                'transaction_id' => $transaction->id,
                'sender_id' => $userId,
                'receiver_id' => $farmerId,
                'message' => $messageText,
                'conversation_thread_id' => $conversationThread->id
            ]);
        } else {
            // Use existing transaction
            $transaction = $existingTransaction;
        }
        
        // Redirect to the messages page with the transaction selected
        return redirect()->route('buyer.messages', ['transaction_id' => $transaction->id]);
    }
}