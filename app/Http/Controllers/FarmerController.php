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
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Basic Farmer Fields
            'farm_name' => 'required|string|max:255',
            'farm_size' => 'nullable|numeric|min:0',
            'farm_size_unit' => 'nullable|in:hectares,acres,sq_meters',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'certification' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:500',
            
            // Business Information
            'business_registration_number' => 'nullable|string|max:255',
            'business_type' => 'nullable|in:Individual,Partnership,Corporation',
            'tax_id_number' => 'nullable|string|max:255',
            
            // Contact & Communication
            'secondary_phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            
            // Payment Information
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'mobile_wallet_provider' => 'nullable|string|max:255',
            'mobile_wallet_number' => 'nullable|string|max:20',
            
            // Certifications
            'organic_certification' => 'nullable|string|max:255',
            'certification_expiry_date' => 'nullable|date',
            'food_safety_certification' => 'nullable|string|max:255',
            'gmp_certified' => 'nullable|boolean',
            'halal_certified' => 'nullable|boolean',
            
            // Farm Details
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'total_chickens' => 'nullable|integer|min:0',
            'farming_method' => 'nullable|string|max:255',
            
            // Operational
            'accepting_orders' => 'nullable|boolean',
            'operation_start_time' => 'nullable|date_format:H:i',
            'operation_end_time' => 'nullable|date_format:H:i',
            'operation_days' => 'nullable|array',
        ]);

        $farmer = $user->farmer;
        $oldValues = $farmer ? $farmer->toArray() : [];

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
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->update(['profile_picture' => $profilePicturePath]);
        }

        // Prepare farmer data
        $farmerData = $request->only([
            'farm_name', 'farm_size', 'farm_size_unit', 'experience_years', 
            'certification', 'farm_address', 'business_registration_number',
            'business_type', 'tax_id_number', 'secondary_phone', 'whatsapp_number',
            'bank_name', 'bank_account_number', 'bank_account_name',
            'mobile_wallet_provider', 'mobile_wallet_number', 'organic_certification',
            'certification_expiry_date', 'food_safety_certification', 'gmp_certified',
            'halal_certified', 'latitude', 'longitude', 'total_chickens',
            'farming_method', 'accepting_orders', 'operation_start_time',
            'operation_end_time', 'operation_days'
        ]);

        // Update or create farmer information
        if ($farmer) {
            $farmer->update($farmerData);
        } else {
            $farmer = $user->farmer()->create($farmerData);
        }

        // Log the activity
        \App\Models\FarmerActivityLog::logUpdate(
            $farmer,
            $user,
            'Farmer',
            $farmer->id,
            'Updated farmer profile',
            $oldValues,
            $farmer->fresh()->toArray()
        );

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
            return response()->json(['success' => true, 'message' => 'Notification marked as read']);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
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

    /**
     * Display farmer earnings and payouts
     */
    public function earnings()
    {
        $farmer = Auth::user()->farmer;
        
        // Get earnings summary
        $totalEarnings = \App\Models\FarmerEarning::where('farmer_id', $farmer->id)
            ->sum('net_amount');
        
        $paidEarnings = \App\Models\FarmerEarning::where('farmer_id', $farmer->id)
            ->where('payout_status', 'paid')
            ->sum('net_amount');
        
        $unpaidEarnings = \App\Models\FarmerEarning::where('farmer_id', $farmer->id)
            ->where('payout_status', 'unpaid')
            ->sum('net_amount');
        
        $pendingEarnings = \App\Models\FarmerEarning::where('farmer_id', $farmer->id)
            ->where('payout_status', 'pending')
            ->sum('net_amount');
        
        // Get earnings history
        $earnings = \App\Models\FarmerEarning::where('farmer_id', $farmer->id)
            ->with('transaction')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Monthly earnings chart
        $monthlyEarnings = \App\Models\FarmerEarning::where('farmer_id', $farmer->id)
            ->select(
                DB::raw('MONTH(earning_date) as month'),
                DB::raw('YEAR(earning_date) as year'),
                DB::raw('SUM(net_amount) as total'),
                DB::raw('SUM(platform_fee) as fees')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
        
        return view('farmers.earnings.index', compact(
            'totalEarnings',
            'paidEarnings',
            'unpaidEarnings',
            'pendingEarnings',
            'earnings',
            'monthlyEarnings'
        ));
    }

    /**
     * Display farmer reviews and ratings
     */
    public function reviews()
    {
        $farmer = Auth::user()->farmer;
        
        // Get all reviews
        $reviews = \App\Models\FarmerReview::where('farmer_id', $farmer->id)
            ->with(['buyer', 'transaction', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Rating breakdown
        $ratingBreakdown = \App\Models\FarmerReview::where('farmer_id', $farmer->id)
            ->select(
                DB::raw('ROUND(overall_rating) as rating'),
                DB::raw('count(*) as count')
            )
            ->groupBy('rating')
            ->get()
            ->keyBy('rating');
        
        // Average ratings
        $avgRatings = \App\Models\FarmerReview::where('farmer_id', $farmer->id)
            ->select(
                DB::raw('AVG(overall_rating) as overall'),
                DB::raw('AVG(product_quality_rating) as quality'),
                DB::raw('AVG(delivery_rating) as delivery'),
                DB::raw('AVG(communication_rating) as communication'),
                DB::raw('AVG(packaging_rating) as packaging')
            )
            ->first();
        
        return view('farmers.reviews.index', compact(
            'reviews',
            'ratingBreakdown',
            'avgRatings',
            'farmer'
        ));
    }

    /**
     * Reply to a review
     */
    public function replyToReview(Request $request, $reviewId)
    {
        $farmer = Auth::user()->farmer;
        $review = \App\Models\FarmerReview::where('farmer_id', $farmer->id)
            ->findOrFail($reviewId);
        
        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);
        
        $review->addReply($validated['reply']);
        
        // Log activity
        \App\Models\FarmerActivityLog::logActivity([
            'farmer_id' => $farmer->id,
            'user_id' => Auth::id(),
            'action' => 'replied',
            'entity_type' => 'Review',
            'entity_id' => $review->id,
            'description' => 'Replied to customer review',
            'severity' => 'info',
        ]);
        
        return redirect()->back()->with('success', 'Reply posted successfully!');
    }

    /**
     * Display activity logs
     */
    public function activityLogs()
    {
        $farmer = Auth::user()->farmer;
        
        $activities = \App\Models\FarmerActivityLog::where('farmer_id', $farmer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('farmers.activities.index', compact('activities'));
    }

    /**
     * Display inventory logs
     */
    public function inventoryLogs()
    {
        $farmer = Auth::user()->farmer;
        
        $logs = \App\Models\InventoryLog::where('farmer_id', $farmer->id)
            ->with(['product', 'size', 'performedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('farmers.inventory.logs', compact('logs'));
    }
}