<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Product;
use App\Models\DemandMatch;
use App\Models\Transaction;
use App\Models\Message;
use App\Models\ConversationThread;
use App\Notifications\BuyerFarmerAcceptNotification;
use App\Notifications\FarmerMatchNotification;
use App\Notifications\OrderAcceptedNotification;
use App\Services\MatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
        
        // Process egg sizes if they come from checkboxes
        $eggSize = $request->input('egg_size');
        if ($request->has('egg_sizes')) {
            $eggSizes = $request->input('egg_sizes');
            $processedSizes = [];
            
            foreach ($eggSizes as $size) {
                $trayCount = null;
                
                switch ($size) {
                    case 'small':
                        $trayCount = $request->input('small_trays');
                        break;
                    case 'medium':
                        $trayCount = $request->input('medium_trays');
                        break;
                    case 'large':
                        $trayCount = $request->input('large_trays');
                        break;
                    case 'extra_large':
                        $trayCount = $request->input('extra_large_trays');
                        break;
                    case 'jumbo':
                        $trayCount = $request->input('jumbo_trays');
                        break;
                }
                
                if ($trayCount) {
                    $processedSizes[] = "{$size} ({$trayCount} tray" . ($trayCount > 1 ? 's' : '') . ')';
                } else {
                    $processedSizes[] = $size;
                }
            }
            
            $eggSize = implode(', ', $processedSizes);
        }

        $validatedData = $request->validate([
            'egg_type' => 'required|string|max:50',
            'egg_size' => 'nullable|string|max:255', // Increased max length for comma-separated values
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:today',
            'small_trays' => 'nullable|integer|min:1', // Tray count for small eggs
            'medium_trays' => 'nullable|integer|min:1', // Tray count for medium eggs
            'large_trays' => 'nullable|integer|min:1', // Tray count for large eggs
            'extra_large_trays' => 'nullable|integer|min:1', // Tray count for extra large eggs
            'jumbo_trays' => 'nullable|integer|min:1', // Tray count for jumbo eggs
        ]);

        $validatedData['buyer_id'] = Auth::id();
        $validatedData['egg_size'] = $eggSize; // Set the processed egg size
        
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
            abort(403);
        }

        $demand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Demand deleted successfully.'
        ]);
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
        $matchingProducts = Product::where('egg_type', $demand->egg_type)
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
                'transaction_id' => $demandMatch->id,
                'product_name' => $productName,
                'quantity' => $demandMatch->demand->quantity,
                'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price
            ];
            $farmerUser->notify(new FarmerMatchNotification($data));
        }
    } else {
        // Farmer is accepting the match
        $demandMatch->update([
            'status' => 'Matched'
        ]);
        
        // Notify the buyer that the farmer has accepted the match
        $buyerUser = $demandMatch->demand->buyer;
        if ($buyerUser) {
            $farmerName = $demandMatch->product->farmer->user->first_name . ' ' . $demandMatch->product->farmer->user->last_name;
            $productName = $demandMatch->product->product_name;
            $message = "{$farmerName} has accepted your demand for \"{$productName}\". You can now start a conversation to negotiate the details.";
            
            $data = [
                'message' => $message,
                'transaction_id' => $demandMatch->id,
                'product_name' => $productName,
                'quantity' => $demandMatch->demand->quantity,
                'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price
            ];
            $buyerUser->notify(new BuyerFarmerAcceptNotification($data));
        }
    }

    return back()->with('success', 'Match accepted successfully.');
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
    
    // Update the match status
    $demandMatch->update([
        'status' => 'Rejected'
    ]);
    
    return back()->with('success', 'Match rejected successfully.');
}

/**
 * Destroy a match
 */
public function destroyMatch(DemandMatch $demandMatch)
{
    // Check if the authenticated user is authorized to delete this match
    if (Auth::id() != $demandMatch->demand->buyer_id && Auth::id() != $demandMatch->product->farmer->user_id) {
        abort(403);
    }
    
    $demandMatch->delete();
    
    return back()->with('success', 'Match deleted successfully.');
}

/**
 * Start a conversation with a matched farmer
 */
