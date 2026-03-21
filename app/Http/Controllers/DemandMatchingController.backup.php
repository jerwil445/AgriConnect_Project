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
            // Egg-specific validations
            'preferred_size' => 'nullable|string|max:20',
            'min_grade' => 'nullable|string|max:10',
            'organic_required' => 'nullable|boolean',
            'free_range_required' => 'nullable|boolean',
            'cage_free_required' => 'nullable|boolean',
            'pasture_raised_required' => 'nullable|boolean',
            'enriched_preferred' => 'nullable|boolean',
            'brown_preferred' => 'nullable|boolean',
        ]);

        $validatedData['buyer_id'] = Auth::id();
        
        // Handle boolean values for egg attributes
        $validatedData['organic_required'] = $request->has('organic_required') ? true : false;
        $validatedData['free_range_required'] = $request->has('free_range_required') ? true : false;
        $validatedData['cage_free_required'] = $request->has('cage_free_required') ? true : false;
        $validatedData['pasture_raised_required'] = $request->has('pasture_raised_required') ? true : false;
        $validatedData['enriched_preferred'] = $request->has('enriched_preferred') ? true : false;
        $validatedData['brown_preferred'] = $request->has('brown_preferred') ? true : false;

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
        // Start building the query for matching products
        $query = Product::where('product_name', 'LIKE', '%' . $demand->product_name . '%')
            ->where('quantity', '>=', $demand->quantity)
            ->where('status', 'Available');

        // If this is an egg demand, add egg-specific matching criteria
        if (stripos($demand->product_name, 'egg') !== false) {
            // Add size matching if demand has preferred size
            if (!empty($demand->preferred_size)) {
                $query->where(function ($q) use ($demand) {
                    $q->whereNull('size')
                      ->orWhere('size', $demand->preferred_size);
                });
            }

            // Add grade matching if demand has minimum grade
            if (!empty($demand->min_grade)) {
                $query->where(function ($q) use ($demand) {
                    $q->whereNull('grade')
                      ->orWhere('grade', '>=', $demand->min_grade);
                });
            }

            // Add organic matching
            if ($demand->organic_required) {
                $query->where(function ($q) {
                    $q->where('organic', true);
                });
            }

            // Add free-range matching
            if ($demand->free_range_required) {
                $query->where(function ($q) {
                    $q->where('free_range', true);
                });
            }

            // Add cage-free matching
            if ($demand->cage_free_required) {
                $query->where(function ($q) {
                    $q->where('cage_free', true);
                });
            }

            // Add pasture-raised matching
            if ($demand->pasture_raised_required) {
                $query->where(function ($q) {
                    $q->where('pasture_raised', true);
                });
            }
        }

        // Execute the query
        $matchingProducts = $query->get();

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
                        'image' => $product->image,
                        'farmer_name' => $farmerName,
                        'farm_name' => $product->farmer->farm_name
                    ]
                ];
                
                // Send the notification
                $buyerUser->notify(new BuyerFarmerAcceptNotification($notificationData));
            }
            
            // Return JSON response for AJAX requests
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('farmer.messages')
                ]);
            }
            
            return redirect()->route('farmer.messages')->with('success', 'Match accepted! You can now communicate with the buyer.');
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

        // Update the match status to Rejected
        $demandMatch->update([
            'status' => 'Rejected'
        ]);

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Match rejected successfully.'
            ]);
        }

        // Redirect back with success message
        return back()->with('success', 'Match rejected successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demand $demand)
    {
        // Check if the user has a buyer profile
        if (!Auth::user() || !Auth::user()->buyer) {
            return redirect()->route('buyer.dashboard')->with('error', 'You must have a buyer profile to delete demands.');
        }
        
        // Ensure the demand belongs to the authenticated user
        if ($demand->buyer_id !== Auth::id()) {
            abort(403);
        }

        // Delete all matches for this demand
        $demand->matches()->delete();

        // Delete the demand
        $demand->delete();

        return redirect()->route('demands.index')
            ->with('success', 'Demand deleted successfully.');
    }

    /**
     * Start a conversation with a farmer about a matched product
     */
    public function startConversation(DemandMatch $demandMatch)
    {
        // Check if the authenticated user is the buyer who created the demand
        if (Auth::id() != $demandMatch->demand->buyer_id) {
            abort(403);
        }

        // For 'New' matches, update status to 'Pending' and notify farmer
        if ($demandMatch->status === 'New') {
            $demandMatch->update(['status' => 'Pending']);
            
            // Notify the farmer about the buyer's interest
            $farmerUser = $demandMatch->product->farmer->user;
            if ($farmerUser) {
                $buyerName = $demandMatch->demand->buyer->first_name . ' ' . $demandMatch->demand->buyer->last_name;
                $productName = $demandMatch->product->product_name;
                $message = "{$buyerName} is interested in your product \"{$productName}\". Please review and accept the match to start negotiation.";
                
                $data = [
                    'message' => $message,
                    'demand_match_id' => $demandMatch->id
                ];
                $farmerUser->notify(new FarmerMatchNotification($data));
            }
        }
        
        // Create a conversation thread if it doesn't exist
        $conversationThread = ConversationThread::firstOrCreate([
            'buyer_id' => $demandMatch->demand->buyer_id,
            'farmer_id' => $demandMatch->product->farmer->user_id
        ]);

        // Create a transaction record if it doesn't exist
        $transaction = Transaction::firstOrCreate([
            'demand_id' => $demandMatch->demand_id,
            'product_id' => $demandMatch->product_id,
            'buyer_id' => $demandMatch->demand->buyer_id,
            'farmer_id' => $demandMatch->product->farmer->user_id,
            'conversation_thread_id' => $conversationThread->id
        ], [
            'final_quantity' => $demandMatch->demand->quantity,
            'final_price' => $demandMatch->product->price,
            'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price,
            'payment_status' => 'Pending',
            'delivery_status' => 'Scheduled',
            'status' => 'Active'
        ]);

        // Redirect to the messages page with the transaction pre-selected
        return redirect()->route('buyer.messages', ['transaction_id' => $transaction->id])->with('success', 'You can now communicate with the farmer.');
    }

    /**
     * Start a transaction with a buyer
     */
    public function startTransaction(DemandMatch $demandMatch)
    {
        // Check if the authenticated user is the farmer who owns the product
        if (Auth::id() != $demandMatch->product->farmer->user_id) {
            abort(403);
        }

        // Check if the match is in 'Pending' status
        if ($demandMatch->status !== 'Pending') {
            return back()->with('error', 'You can only start a transaction for pending matches.');
        }

        // Update the match status to Matched
        $demandMatch->update([
            'status' => 'Matched'
        ]);

        // Create a conversation thread if it doesn't exist
        $conversationThread = ConversationThread::firstOrCreate([
            'buyer_id' => $demandMatch->demand->buyer_id,
            'farmer_id' => $demandMatch->product->farmer->user_id
        ]);

        // Create a transaction record if it doesn't exist
        $transaction = Transaction::firstOrCreate([
            'demand_id' => $demandMatch->demand_id,
            'product_id' => $demandMatch->product_id,
            'buyer_id' => $demandMatch->demand->buyer_id,
            'farmer_id' => $demandMatch->product->farmer->user_id,
            'conversation_thread_id' => $conversationThread->id
        ], [
            'final_quantity' => $demandMatch->demand->quantity,
            'final_price' => $demandMatch->product->price,
            'total_amount' => $demandMatch->demand->quantity * $demandMatch->product->price,
            'payment_status' => 'Pending',
            'delivery_status' => 'Scheduled',
            'status' => 'Active'
        ]);

        // Send notification to the buyer
        $buyerUser = $demandMatch->demand->buyer;
        if ($buyerUser) {
            // Prepare notification data with product details
            $product = $demandMatch->product;
            $farmerName = $product->farmer->user->first_name . ' ' . $product->farmer->user->last_name;
            $notificationData = [
                'message' => "{$farmerName} has accepted your match request for \"{$product->product_name}\" and started a transaction. You can now negotiate.",
                'data' => [
                    'product_name' => $product->product_name,
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                    'unit' => $product->unit ?? 'units',
                    'price' => $product->price,
                    'harvest_date' => $product->harvest_date,
                    'image' => $product->image,
                    'farmer_name' => $farmerName,
                    'farm_name' => $product->farmer->farm_name,
                    'transaction_id' => $transaction->id
                ]
            ];
            
            // Send the notification
            $buyerUser->notify(new BuyerFarmerAcceptNotification($notificationData));
        }

        // Redirect to the transaction page
        return redirect()->route('farmer.messages', ['transaction_id' => $transaction->id])->with('success', 'Transaction started successfully!');
    }

    /**
     * Show transaction details
     */
    public function showTransaction(Transaction $transaction)
    {
        // Check if the authenticated user is involved in this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Load the transaction with related data
        $transaction->load('demand', 'product.images', 'product.farmer.user', 'buyer', 'messages.sender', 'conversationThread');

        // Redirect to the messages page with the transaction pre-selected
        if (Auth::user() && Auth::user()->buyer && Auth::id() == $transaction->buyer_id) {
            return redirect()->route('buyer.messages', ['transaction_id' => $transaction->id]);
        } else {
            return redirect()->route('farmer.messages', ['transaction_id' => $transaction->id]);
        }
    }

    /**
     * Send a message in a transaction
     */
    public function sendMessage(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is involved in this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Validate the request
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Create the message
        $message = Message::create([
            'conversation_thread_id' => $transaction->conversation_thread_id,
            'sender_id' => Auth::id(),
            'message' => $request->message
        ]);

        // Return JSON response for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'sender_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name
            ]);
        }

        return back()->with('success', 'Message sent successfully.');
    }

    /**
     * Get unread messages count
     */
    public function getUnreadMessagesCount()
    {
        // Get all conversation threads for the authenticated user
        $conversationThreads = ConversationThread::whereHas('transaction', function ($query) {
            $query->where('buyer_id', Auth::id())
                  ->orWhere('farmer_id', Auth::id());
        })->with('messages')->get();

        $unreadCount = 0;

        foreach ($conversationThreads as $thread) {
            foreach ($thread->messages as $message) {
                if ($message->sender_id != Auth::id() && !$message->read_at) {
                    $unreadCount++;
                }
            }
        }

        return response()->json(['count' => $unreadCount]);
    }

    /**
     * Get unread messages count by conversation
     */
    public function getUnreadMessagesCountByConversation(Transaction $transaction)
    {
        // Check if the authenticated user is involved in this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Get unread messages count for this conversation
        $unreadCount = Message::where('conversation_thread_id', $transaction->conversation_thread_id)
                              ->where('sender_id', '!=', Auth::id())
                              ->whereNull('read_at')
                              ->count();

        return response()->json(['count' => $unreadCount]);
    }

    /**
     * Load conversation messages
     */
    public function loadConversation(Transaction $transaction)
    {
        // Check if the authenticated user is involved in this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Mark messages as read
        Message::where('conversation_thread_id', $transaction->conversation_thread_id)
               ->where('sender_id', '!=', Auth::id())
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        // Load messages
        $messages = Message::where('conversation_thread_id', $transaction->conversation_thread_id)
                           ->with('sender')
                           ->orderBy('created_at', 'asc')
                           ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Load transaction details
     */
    public function loadTransactionDetails(Transaction $transaction)
    {
        // Check if the authenticated user is involved in this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Load the transaction with related data
        $transaction->load('demand', 'product.images', 'product.farmer.user', 'buyer');

        return response()->json(['transaction' => $transaction]);
    }

    /**
     * Place an order for a product
     */
    public function placeOrder(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the buyer
        if (Auth::id() != $transaction->buyer_id) {
            abort(403);
        }

        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $transaction->product->quantity,
            'delivery_address' => 'required|string|max:500'
        ]);

        // Update transaction with order details
        $transaction->update([
            'final_quantity' => $request->quantity,
            'total_amount' => $request->quantity * $transaction->product->price,
            'delivery_address' => $request->delivery_address,
            'status' => 'Ordered'
        ]);

        // Update product quantity
        $product = $transaction->product;
        $product->quantity -= $request->quantity;
        if ($product->quantity <= 0) {
            $product->status = 'Sold Out';
        }
        $product->save();

        // Return JSON response for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'transaction' => $transaction
            ]);
        }

        return redirect()->route('buyer.orders')->with('success', 'Order placed successfully!');
    }

    /**
     * Show order details
     */
    public function showOrder(Transaction $transaction)
    {
        // Check if the authenticated user is involved in this transaction
        if (Auth::id() != $transaction->buyer_id && Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Load the transaction with related data
        $transaction->load('demand', 'product.images', 'product.farmer.user', 'buyer', 'messages.sender');

        return view('orders.show', compact('transaction'));
    }

    /**
     * List all orders for the authenticated user
     */
    public function listOrders()
    {
        // Get transactions for the authenticated user
        $transactions = Transaction::where('buyer_id', Auth::id())
                                  ->orWhere('farmer_id', Auth::id())
                                  ->with('product', 'buyer', 'product.farmer.user')
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('orders.index', compact('transactions'));
    }

    /**
     * List all transactions for the authenticated user
     */
    public function listTransactions(Request $request)
    {
        // Get transactions for the authenticated user
        $mainTransactions = Transaction::where('buyer_id', Auth::id())
                                  ->orWhere('farmer_id', Auth::id())
                                  ->with('product', 'buyer', 'product.farmer.user')
                                  ->orderBy('created_at', 'desc')
                                  ->get();
        
        // Initialize other required variables
        $unreadCounts = [];
        $selectedTransaction = null;
        
        // Check if a transaction_id parameter was provided
        $transactionId = $request->query('transaction_id');
        if ($transactionId) {
            $selectedTransaction = $mainTransactions->firstWhere('id', $transactionId);
        }

        return view('messages.index', compact('mainTransactions', 'unreadCounts', 'selectedTransaction'));
    }

    /**
     * Mark an order as paid
     */
    public function markOrderAsPaid(Transaction $transaction)
    {
        // Check if the authenticated user is the buyer
        if (Auth::id() != $transaction->buyer_id) {
            abort(403);
        }

        // Update payment status
        $transaction->update([
            'payment_status' => 'Paid'
        ]);

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order marked as paid!'
            ]);
        }

        return back()->with('success', 'Order marked as paid!');
    }

    /**
     * Mark an order as delivered
     */
    public function markOrderAsDelivered(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Update delivery status
        $transaction->update([
            'delivery_status' => 'Delivered'
        ]);

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order marked as delivered!'
            ]);
        }

        return back()->with('success', 'Order marked as delivered!');
    }

    /**
     * Accept an order
     */
    public function acceptOrder(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Update status
        $transaction->update([
            'status' => 'Accepted'
        ]);

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order accepted!'
            ]);
        }

        return back()->with('success', 'Order accepted!');
    }

    /**
     * Reject an order
     */
    public function rejectOrder(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Update status
        $transaction->update([
            'status' => 'Rejected'
        ]);

        // Return the quantity to the product
        $product = $transaction->product;
        $product->quantity += $transaction->final_quantity;
        if ($product->quantity > 0 && $product->status == 'Sold Out') {
            $product->status = 'Available';
        }
        $product->save();

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order rejected!'
            ]);
        }

        return back()->with('success', 'Order rejected!');
    }

    /**
     * Mark an order as prepared
     */
    public function markOrderAsPrepared(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Update status
        $transaction->update([
            'status' => 'Prepared'
        ]);

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order marked as prepared!'
            ]);
        }

        return back()->with('success', 'Order marked as prepared!');
    }

    /**
     * Assign logistics to an order
     */
    public function assignLogistics(Transaction $transaction)
    {
        // Check if the authenticated user is the farmer
        if (Auth::id() != $transaction->farmer_id) {
            abort(403);
        }

        // Update status
        $transaction->update([
            'status' => 'In Transit'
        ]);

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logistics assigned!'
            ]);
        }

        return back()->with('success', 'Logistics assigned!');
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

        // Delete the match
        $demandMatch->delete();

        // Return JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Match removed successfully.'
            ]);
        }

        // Redirect back with success message
        return back()->with('success', 'Match removed successfully.');
    }

    /**
     * Message a farmer about a product
     */
    public function messageFarmer(Product $product, Request $request)
    {
        // Check if the authenticated user is a buyer
        if (!Auth::user() || !Auth::user()->buyer) {
            return response()->json([
                'success' => false,
                'message' => 'You must be a buyer to message a farmer.'
            ]);
        }

        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity,
            'delivery_date' => 'required|date|after_or_equal:today',
            'message' => 'required|string|max:1000'
        ]);

        // Create a demand
        $demand = Demand::create([
            'buyer_id' => Auth::id(),
            'product_name' => $product->product_name,
            'quantity' => $request->quantity,
            'location' => Auth::user()->address ?? '',
            'delivery_date' => $request->delivery_date
        ]);

        // Create a demand match
        $demandMatch = DemandMatch::create([
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Pending',
            'matched_date' => now()
        ]);

        // Create a conversation thread
        $conversationThread = ConversationThread::create([
            'demand_match_id' => $demandMatch->id
        ]);

        // Create a transaction
        $transaction = Transaction::create([
            'demand_id' => $demand->id,
            'product_id' => $product->id,
            'buyer_id' => Auth::id(),
            'farmer_id' => $product->farmer->user_id,
            'conversation_thread_id' => $conversationThread->id,
            'final_quantity' => $request->quantity,
            'final_price' => $product->price,
            'total_amount' => $request->quantity * $product->price,
            'payment_status' => 'Pending',
            'delivery_status' => 'Scheduled',
            'status' => 'Active'
        ]);

        // Create the initial message
        Message::create([
            'conversation_thread_id' => $conversationThread->id,
            'sender_id' => Auth::id(),
            'message' => $request->message
        ]);

        // Notify the farmer
        $farmerUser = $product->farmer->user;
        if ($farmerUser) {
            $buyerName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $notificationData = [
                'message' => "{$buyerName} is interested in your product \"{$product->product_name}\". Please review and respond.",
                'data' => [
                    'product_name' => $product->product_name,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'unit' => $product->unit ?? 'units',
                    'price' => $product->price,
                    'harvest_date' => $product->harvest_date,
                    'image' => $product->image,
                    'buyer_name' => $buyerName,
                    'transaction_id' => $transaction->id
                ]
            ];
            
            // Send the notification
            $farmerUser->notify(new FarmerMatchNotification($notificationData));
        }

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'redirect' => route('transactions.show', $transaction->id)
        ]);
    }
}
