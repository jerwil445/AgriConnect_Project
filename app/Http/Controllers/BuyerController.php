<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Farmer;
use App\Models\FarmerReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuyerController extends Controller
{
    /**
     * Display the buyer dashboard with all available products.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $search = $request->input('search');
        $location = $request->input('location');
        $category = $request->input('category');
        $status = $request->input('status', 'all');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minQuantity = $request->input('min_quantity');
        $maxQuantity = $request->input('max_quantity');
        $certification = $request->input('certification', []);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Build the query
        $query = Product::with(['farmer.user', 'sizes']);
        
        // Apply status filter
        if ($status === 'Available') {
            $query->where('status', 'Available');
        } else {
            $query->whereIn('status', ['Available', 'Sold Out']);
        }

        // Apply search filter
        if ($search) {
            $query->where('egg_type', 'LIKE', "%{$search}%");
        }

        // Apply location filter
        if ($location) {
            $query->whereHas('farmer.user', function($q) use ($location) {
                $q->where('address', 'LIKE', "%{$location}%");
            });
        }

        // Apply category filter (egg type)
        if ($category && $category !== 'all') {
            $query->where('egg_type', 'LIKE', "%{$category}%");
        }

        // Apply price range filter
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Apply quantity range filter
        if ($minQuantity) {
            $query->where('quantity', '>=', $minQuantity);
        }
        if ($maxQuantity) {
            $query->where('quantity', '<=', $maxQuantity);
        }

        // Apply certification filter
        if (!empty($certification)) {
            $query->whereHas('farmer', function($q) use ($certification) {
                $q->whereIn('certification', $certification);
            });
        }

        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'quantity':
                $query->orderBy('quantity', 'desc');
                break;
            case 'harvest_date':
                $query->orderBy('harvest_date', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Paginate results
        $products = $query->paginate(12)->appends($request->query());

        // Get unique egg types for category filter
        $eggTypes = Product::select('egg_type')
            ->distinct()
            ->whereNotNull('egg_type')
            ->pluck('egg_type')
            ->sort();

        return view('buyers.dashboard', compact('products', 'eggTypes', 'search', 'location', 'category', 'status', 'minPrice', 'maxPrice', 'minQuantity', 'maxQuantity', 'certification', 'sortBy'));
    }
    
    /**
     * Display the buyer profile.
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('buyers.profile', compact('user'));
    }
    
    /**
     * Display the specified product details.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showProduct(Product $product)
    {
        // Load the farmer, user information, and product images
        $product->load('farmer.user', 'images');
        
        return view('buyers.products.show', compact('product'));
    }
    
    /**
     * Display buyer notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);
        
        return view('buyers.notifications.index', compact('notifications'));
    }
    
    /**
     * Mark a notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true, 'message' => 'Notification marked as read']);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
    
    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }
    
    /**
     * Display the buyer profile edit form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('buyers.profile-edit', compact('user'));
    }

    /**
     * Update the buyer profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Buyer specific fields
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
            'preferred_products' => 'nullable|string|max:255',
            'buyer_address' => 'nullable|string|max:255',
        ]);

        // Update user information
        $user->update($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'address'
        ]));

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Store new profile picture
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->update(['profile_picture' => $profilePicturePath]);
        }

        // Update buyer information
        if ($user->buyer) {
            $user->buyer->update($request->only([
                'company_name',
                'business_type',
                'preferred_products',
                'buyer_address'
            ]));
        } else {
            // Create buyer profile if it doesn't exist
            $user->buyer()->create($request->only([
                'company_name',
                'business_type',
                'preferred_products',
                'buyer_address'
            ]));
        }

        return redirect()->route('buyer.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the review form for a completed order
     */
    public function showReviewForm(Transaction $transaction)
    {
        $user = Auth::user();
        
        // Check if the user is the buyer for this transaction
        if ($transaction->buyer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Check if order is delivered
        if ($transaction->status !== 'Delivered') {
            return redirect()->route('buyer.orders')
                ->with('error', 'You can only review completed orders.');
        }
        
        // Check if already reviewed
        if ($transaction->farmerReview) {
            return redirect()->route('buyer.orders')
                ->with('info', 'You have already reviewed this order.');
        }
        
        $transaction->load('product', 'farmer');
        
        return view('buyers.reviews.create', compact('transaction'));
    }

    /**
     * Submit a review for a completed order
     */
    public function submitReview(Request $request, Transaction $transaction)
    {
        $user = Auth::user();
        
        // Check if the user is the buyer for this transaction
        if ($transaction->buyer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Check if order is delivered
        if ($transaction->status !== 'Delivered') {
            return redirect()->route('buyer.orders')
                ->with('error', 'You can only review completed orders.');
        }
        
        // Check if already reviewed
        if ($transaction->farmerReview) {
            return redirect()->route('buyer.orders')
                ->with('error', 'You have already reviewed this order.');
        }
        
        // Validate the review
        $validated = $request->validate([
            'overall_rating' => 'required|numeric|min:1|max:5',
            'product_quality_rating' => 'required|numeric|min:1|max:5',
            'delivery_rating' => 'required|numeric|min:1|max:5',
            'communication_rating' => 'required|numeric|min:1|max:5',
            'packaging_rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        // Get the farmer profile
        $farmer = Farmer::where('user_id', $transaction->farmer_id)->first();
        
        if (!$farmer) {
            return redirect()->route('buyer.orders')
                ->with('error', 'Unable to submit review: Farmer profile not found.');
        }
        
        // Create the review
        FarmerReview::create([
            'farmer_id' => $farmer->id,
            'buyer_id' => $user->id,
            'transaction_id' => $transaction->id,
            'product_id' => $transaction->product_id,
            'overall_rating' => $validated['overall_rating'],
            'product_quality_rating' => $validated['product_quality_rating'],
            'delivery_rating' => $validated['delivery_rating'],
            'communication_rating' => $validated['communication_rating'],
            'packaging_rating' => $validated['packaging_rating'],
            'comment' => $validated['comment'],
            'status' => 'approved',
            'is_verified_purchase' => true,
        ]);
        
        // Update farmer's rating statistics
        $farmer->updateRating();
        
        return redirect()->route('buyer.orders')
            ->with('success', 'Thank you for your review!');
    }
}