public function startConversation(DemandMatch $demandMatch)
{
    // Check if the authenticated user is the buyer who made the demand
    if (Auth::id() != $demandMatch->demand->buyer_id) {
        abort(403);
    }
    
    // Check if there's already an existing transaction for this match
    $existingTransaction = Transaction::where('demand_id', $demandMatch->demand_id)
        ->where('product_id', $demandMatch->product_id)
        ->first();
    
    if ($existingTransaction) {
        // If there's already a transaction, redirect to the messages page with that transaction
        return redirect()->route('buyer.messages', ['transaction_id' => $existingTransaction->id]);
    }
    
    // Get the farmer user
    $farmer = $demandMatch->product->farmer;
    
    // Check if farmer exists
    if (!$farmer) {
        return redirect()->back()->with('error', 'Unable to start conversation: Farmer profile not found.');
    }
    
    // Get the user associated with the farmer
    $farmerUser = $farmer->user;
    
    // Check if farmer user exists
    if (!$farmerUser) {
        return redirect()->back()->with('error', 'Unable to start conversation: Farmer account not found.');
    }
    
    // Create or get conversation thread
    $conversationThread = ConversationThread::firstOrCreate([
        'buyer_id' => min(Auth::id(), $farmerUser->id),
        'farmer_id' => max(Auth::id(), $farmerUser->id)
    ]);
    
    // Create a transaction record
    $transaction = Transaction::create([
        'buyer_id' => Auth::id(),
        'farmer_id' => $farmerUser->id,
        'product_id' => $demandMatch->product_id,
        'demand_id' => $demandMatch->demand_id,
        'final_quantity' => $demandMatch->demand->quantity,
        'final_price' => $demandMatch->product->price,
        'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price,
        'payment_status' => 'Pending',
        'delivery_status' => 'Scheduled',
        'status' => 'Active',
        'initiator_id' => Auth::id(),
        'conversation_thread_id' => $conversationThread->id
    ]);
    
    // Send a welcome message
    $eggTypes = [
        'chicken' => 'Chicken Eggs',
        'duck' => 'Duck Eggs',
        'quail' => 'Quail Eggs',
        'native_chicken' => 'Native Chicken Eggs',
        'brown' => 'Brown Eggs',
        'white' => 'White Eggs'
    ];
    $eggTypeName = $eggTypes[$transaction->product->egg_type] ?? ucfirst(str_replace('_', ' ', $transaction->product->egg_type));
    $messageText = "Is this available?\n\nEgg Type: {$eggTypeName}\nQuantity: {$transaction->product->quantity} {$transaction->product->unit}\nPrice: ₱" . number_format($transaction->product->price, 2) . "/{$transaction->product->unit}";
    Message::create([
        'transaction_id' => $transaction->id,
        'sender_id' => Auth::id(),
        'receiver_id' => $farmerUser->id,
        'message' => $messageText,
        'conversation_thread_id' => $conversationThread->id
    ]);
    
    return redirect()->route('buyer.messages', ['transaction_id' => $transaction->id]);
}

/**
 * Start a transaction with a matched buyer
 */
