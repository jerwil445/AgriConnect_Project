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
    public function dashboard()
    {
        // Fetch all products with their farmer information, sizes, and remaining inventory
        // Show both available and sold out products
        $products = Product::with('farmer.user', 'sizes', 'remainingInventory')
            ->whereIn('status', ['Available', 'Sold Out'])
            ->paginate(12);
        
        return view('buyers.dashboard', compact('products'));
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
        // Load the farmer, user information, product images, sizes, and remaining inventory
        $product->load('farmer.user', 'images', 'sizes', 'remainingInventory');
        
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