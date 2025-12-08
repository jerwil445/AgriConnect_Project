<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\DemandMatch;
use Carbon\Carbon;

class FarmerController extends Controller
{
    /**
     * Display the farmer dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $farmerId = $user->id;
        
        // Get total products
        $totalProducts = Product::where('farmer_id', $user->farmer->id)->count();
        
        // Get available products
        $availableProducts = Product::where('farmer_id', $user->farmer->id)
            ->where('status', 'Available')
            ->count();
        
        // Get total matches
        $totalMatches = DemandMatch::whereHas('product', function($query) use ($user) {
            $query->where('farmer_id', $user->farmer->id);
        })->count();
        
        // Get new matches (status = 'New')
        $newMatches = DemandMatch::whereHas('product', function($query) use ($user) {
            $query->where('farmer_id', $user->farmer->id);
        })->where('status', 'New')->count();
        
        // Get pending orders (status = 'Ordered')
        $pendingOrders = Transaction::where('farmer_id', $farmerId)
            ->where('status', 'Ordered')
            ->count();
        
        // Get total sales (sum of completed transactions)
        $totalSales = Transaction::where('farmer_id', $farmerId)
            ->whereIn('status', ['Accepted', 'Prepared', 'In Transit', 'Delivered'])
            ->sum('total_amount');
        
        // Get active chats
        $activeChats = Transaction::where('farmer_id', $farmerId)
            ->whereIn('status', ['Transaction Started', 'Accepted', 'Prepared', 'In Transit'])
            ->count();
        
        // Get recent orders (last 5)
        $recentOrders = Transaction::where('farmer_id', $farmerId)
            ->with(['buyer', 'product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get monthly sales data (last 6 months)
        $monthlySales = Transaction::where('farmer_id', $farmerId)
            ->whereIn('status', ['Accepted', 'Prepared', 'In Transit', 'Delivered'])
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Get top products
        $topProducts = Product::where('farmer_id', $user->farmer->id)
            ->withCount('matches')
            ->orderBy('matches_count', 'desc')
            ->take(5)
            ->get();
        
        return view('farmers.dashboard', compact(
            'totalProducts',
            'availableProducts',
            'totalMatches',
            'newMatches',
            'pendingOrders',
            'totalSales',
            'activeChats',
            'recentOrders',
            'monthlySales',
            'topProducts'
        ));
    }

    /**
     * Display the farmer profile.
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('farmers.profile', compact('user'));
    }

    /**
     * Display the farmer profile edit form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('farmers.profile-edit', compact('user'));
    }

    /**
     * Update the farmer profile.
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
            // Farmer specific fields
            'farm_name' => 'nullable|string|max:255',
            'farm_size' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'certification' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:255',
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

        // Update farmer information
        if ($user->farmer) {
            $user->farmer->update($request->only([
                'farm_name',
                'farm_size',
                'experience_years',
                'certification',
                'farm_address'
            ]));
        } else {
            // Create farmer profile if it doesn't exist
            $user->farmer()->create($request->only([
                'farm_name',
                'farm_size',
                'experience_years',
                'certification',
                'farm_address'
            ]));
        }

        return redirect()->route('farmer.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Display farmer notifications.
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);
        
        return view('farmers.notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
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
     * Display farmer analytics.
     */
    public function analytics()
    {
        $user = Auth::user();
        $farmerId = $user->id;
        
        // Get total products
        $totalProducts = Product::where('farmer_id', $user->farmer->id)->count();
        
        // Get available products
        $availableProducts = Product::where('farmer_id', $user->farmer->id)
            ->where('status', 'Available')
            ->count();
        
        // Get sold out products
        $soldOutProducts = Product::where('farmer_id', $user->farmer->id)
            ->where('status', 'Sold Out')
            ->count();
        
        // Get total matches
        $totalMatches = DemandMatch::whereHas('product', function($query) use ($user) {
            $query->where('farmer_id', $user->farmer->id);
        })->count();
        
        // Get accepted matches
        $acceptedMatches = DemandMatch::whereHas('product', function($query) use ($user) {
            $query->where('farmer_id', $user->farmer->id);
        })->where('status', 'Matched')->count();
        
        // Get total orders
        $totalOrders = Transaction::where('farmer_id', $farmerId)->count();
        
        // Get completed orders
        $completedOrders = Transaction::where('farmer_id', $farmerId)
            ->where('status', 'Delivered')
            ->count();
        
        // Get total revenue
        $totalRevenue = Transaction::where('farmer_id', $farmerId)
            ->whereIn('status', ['Accepted', 'Prepared', 'In Transit', 'Delivered'])
            ->sum('total_amount');
        
        // Get monthly revenue (last 12 months)
        $monthlyRevenue = Transaction::where('farmer_id', $farmerId)
            ->whereIn('status', ['Accepted', 'Prepared', 'In Transit', 'Delivered'])
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Get order status breakdown
        $ordersByStatus = Transaction::where('farmer_id', $farmerId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Get top selling products
        $topProducts = Product::where('farmer_id', $user->farmer->id)
            ->withCount(['transactions' => function($query) {
                $query->whereIn('status', ['Accepted', 'Prepared', 'In Transit', 'Delivered']);
            }])
            ->orderBy('transactions_count', 'desc')
            ->take(5)
            ->get();
        
        // Get recent transactions
        $recentTransactions = Transaction::where('farmer_id', $farmerId)
            ->with(['buyer', 'product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Calculate average order value
        $avgOrderValue = Transaction::where('farmer_id', $farmerId)
            ->whereIn('status', ['Accepted', 'Prepared', 'In Transit', 'Delivered'])
            ->avg('total_amount');
        
        // Get match conversion rate
        $matchConversionRate = $totalMatches > 0 
            ? round(($totalOrders / $totalMatches) * 100, 2) 
            : 0;
        
        return view('farmers.analytics', compact(
            'totalProducts',
            'availableProducts',
            'soldOutProducts',
            'totalMatches',
            'acceptedMatches',
            'totalOrders',
            'completedOrders',
            'totalRevenue',
            'monthlyRevenue',
            'ordersByStatus',
            'topProducts',
            'recentTransactions',
            'avgOrderValue',
            'matchConversionRate'
        ));
    }
}