public function startTransaction(DemandMatch $demandMatch)
{
    // Check if the authenticated user is the farmer who owns the product
    if (Auth::id() != $demandMatch->product->farmer->user_id) {
        abort(403);
    }
    
    // Check if there's already an existing transaction for this match
    $existingTransaction = Transaction::where('demand_id', $demandMatch->demand_id)
        ->where('product_id', $demandMatch->product_id)
        ->first();
    
    if ($existingTransaction) {
        // If there's already a transaction, redirect to the messages page with that transaction
        return redirect()->route('farmer.messages', ['transaction_id' => $existingTransaction->id]);
    }
    
    // Get the buyer user
    $buyer = $demandMatch->demand->buyer;
    
    // Check if buyer exists
    if (!$buyer) {
        return redirect()->back()->with('error', 'Unable to start conversation: Buyer profile not found.');
    }
    
    // Get the user associated with the buyer
    $buyerUser = $buyer->user;
    
    // Check if buyer user exists
    if (!$buyerUser) {
        return redirect()->back()->with('error', 'Unable to start conversation: Buyer account not found.');
    }
    
    // Create or get conversation thread
    $conversationThread = ConversationThread::firstOrCreate([
        'buyer_id' => min($buyerUser->id, Auth::id()),
        'farmer_id' => max($buyerUser->id, Auth::id())
    ]);
    
    // Create a transaction record
    $transaction = Transaction::create([
        'buyer_id' => $buyerUser->id,
        'farmer_id' => Auth::id(),
        'product_id' => $demandMatch->product_id,
        'demand_id' => $demandMatch->demand_id,
        'final_quantity' => $demandMatch->demand->quantity,
        'final_price' => $demandMatch->product->price,
        'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price,
        'payment_status' => 'Pending',
        'delivery_status' => 'Scheduled',
        'status' => 'Active',
        'initiator_id' => Auth::id(),
        'conversation_thread_id' => $conversationThread->id
    ]);
    
    // Send a welcome message
    $eggTypes = [
        'chicken' => 'Chicken Eggs',
        'duck' => 'Duck Eggs',
        'quail' => 'Quail Eggs',
        'native_chicken' => 'Native Chicken Eggs',
        'brown' => 'Brown Eggs',
        'white' => 'White Eggs'
    ];
    $eggTypeName = $eggTypes[$transaction->product->egg_type] ?? ucfirst(str_replace('_', ' ', $transaction->product->egg_type));
    $messageText = "Is this available?\n\nEgg Type: {$eggTypeName}\nQuantity: {$transaction->product->quantity} {$transaction->product->unit}\nPrice: ₱" . number_format($transaction->product->price, 2) . "/{$transaction->product->unit}";
    Message::create([
        'transaction_id' => $transaction->id,
        'sender_id' => Auth::id(),
        'receiver_id' => $buyerUser->id,
        'message' => $messageText,
        'conversation_thread_id' => $conversationThread->id
    ]);
    
    return redirect()->route('farmer.messages', ['transaction_id' => $transaction->id]);
}

/**
 * List all transactions for the authenticated user
 */
public function listTransactions(Request $request)
{
    $user = Auth::user();
    
    if ($user->buyer) {
        // Get transactions for buyer, grouped by conversation thread to avoid duplicates
        $transactions = Transaction::where('buyer_id', $user->id)
            ->with('product', 'farmer', 'demand', 'conversationThread')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->unique('conversation_thread_id')
            ->values();
    } elseif ($user->farmer) {
        // Get transactions for farmer, grouped by conversation thread to avoid duplicates
        $transactions = Transaction::where('farmer_id', $user->id)
            ->with('product', 'buyer', 'demand', 'conversationThread')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->unique('conversation_thread_id')
            ->values();
    } else {
        abort(403);
    }
    
    // Get unread message counts
    $unreadCounts = [];
    if ($user->buyer) {
        $unreadCounts = Message::selectRaw('conversation_thread_id, count(*) as message_count')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->groupBy('conversation_thread_id')
            ->pluck('message_count', 'conversation_thread_id');
    } elseif ($user->farmer) {
        $unreadCounts = Message::selectRaw('conversation_thread_id, count(*) as message_count')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->groupBy('conversation_thread_id')
            ->pluck('message_count', 'conversation_thread_id');
    }
    
    // Check if a specific transaction was requested
    $selectedTransaction = null;
    $transactionId = $request->query('transaction_id');
    if ($transactionId) {
        $selectedTransaction = $transactions->firstWhere('id', $transactionId);
    }
    
    // If no selected transaction found but we have a transaction ID, 
    // look for any transaction with the same conversation thread
    if (!$selectedTransaction && $transactionId) {
        $requestedTransaction = Transaction::find($transactionId);
        if ($requestedTransaction) {
            $conversationThreadId = $requestedTransaction->conversation_thread_id;
            $selectedTransaction = $transactions->firstWhere('conversation_thread_id', $conversationThreadId);
        }
    }
    
    return view('messages.index', compact('transactions', 'unreadCounts', 'selectedTransaction'));
}

/**
 * Show a specific transaction
 */
