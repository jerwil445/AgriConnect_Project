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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
            'variety_size' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'nullable|string|max:50',
            'purok_street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:today',
            'deadline' => 'nullable|date|after_or_equal:delivery_date',
        ]);

        $legacyVarietySize = $this->buildLegacyVarietySizeString($request);
        $validatedData['variety_size'] = $validatedData['variety_size'] ?? $legacyVarietySize;
        $validatedData['buyer_id'] = Auth::id();
        $validatedData['status'] = 'Available';

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

        $demand->load('matches.product.images', 'matches.product.remainingInventory', 'matches.product.farmer.user');

        return view('buyers.demands.show', compact('demand'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demand $demand)
    {
        $request = request();

        // Check if the user has a buyer profile
        if (!Auth::user() || !Auth::user()->buyer) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must have a buyer profile to delete demands.'
                ]);
            }

            return redirect()->route('buyer.dashboard')
                ->with('error', 'You must have a buyer profile to delete demands.');
        }

        // Ensure the demand belongs to the authenticated user
        if ($demand->buyer_id !== Auth::id()) {
            abort(403);
        }

        $demand->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Demand deleted successfully.'
            ]);
        }

        return redirect()->route('demands.index')
            ->with('success', 'Demand deleted successfully.');
    }

    /**
     * Show matches for a farmer's products
     */
    public function farmerMatches(Request $request)
    {
        // Get the authenticated user's farmer profile
        $farmer = Auth::user()->farmer;

        if (!$farmer) {
            abort(403);
        }

        $search = trim((string) $request->input('search', ''));
        $statusFilter = $request->input('status');
        $perPage = (int) $request->input('per_page', 9); // Default to 9 as it's a grid of 3

        if (!in_array($perPage, [9, 18, 27, 45, 90], true)) {
            $perPage = 9;
        }

        if (!in_array($statusFilter, ['Available', 'Pending', 'Sold Out'], true)) {
            $statusFilter = '';
        }

        // Get products for this farmer with their matches
        $productsQuery = $farmer->products()->with(['matches.demand.buyer', 'images', 'remainingInventory']);

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('product_name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('variety_size', 'ILIKE', '%' . $search . '%')
                    ->orWhereHas('matches.demand.buyer', function ($q) use ($search) {
                        $q->where('first_name', 'ILIKE', '%' . $search . '%')
                            ->orWhere('last_name', 'ILIKE', '%' . $search . '%');
                    });
            });
        }

        if ($statusFilter !== '') {
            $productsQuery->where('status', $statusFilter);
        }

        $products = $productsQuery->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('farmers.matches.index', compact('products', 'search', 'statusFilter', 'perPage'));
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
        $demandProductName = $demand->product_name;
        $hasDemandStatus = Schema::hasColumn('demands', 'status');

        if (!$demandProductName) {
            return;
        }

        $matchingProducts = Product::where('product_name', $demandProductName);

        if (!empty($demand->variety_size)) {
            $matchingProducts->where('variety_size', 'LIKE', '%' . $demand->variety_size . '%');
        }

        if ($hasDemandStatus) {
            $matchingProducts->where('status', 'Available');
        }

        $matchingProducts = $matchingProducts
            ->where(function ($query) use ($demand) {
                // Check if product has remaining inventory and sufficient quantity
                $query->whereHas('remainingInventory', function ($subQuery) use ($demand) {
                    $subQuery->where('remaining_quantity', '>=', $demand->quantity);
                })
                    // Or fallback to original quantity if no remaining inventory exists
                    ->orWhere('quantity', '>=', $demand->quantity);
            })
            ->where(function ($query) use ($demand) {
                // Match based on address fields (Province, City/Municipality, Barangay)
                // Skip location matching if no address fields are provided
                if (empty($demand->province) && empty($demand->municipality_city) && empty($demand->barangay)) {
                    return $query; // No location filtering
                }

                $query->where(function ($subQuery) use ($demand) {
                    // Match province if provided
                    if (!empty($demand->province)) {
                        $subQuery->where('province', 'LIKE', '%' . $demand->province . '%');
                    }

                    // Match municipality/city if provided
                    if (!empty($demand->municipality_city)) {
                        $subQuery->orWhere('municipality_city', 'LIKE', '%' . $demand->municipality_city . '%');
                    }

                    // Match barangay if provided
                    if (!empty($demand->barangay)) {
                        $subQuery->orWhere('barangay', 'LIKE', '%' . $demand->barangay . '%');
                    }
                })->orWhereHas('farmer', function ($subQuery) use ($demand) {
                    $subQuery->where(function ($farmerSubQuery) use ($demand) {
                        // Match province if provided
                        if (!empty($demand->province)) {
                            $farmerSubQuery->where('farm_address', 'LIKE', '%' . $demand->province . '%');
                        }

                        // Match municipality/city if provided
                        if (!empty($demand->municipality_city)) {
                            $farmerSubQuery->orWhere('farm_address', 'LIKE', '%' . $demand->municipality_city . '%');
                        }

                        // Match barangay if provided
                        if (!empty($demand->barangay)) {
                            $farmerSubQuery->orWhere('farm_address', 'LIKE', '%' . $demand->barangay . '%');
                        }
                    });
                });
            })
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
                    'transaction_id' => null,
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
                    'transaction_id' => null,
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
            'buyer_id' => Auth::id(),
            'farmer_id' => $farmerUser->id
        ]);

        // Update the match status to 'Transaction Started'
        $demandMatch->update([
            'status' => 'Transaction Started'
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

        $messageText = $this->buildInquiryMessage($transaction->product);
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
        $buyerUser = $demandMatch->demand->buyer;

        // Check if buyer exists
        if (!$buyerUser) {
            return redirect()->back()->with('error', 'Unable to start conversation: Buyer profile not found.');
        }

        // Create or get conversation thread
        $conversationThread = ConversationThread::firstOrCreate([
            'buyer_id' => $buyerUser->id,
            'farmer_id' => Auth::id()
        ]);

        // Update the match status to 'Transaction Started'
        $demandMatch->update([
            'status' => 'Transaction Started'
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

        $messageText = $this->buildInquiryMessage($transaction->product);
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
        $transaction->load('product', 'demand', 'buyer', 'farmer');

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
        $transaction->load('product', 'demand', 'buyer', 'farmer');

        return view('messages.transaction-details', compact('transaction'));
    }

    /**
     * Place an order
     */
    public function placeOrder(Request $request, Transaction $transaction)
    {
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
        ]);

        $orderedQuantity = $validatedData['order_quantity'];
        if (!is_numeric($orderedQuantity) || $orderedQuantity <= 0) {
            return back()->with('error', 'Invalid order quantity.');
        }

        $product = $transaction->product;
        $availableQuantity = $this->getAvailableQuantity($product);
        if ($availableQuantity < $orderedQuantity) {
            return back()->with('error', 'Not enough quantity available for this product. Only ' . $availableQuantity . ' ' . $product->unit . ' remaining.');
        }

        $finalPrice = (float) $product->price;
        $totalAmount = $orderedQuantity * $finalPrice;

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
            'total_amount' => $totalAmount
        ]);

        // Update the match status to 'Ordered' if there's a demand associated with this transaction
        if ($transaction->demand_id) {
            $demandMatch = DemandMatch::where('demand_id', $transaction->demand_id)
                ->where('product_id', $transaction->product_id)
                ->first();

            if ($demandMatch) {
                $demandMatch->update([
                    'status' => 'Ordered'
                ]);
            }
        }

        $this->updateRemainingInventoryForOrder($product, $orderedQuantity);

        $farmerUser = $transaction->farmer;
        if ($farmerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "A buyer has placed an order for your product \"{$productName}\". Please check the order details.";

            $data = [
                'message' => $message,
                'transaction_id' => $newTransaction->id,
                'product_name' => $productName,
                'quantity' => $orderedQuantity,
                'total_amount' => $totalAmount
            ];
            $farmerUser->notify(new OrderAcceptedNotification($data));
        }

        $buyerUser = $transaction->buyer;
        if ($buyerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "You have placed an order for \"{$productName}\". The farmer will review your order shortly.";

            $data = [
                'message' => $message,
                'transaction_id' => $newTransaction->id,
                'product_name' => $productName,
                'quantity' => $orderedQuantity,
                'total_amount' => $totalAmount
            ];
            $buyerUser->notify(new OrderAcceptedNotification($data));
        }

        $remainingQuantity = $this->getAvailableQuantity($product->fresh('remainingInventory'));
        return back()->with('success', 'Order placed successfully! You can place another order for this product as long as there is quantity available. ' . $remainingQuantity . ' ' . $product->unit . ' remaining.');
    }



    /**
     * Update remaining inventory tracking for an order without modifying the product table
     */
    protected function updateRemainingInventoryForOrder(Product $product, int $orderedQuantity = 0)
    {
        $product->load('remainingInventory');

        if (!$product->remainingInventory) {
            $remainingInventory = \App\Models\RemainingInventory::create([
                'product_id' => $product->id,
                'original_quantity' => $product->quantity,
                'original_price' => $product->total_amount ?? ($product->quantity * $product->price),
                'original_total_trays' => $product->quantity,
                'remaining_quantity' => $product->quantity,
                'remaining_price' => $product->total_amount ?? ($product->quantity * $product->price),
                'remaining_total_trays' => $product->quantity,
                'per_size_remaining' => null,
                'last_updated' => now()
            ]);

            $product->setRelation('remainingInventory', $remainingInventory);
        }

        $newRemainingQuantity = max(0, $product->remainingInventory->remaining_quantity - $orderedQuantity);
        $product->remainingInventory->update([
            'remaining_quantity' => $newRemainingQuantity,
            'remaining_price' => $newRemainingQuantity * (float) $product->price,
            'remaining_total_trays' => $newRemainingQuantity,
            'per_size_remaining' => null,
            'last_updated' => now()
        ]);

        // If remaining quantity is zero, mark product as sold out
        if ($newRemainingQuantity <= 0) {
            $product->update([
                'status' => 'Sold Out'
            ]);
        }
    }

    /**
     * Mark order as paid
     */
    public function markOrderAsPaid(Transaction $transaction)
    {
        // Check if the authenticated user is the buyer
        if (Auth::id() != $transaction->buyer_id) {
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

        return response()->json([
            'success' => true,
            'message' => 'Order marked as paid. The farmer has been notified.'
        ]);
    }

    /**
     * Mark order as delivered
     */
    public function markOrderAsDelivered(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
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

        return response()->json([
            'success' => true,
            'message' => 'Order marked as delivered. The buyer has been notified.'
        ]);
    }

    /**
     * Accept order
     */
    public function acceptOrder(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
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

        return response()->json([
            'success' => true,
            'message' => 'Order accepted. The buyer has been notified.'
        ]);
    }

    /**
     * Reject order
     */
    public function rejectOrder(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        $transaction->update([
            'status' => 'Rejected'
        ]);

        $this->restoreRemainingInventoryForOrder($transaction->product, (int) $transaction->final_quantity);

        // Notify buyer
        $buyerUser = $transaction->buyer;
        $message = "Your order #{$transaction->id} has been rejected. Please contact the farmer for more information.";
        $buyerUser->notify(new OrderAcceptedNotification([
            'message' => $message,
            'transaction_id' => $transaction->id
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Order rejected. The buyer has been notified.'
        ]);
    }

    /**
     * Mark order as prepared
     */
    public function markOrderAsPrepared(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
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

        return response()->json([
            'success' => true,
            'message' => 'Order marked as prepared. The buyer has been notified.'
        ]);
    }

    /**
     * Assign logistics
     */
    public function assignLogistics(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
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

        return response()->json([
            'success' => true,
            'message' => 'Logistics assigned. The buyer has been notified.'
        ]);
    }

    /**
     * Mark order as delivered by buyer
     */
    public function markOrderAsDeliveredByBuyer(Transaction $transaction)
    {
        // Check if the authenticated user is the buyer
        if (Auth::id() != $transaction->buyer_id) {
            abort(403);
        }

        $transaction->update([
            'delivery_status' => 'Delivered'
        ]);

        // Notify farmer
        $farmerUser = $transaction->farmer;
        $message = "Order #{$transaction->id} has been marked as delivered by the buyer.";
        $farmerUser->notify(new OrderAcceptedNotification([
            'message' => $message,
            'transaction_id' => $transaction->id
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Order marked as delivered. The farmer has been notified.'
        ]);
    }

    /**
     * List all orders for the authenticated user
     */
    public function listOrders(Request $request)
    {
        $user = Auth::user();
        $orderStatuses = ['Ordered', 'Accepted', 'Rejected', 'Prepared', 'In Transit', 'Delivered'];
        $perPageOptions = [10, 25, 50, 100];
        $perPage = in_array((int) $request->input('per_page', 10), $perPageOptions) ? (int) $request->input('per_page', 10) : 10;
        $search = trim($request->input('search', ''));
        $statusFilter = $request->input('status');

        if ($user->buyer) {
            $ordersQuery = Transaction::where('buyer_id', $user->id)
                ->whereIn('status', $orderStatuses)
                ->with('product', 'farmer')
                ->when($search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('id', 'ILIKE', "%{$search}%")
                            ->orWhereHas('product', function ($query) use ($search) {
                                $query->where('product_name', 'ILIKE', "%{$search}%");
                            })
                            ->orWhereHas('farmer', function ($query) use ($search) {
                                $query->where('first_name', 'ILIKE', "%{$search}%")
                                    ->orWhere('last_name', 'ILIKE', "%{$search}%");
                            });
                    });
                })
                ->when($statusFilter && in_array($statusFilter, $orderStatuses), function ($query) use ($statusFilter) {
                    $query->where('status', $statusFilter);
                })
                ->orderBy('created_at', 'desc');

            $orders = $ordersQuery->paginate($perPage)->withQueryString();

            return view('buyers.orders.index', compact('orders', 'orderStatuses', 'search', 'statusFilter', 'perPage'));
        } elseif ($user->farmer) {
            $ordersQuery = Transaction::where('farmer_id', $user->id)
                ->whereIn('status', $orderStatuses)
                ->with('product', 'buyer')
                ->when($search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('id', 'ILIKE', "%{$search}%")
                            ->orWhereHas('product', function ($query) use ($search) {
                                $query->where('product_name', 'ILIKE', "%{$search}%");
                            })
                            ->orWhereHas('buyer', function ($query) use ($search) {
                                $query->where('first_name', 'ILIKE', "%{$search}%")
                                    ->orWhere('last_name', 'ILIKE', "%{$search}%");
                            });
                    });
                })
                ->when($statusFilter && in_array($statusFilter, $orderStatuses), function ($query) use ($statusFilter) {
                    $query->where('status', $statusFilter);
                })
                ->orderBy('created_at', 'desc');

            $orders = $ordersQuery->paginate($perPage)->withQueryString();

            return view('farmers.orders.index', compact('orders', 'orderStatuses', 'search', 'statusFilter', 'perPage'));
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

        if ($userId == $farmerId) {
            return back()->with('error', 'You cannot start a demand conversation with your own product.');
        }

        // Check if there's already a transaction for this specific product with this farmer
        $existingTransaction = Transaction::where('buyer_id', $userId)
            ->where('farmer_id', $farmerId)
            ->where('product_id', $product->id)
            ->first();

        // If no transaction exists, create one
        if (!$existingTransaction) {
            // Create or get conversation thread using the same logic as startTransaction
            $conversationThread = ConversationThread::firstOrCreate([
                'buyer_id' => $userId,
                'farmer_id' => $farmerId
            ]);

            // Calculate the available quantity for this buyer
            // Check if this buyer has already ordered some of this product
            $orderedQuantity = Transaction::where('buyer_id', $userId)
                ->where('product_id', $product->id)
                ->where('status', 'Ordered')
                ->sum('final_quantity');

            // Define available quantity as the product's quantity minus what this buyer has already ordered
            $availableQuantity = max(0, $this->getAvailableQuantity($product) - $orderedQuantity);

            if ($availableQuantity <= 0) {
                return back()->with('error', 'This product has no remaining quantity available for a new order.');
            }

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

            $messageText = $this->buildInquiryMessage($product);
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

    protected function restoreRemainingInventoryForOrder(Product $product, int $quantity): void
    {
        $product->load('remainingInventory');

        if (!$product->remainingInventory) {
            return;
        }

        $restoredQuantity = min(
            (int) $product->remainingInventory->original_quantity,
            (int) $product->remainingInventory->remaining_quantity + $quantity
        );

        $product->remainingInventory->update([
            'remaining_quantity' => $restoredQuantity,
            'remaining_price' => $restoredQuantity * (float) $product->price,
            'remaining_total_trays' => $restoredQuantity,
            'per_size_remaining' => null,
            'last_updated' => now(),
        ]);

        if ($restoredQuantity > 0 && $product->status === 'Sold Out') {
            $product->update(['status' => 'Available']);
        }
    }

    protected function getAvailableQuantity(Product $product): int
    {
        $product->loadMissing('remainingInventory');

        return (int) ($product->remainingInventory->remaining_quantity ?? $product->quantity);
    }

    protected function buildInquiryMessage(Product $product): string
    {
        $lines = [
            'Is this available?',
            '',
            'Product: ' . $product->product_name,
        ];

        if (!empty($product->variety_size)) {
            $lines[] = 'Variety/Size: ' . $product->variety_size;
        }

        $lines[] = 'Quantity: ' . $product->quantity . ' ' . $product->unit;
        $lines[] = 'Price per Unit: PHP ' . number_format((float) $product->price, 2) . '/' . $product->unit;

        return implode("\n", $lines);
    }

    protected function buildLegacyVarietySizeString(Request $request): ?string
    {
        if (!$request->has('egg_sizes')) {
            return $request->input('variety_size') ?? $request->input('egg_size');
        }

        $processedSizes = [];

        foreach ($request->input('egg_sizes', []) as $size) {
            $trayCount = match ($size) {
                'small' => $request->input('small_trays'),
                'medium' => $request->input('medium_trays'),
                'large' => $request->input('large_trays'),
                'extra_large' => $request->input('extra_large_trays'),
                'jumbo' => $request->input('jumbo_trays'),
                default => null,
            };

            if ($trayCount) {
                $processedSizes[] = "{$size} ({$trayCount} tray" . ($trayCount > 1 ? 's' : '') . ')';
            } else {
                $processedSizes[] = $size;
            }
        }

        return empty($processedSizes) ? null : implode(', ', $processedSizes);
    }
}
