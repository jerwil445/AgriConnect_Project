<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        }
        
        return back();
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
}