public function showTransaction(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to view this transaction
    if ($user->buyer && $transaction->buyer_id != $user->id) {
        abort(403);
    } elseif ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    // Load related data
    $transaction->load('product', 'demand', 'messages.sender', 'conversationThread');
    
    return view('messages.show', compact('transaction'));
}

/**
 * Show order details for a transaction
 */
public function showOrder(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to view this transaction
    if ($user->buyer && $transaction->buyer_id != $user->id) {
        abort(403);
    } elseif ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    // Load related data
    $transaction->load('product.sizes', 'demand', 'buyer', 'farmer', 'sizeTransactions');
    
    // Pass the transaction as 'order' to match the view expectation
    $order = $transaction;
    
    return view('orders.show', compact('order'));
}

/**
 * Send a message in a transaction
 */
public function sendMessage(Request $request, Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to send messages in this transaction
    if ($user->buyer && $transaction->buyer_id != $user->id) {
        abort(403);
    } elseif ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    $request->validate([
        'message' => 'required|string|max:1000'
    ]);
    
    // Determine receiver
    $receiverId = $user->buyer ? $transaction->farmer_id : $transaction->buyer_id;
    
    // Create message
    $message = Message::create([
        'transaction_id' => $transaction->id,
        'sender_id' => $user->id,
        'receiver_id' => $receiverId,
        'message' => $request->input('message'),
        'conversation_thread_id' => $transaction->conversation_thread_id
    ]);
    
    // Update transaction updated_at timestamp
    $transaction->updated_at = now();
    $transaction->save();
    
    return response()->json([
        'success' => true,
        'data' => [
            'message' => $message->message,
            'sender' => $message->sender->first_name . ' ' . $message->sender->last_name,
            'created_at' => $message->created_at->format('M d, Y H:i'),
            'is_sender' => $message->sender_id == Auth::id()
        ]
    ]);
}

/**
 * Get unread messages count
 */
public function getUnreadMessagesCount()
{
    $user = Auth::user();
    
    if ($user->buyer) {
        $count = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
    } elseif ($user->farmer) {
        $count = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
    } else {
        $count = 0;
    }
    
    return response()->json([
        'count' => $count
    ]);
}

/**
 * Get unread messages count by conversation
 */
public function getUnreadMessagesCountByConversation()
{
    $user = Auth::user();
    
    if ($user->buyer) {
        $counts = Message::selectRaw('conversation_thread_id, count(*) as message_count')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->groupBy('conversation_thread_id')
            ->pluck('message_count', 'conversation_thread_id');
    } elseif ($user->farmer) {
        $counts = Message::selectRaw('conversation_thread_id, count(*) as message_count')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->groupBy('conversation_thread_id')
            ->pluck('message_count', 'conversation_thread_id');
    } else {
        $counts = collect();
    }
    
    return response()->json([
        'success' => true,
        'counts' => $counts
    ]);
}

/**
 * Load conversation messages
 */
public function loadConversation(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to view this transaction
    if ($user->buyer && $transaction->buyer_id != $user->id) {
        abort(403);
    } elseif ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    // Mark all messages in this conversation thread as read
    Message::where('conversation_thread_id', $transaction->conversation_thread_id)
        ->where('receiver_id', $user->id)
        ->where('is_read', false)
        ->update(['is_read' => true]);
    
    // Load all messages for this conversation thread, ordered by creation time
    $messages = Message::where('conversation_thread_id', $transaction->conversation_thread_id)
        ->with('sender')
        ->orderBy('created_at', 'asc')
        ->get();
    
    return view('messages.conversation', compact('transaction', 'messages'));
}

/**
 * Load transaction details
 */
public function loadTransactionDetails(Transaction $transaction)
{
    $user = Auth::user();
        
    // Check if user is authorized to view this transaction
    if ($user->buyer && $transaction->buyer_id != $user->id) {
        abort(403);
    } elseif ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
        
    // Load related data
    $transaction->load('product.sizes', 'demand', 'buyer', 'farmer', 'sizeTransactions');
        
    return view('messages.transaction-details', compact('transaction'));
}

/**
 * Place an order
 */
