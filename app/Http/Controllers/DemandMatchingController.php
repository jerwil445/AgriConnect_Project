<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Product;
use App\Models\DemandMatch;
use App\Notifications\FarmerMatchNotification;
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
            'delivery_date' => 'required|date|after:today',
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
                    'status' => 'Pending',
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
            
            // Notify the farmer about the buyer's interest
            $farmerUser = $demandMatch->product->farmer->user;
            $farmerUser->notify(new FarmerMatchNotification('A buyer is interested in your product. Please review and accept the match to start negotiation.'));
            
            // Return JSON response for AJAX requests to show popup
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Waiting for Farmer\'s Acceptance...'
                ]);
            }
            
            return back()->with('success', 'Waiting for Farmer\'s Acceptance...');
        }
        // Check if the user is a farmer accepting the match
        else if (Auth::id() == $demandMatch->product->farmer->user_id) {
            // Update the match status to Matched
            $demandMatch->update([
                'status' => 'Matched'
            ]);
            
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
}