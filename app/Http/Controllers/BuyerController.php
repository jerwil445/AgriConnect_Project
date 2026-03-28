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
        $search = trim((string) $request->input('search', ''));
        $varietySize = trim((string) $request->input('variety_size', ''));
        $location = trim((string) $request->input('location', ''));
        $statusFilter = $request->input('status');
        $unitFilter = $request->input('unit');
        $sort = $request->input('sort', 'latest');
        $perPage = (int) $request->input('per_page', 12);

        if (!in_array($statusFilter, ['Available', 'Sold Out'], true)) {
            $statusFilter = '';
        }

        if (!in_array($unitFilter, ['pieces', 'trays', 'dozen', 'kilos', 'boxes', 'bunches', 'sacks'], true)) {
            $unitFilter = '';
        }

        if (!in_array($sort, ['latest', 'name_asc', 'price_low', 'price_high', 'harvest_soon'], true)) {
            $sort = 'latest';
        }

        if (!in_array($perPage, [6, 12, 24, 48], true)) {
            $perPage = 12;
        }

        $productsQuery = Product::with('farmer.user', 'remainingInventory')
            ->whereIn('status', ['Available', 'Sold Out']);

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('product_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('variety_size', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('barangay', 'LIKE', '%' . $search . '%')
                    ->orWhere('municipality_city', 'LIKE', '%' . $search . '%')
                    ->orWhere('province', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('farmer.user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        if ($statusFilter !== '') {
            $productsQuery->where('status', $statusFilter);
        }

        if ($unitFilter !== '') {
            $productsQuery->where('unit', $unitFilter);
        }

        if ($varietySize !== '') {
            $productsQuery->where('variety_size', 'LIKE', '%' . $varietySize . '%');
        }

        if ($location !== '') {
            $productsQuery->where(function ($query) use ($location) {
                $query->where('purok_street', 'LIKE', '%' . $location . '%')
                    ->orWhere('barangay', 'LIKE', '%' . $location . '%')
                    ->orWhere('municipality_city', 'LIKE', '%' . $location . '%')
                    ->orWhere('province', 'LIKE', '%' . $location . '%');
            });
        }

        match ($sort) {
            'name_asc' => $productsQuery->orderBy('product_name'),
            'price_low' => $productsQuery->orderBy('price'),
            'price_high' => $productsQuery->orderByDesc('price'),
            'harvest_soon' => $productsQuery->orderBy('harvest_date'),
            default => $productsQuery->orderByDesc('created_at'),
        };

        $products = $productsQuery
            ->paginate($perPage)
            ->withQueryString();
        
        return view('buyers.dashboard', compact(
            'products',
            'search',
            'varietySize',
            'location',
            'statusFilter',
            'unitFilter',
            'sort',
            'perPage'
        ));
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
        $product->load('farmer.user', 'images', 'remainingInventory');
        
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
            $user->buyer->update([
                'company_name' => $request->input('company_name'),
                'business_type' => $request->input('business_type'),
                'preferred_products' => $request->input('preferred_products'),
                'address' => $request->input('buyer_address'),
            ]);
        } else {
            // Create buyer profile if it doesn't exist
            $user->buyer()->create([
                'company_name' => $request->input('company_name'),
                'business_type' => $request->input('business_type'),
                'preferred_products' => $request->input('preferred_products'),
                'address' => $request->input('buyer_address'),
            ]);
        }

        return redirect()->route('buyer.profile')->with('success', 'Profile updated successfully.');
    }
}