public function placeOrder(Request $request, Transaction $transaction)
{
    // Log the incoming request data
    \Log::info('PlaceOrder request data: ' . json_encode($request->all()));
    \Log::info('PlaceOrder route params: ' . json_encode($request->route()->parameters()));
    
    // Check if the authenticated user is the buyer in this transaction
    if (Auth::id() != $transaction->buyer_id) {
        return back()->with('error', 'Unauthorized access.');
    }

    // Validate the request data
    $validatedData = $request->validate([
        'buyer_name' => 'required|string|max:255',
        'buyer_email' => 'required|email|max:255',
        'buyer_phone' => 'required|string|max:20',
        'buyer_address' => 'required|string|max:500',
        'payment_method' => 'required|string|in:cash_on_delivery,bank_transfer,credit_card,e_wallet',
        'order_quantity' => 'required|integer|min:1',
        'tray_counts' => 'sometimes|array',
        'tray_counts.*' => 'integer|min:0',
        'total_price' => 'required|numeric|min:0',
    ]);
    
    // Log validated data
    \Log::info('Validated data: ' . json_encode($validatedData));

    // Determine the ordered quantity
    $orderedQuantity = $validatedData['order_quantity'];
    
    // Get tray counts if provided
    $trayCounts = $request->input('tray_counts', []);
    
    // Log the tray counts for debugging
    \Log::info('Tray counts received: ' . json_encode($trayCounts));
    \Log::info('Tray counts type: ' . gettype($trayCounts));
    \Log::info('Tray counts empty check: ' . (empty($trayCounts) ? 'true' : 'false'));
    
    // Validate ordered quantity
    if (!is_numeric($orderedQuantity) || $orderedQuantity <= 0) {
        return back()->with('error', 'Invalid order quantity.');
    }

    // Calculate final price based on size-based ordering or fallback to product price
    $finalPrice = $validatedData['total_price'] > 0 ? ($validatedData['total_price'] / $orderedQuantity) : $transaction->product->price;
    
    // If we have tray counts, recalculate the ordered quantity and total price based on them
    if (!empty($trayCounts)) {
        \Log::info('Processing tray counts for recalculation');
        $orderedQuantity = 0;
        $totalPrice = 0;
        foreach ($trayCounts as $sizeId => $trayCount) {
            \Log::info('Processing size ID: ' . $sizeId . ', tray count: ' . $trayCount);
            if ($trayCount > 0) {
                $size = $transaction->product->sizes->find($sizeId);
                \Log::info('Size lookup result: ' . ($size ? 'found' : 'not found'));
                if ($size) {
                    $orderedQuantity += $trayCount;
                    $totalPrice += $trayCount * $size->price_per_tray;
                    \Log::info('Added to totals - Quantity: ' . $trayCount . ', Price: ' . ($trayCount * $size->price_per_tray));
                }
            }
        }
        // Update the validated data
        $validatedData['total_price'] = $totalPrice;
        $finalPrice = $totalPrice / $orderedQuantity;
        \Log::info('Recalculated totals - Ordered Quantity: ' . $orderedQuantity . ', Total Price: ' . $totalPrice . ', Final Price: ' . $finalPrice);
    }
    
    // Prepare size details for storage
    $sizeDetails = [];
    if (!empty($trayCounts)) {
        \Log::info('Preparing size details for storage');
        foreach ($trayCounts as $sizeId => $trayCount) {
            if ($trayCount > 0) {
                $size = $transaction->product->sizes->find($sizeId);
                if ($size) {
                    $sizeDetails[] = [
                        'size_id' => $size->id,
                        'size_name' => $size->size_name,
                        'tray_count' => $trayCount,
                        'price_per_tray' => $size->price_per_tray,
                        'total_price' => $trayCount * $size->price_per_tray
                    ];
                    \Log::info('Added size detail: ' . json_encode(end($sizeDetails)));
                }
            }
        }
    }
    
    // Create a new transaction instead of updating the existing one
    \Log::info('Creating new transaction with data: ' . json_encode([
        'buyer_id' => $transaction->buyer_id,
        'farmer_id' => $transaction->farmer_id,
        'product_id' => $transaction->product_id,
        'demand_id' => $transaction->demand_id,
        'conversation_thread_id' => $transaction->conversation_thread_id,
        'buyer_name' => $validatedData['buyer_name'],
        'buyer_email' => $validatedData['buyer_email'],
        'buyer_phone' => $validatedData['buyer_phone'],
        'buyer_address' => $validatedData['buyer_address'],
        'payment_method' => $validatedData['payment_method'],
        'status' => 'Ordered',
        'final_quantity' => $orderedQuantity,
        'final_price' => $finalPrice,
        'total_amount' => $validatedData['total_price'],
        'tray_counts' => !empty($trayCounts) ? json_encode($trayCounts) : null,
        'size_details' => !empty($sizeDetails) ? json_encode($sizeDetails) : null
    ]));
    
    $newTransaction = Transaction::create([
        'buyer_id' => $transaction->buyer_id,
        'farmer_id' => $transaction->farmer_id,
        'product_id' => $transaction->product_id,
        'demand_id' => $transaction->demand_id,
        'conversation_thread_id' => $transaction->conversation_thread_id,
        'buyer_name' => $validatedData['buyer_name'],
        'buyer_email' => $validatedData['buyer_email'],
        'buyer_phone' => $validatedData['buyer_phone'],
        'buyer_address' => $validatedData['buyer_address'],
        'payment_method' => $validatedData['payment_method'],
        'status' => 'Ordered',
        'final_quantity' => $orderedQuantity,
        'final_price' => $finalPrice,
        'total_amount' => $validatedData['total_price'],
        'tray_counts' => !empty($trayCounts) ? json_encode($trayCounts) : null,
        'size_details' => !empty($sizeDetails) ? json_encode($sizeDetails) : null
    ]);
    
    \Log::info('New transaction created with ID: ' . $newTransaction->id);

    // Create size transaction records for each ordered size
    if (!empty($trayCounts)) {
        \Log::info('Creating size transactions for transaction ID: ' . $newTransaction->id);
        foreach ($trayCounts as $sizeId => $trayCount) {
            if ($trayCount > 0) {
                $size = $transaction->product->sizes->find($sizeId);
                if ($size) {
                    \Log::info('Creating size transaction for size ID: ' . $sizeId . ', tray count: ' . $trayCount);
                    try {
                        \App\Models\SizeTransaction::create([
                            'transaction_id' => $newTransaction->id,
                            'size_id' => $size->id,
                            'size_name' => $size->size_name,
                            'tray_count' => $trayCount,
                            'price_per_tray' => $size->price_per_tray,
                            'total_price' => $trayCount * $size->price_per_tray
                        ]);
                        \Log::info('Successfully created size transaction for size ID: ' . $sizeId);
                    } catch (\Exception $e) {
                        \Log::error('Error creating size transaction for size ID: ' . $sizeId . '. Error: ' . $e->getMessage());
                    }
                }
            }
        }
    };

    // Deduct the ordered quantity from the product
    $product = $transaction->product;
    
    // Handle size-based deductions if tray counts are provided
    if (!empty($trayCounts)) {
        // Deduct quantities from each size
        foreach ($trayCounts as $sizeId => $trayCount) {
            if ($trayCount > 0) {
                $size = $product->sizes->find($sizeId);
                if ($size) {
                    $newTrayCount = max(0, $size->tray_count - $trayCount);
                    $size->update(['tray_count' => $newTrayCount]);
                }
            }
        }
        
        // Recalculate product total quantity
        $newProductQuantity = $product->sizes->sum('tray_count');
        $product->update(['quantity' => $newProductQuantity]);
    }
    
    // Check if ordered quantity exceeds available quantity
    if ($orderedQuantity > $product->quantity) {
        // Rollback the newly created transaction
        $newTransaction->delete();
        return back()->with('error', 'You cannot order more than the available quantity of ' . $product->quantity . ' ' . $product->unit . '.');
    }
    
    // Check if there's enough quantity available
    if ($product->quantity >= $orderedQuantity) {
        // For size-based orders, we've already deducted quantities from sizes above
        // For non-size-based orders, deduct the quantity
        if (empty($trayCounts)) {
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
        } else {
            // For size-based orders, check if product is sold out
            if ($product->quantity <= 0) {
                $product->update([
                    'status' => 'Sold Out'
                ]);
            }
        }
    } else {
        // Not enough quantity available, rollback the order
        $newTransaction->delete();
        return back()->with('error', 'Not enough quantity available for this product. Only ' . $product->quantity . ' ' . $product->unit . ' remaining. You cannot place any more orders for this product as it is now sold out.');
    }

    // Send notification to farmer about the order
    $farmerUser = $transaction->farmer;
    if ($farmerUser && $transaction->product) {
        $productName = $transaction->product->product_name;
        $message = "A buyer has placed an order for your product \"{$productName}\". Please check the order details.";
        
        // Add size information to the notification if applicable
        if (!empty($trayCounts)) {
            $sizeDetails = [];
            foreach ($trayCounts as $sizeId => $trayCount) {
                if ($trayCount > 0) {
                    $size = $transaction->product->sizes->find($sizeId);
                    if ($size) {
                        $sizeDetails[] = "{$size->size_name}: {$trayCount} trays";
                    }
                }
            }
            if (!empty($sizeDetails)) {
                $message .= " Size details: " . implode(', ', $sizeDetails) . ".";
            }
        }
        
        $data = [
            'message' => $message,
            'transaction_id' => $newTransaction->id,
            'product_name' => $productName,
            'quantity' => $orderedQuantity,
            'total_amount' => $validatedData['total_price']
        ];
        $farmerUser->notify(new OrderAcceptedNotification($data));
    }
    
    // Send notification to buyer about their order
    $buyerUser = $transaction->buyer;
    if ($buyerUser && $transaction->product) {
        $productName = $transaction->product->product_name;
        $message = "You have placed an order for \"{$productName}\". The farmer will review your order shortly.";
        
        // Add size information to the notification if applicable
        if (!empty($trayCounts)) {
            $sizeDetails = [];
            foreach ($trayCounts as $sizeId => $trayCount) {
                if ($trayCount > 0) {
                    $size = $transaction->product->sizes->find($sizeId);
                    if ($size) {
                        $sizeDetails[] = "{$size->size_name}: {$trayCount} trays";
                    }
                }
            }
            if (!empty($sizeDetails)) {
                $message .= " Size details: " . implode(', ', $sizeDetails) . ".";
            }
        }
        
        $data = [
            'message' => $message,
            'transaction_id' => $newTransaction->id,
            'product_name' => $productName,
            'quantity' => $orderedQuantity,
            'total_amount' => $validatedData['total_price']
        ];
        $buyerUser->notify(new OrderAcceptedNotification($data));
    }

    $remainingQuantity = $product->quantity;
    return back()->with('success', 'Order placed successfully! You can place another order for this product as long as there is quantity available. ' . $remainingQuantity . ' ' . $product->unit . ' remaining.');
}

/**
 * Mark order as paid
 */
public function markOrderAsPaid(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to mark order as paid
    if ($user->buyer && $transaction->buyer_id != $user->id) {
        abort(403);
    }
    
    $transaction->update([
        'payment_status' => 'Paid'
    ]);
    
    // Notify farmer
    $farmerUser = $transaction->farmer;
    $message = "Payment received for order #{$transaction->id}. Please prepare the goods for delivery.";
    $farmerUser->notify(new OrderAcceptedNotification([
        'message' => $message,
        'transaction_id' => $transaction->id
    ]));
    
    return back()->with('success', 'Order marked as paid. The farmer has been notified.');
}

/**
 * Mark order as delivered
 */
public function markOrderAsDelivered(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to mark order as delivered
    if ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    $transaction->update([
        'delivery_status' => 'Delivered'
    ]);
    
    // Notify buyer
    $buyerUser = $transaction->buyer;
    $message = "Your order #{$transaction->id} has been delivered. Please confirm receipt.";
    $buyerUser->notify(new OrderAcceptedNotification([
        'message' => $message,
        'transaction_id' => $transaction->id
    ]));
    
    return back()->with('success', 'Order marked as delivered. The buyer has been notified.');
}

/**
 * Accept order
 */
public function acceptOrder(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to accept order
    if ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    $transaction->update([
        'status' => 'Accepted'
    ]);
    
    // Notify buyer
    $buyerUser = $transaction->buyer;
    $message = "Your order #{$transaction->id} has been accepted. We're preparing your goods for delivery.";
    $buyerUser->notify(new OrderAcceptedNotification([
        'message' => $message,
        'transaction_id' => $transaction->id
    ]));
    
    return back()->with('success', 'Order accepted. The buyer has been notified.');
}

/**
 * Reject order
 */
public function rejectOrder(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to reject order
    if ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    $transaction->update([
        'status' => 'Rejected'
    ]);
    
    // Notify buyer
    $buyerUser = $transaction->buyer;
    $message = "Your order #{$transaction->id} has been rejected. Please contact the farmer for more information.";
    $buyerUser->notify(new OrderAcceptedNotification([
        'message' => $message,
        'transaction_id' => $transaction->id
    ]));
    
    return back()->with('success', 'Order rejected. The buyer has been notified.');
}

/**
 * Mark order as prepared
 */
public function markOrderAsPrepared(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to mark order as prepared
    if ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    $transaction->update([
        'delivery_status' => 'Prepared'
    ]);
    
    // Notify buyer
    $buyerUser = $transaction->buyer;
    $message = "Your order #{$transaction->id} has been prepared and is ready for pickup or delivery.";
    $buyerUser->notify(new OrderAcceptedNotification([
        'message' => $message,
        'transaction_id' => $transaction->id
    ]));
    
    return back()->with('success', 'Order marked as prepared. The buyer has been notified.');
}

/**
 * Assign logistics
 */
public function assignLogistics(Transaction $transaction)
{
    $user = Auth::user();
    
    // Check if user is authorized to assign logistics
    if ($user->farmer && $transaction->farmer_id != $user->id) {
        abort(403);
    }
    
    $transaction->update([
        'delivery_status' => 'In Transit'
    ]);
    
    // Notify buyer
    $buyerUser = $transaction->buyer;
    $message = "Your order #{$transaction->id} is now in transit. You will receive updates on the delivery status.";
    $buyerUser->notify(new OrderAcceptedNotification([
        'message' => $message,
        'transaction_id' => $transaction->id
    ]));
    
    return back()->with('success', 'Logistics assigned. The buyer has been notified.');
}

/**
 * List all orders for the authenticated user
 */
public function listOrders()
{
    $user = Auth::user();
    
    if ($user->buyer) {
        // Get orders for buyer
        $orders = Transaction::where('buyer_id', $user->id)
            ->whereIn('status', ['Ordered', 'Accepted', 'Rejected', 'Prepared', 'In Transit', 'Delivered'])
            ->with('product', 'farmer')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('buyers.orders.index', compact('orders'));
    } elseif ($user->farmer) {
        // Get orders for farmer
        $orders = Transaction::where('farmer_id', $user->id)
            ->whereIn('status', ['Ordered', 'Accepted', 'Rejected', 'Prepared', 'In Transit', 'Delivered'])
            ->with('product', 'buyer')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('farmers.orders.index', compact('orders'));
    } else {
        abort(403);
    }
}

/**
 * Message a farmer about a product
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
        // Create or get conversation thread using the same logic as startTransaction
        $conversationThread = ConversationThread::firstOrCreate([
            'buyer_id' => min($userId, $farmerId),
            'farmer_id' => max($userId, $farmerId)
        ]);
        
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
        $eggTypes = [
            'chicken' => 'Chicken Eggs',
            'duck' => 'Duck Eggs',
            'quail' => 'Quail Eggs',
            'native_chicken' => 'Native Chicken Eggs',
            'brown' => 'Brown Eggs',
            'white' => 'White Eggs'
        ];
        $eggTypeName = $eggTypes[$product->egg_type] ?? ucfirst(str_replace('_', ' ', $product->egg_type));
        $messageText = "Is this available?\n\nEgg Type: {$eggTypeName}\nQuantity: {$product->quantity} {$product->unit}\nPrice: ₱" . number_format($product->price, 2) . "/{$product->unit}";
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