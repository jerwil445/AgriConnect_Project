<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class FarmerController extends Controller
{
    /**
     * Display the farmer dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Active Listings
        $activeListings = 0;
        if ($user->farmer) {
            $activeListings = \App\Models\Product::where('farmer_id', $user->farmer->id)
                ->where('status', 'Available')
                ->count();
        }

        // Pending Orders
        $pendingOrders = \App\Models\Transaction::where('farmer_id', $user->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->count();

        // Total Sales amount for completed transactions
        $totalSales = \App\Models\Transaction::where('farmer_id', $user->id)
            ->where('status', 'completed')
            ->sum('final_price');

        // Recent Activity (Transactions)
        $activities = \App\Models\Transaction::with('product')
            ->where('farmer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($t) {
                return [
                    'icon' => 'fa-seedling',
                    'title' => 'Order #' . $t->id . ' – ' . ($t->product ? $t->product->product_name : 'Item') . ' (' . $t->final_quantity . ')',
                    'time' => $t->created_at->diffForHumans()
                ];
            });

        // Current Market Demands - top requested products
        $marketDemands = \App\Models\Demand::select('product_name', \Illuminate\Support\Facades\DB::raw('count(*) as buyers_count'))
            ->groupBy('product_name')
            ->orderByDesc('buyers_count')
            ->take(3)
            ->get();

        // 3. Low Inventory Warnings
        $farmerId = $user->farmer ? $user->farmer->id : 0;
        $lowInventory = \App\Models\Product::where('farmer_id', $farmerId)
            ->where('status', 'Available')
            ->where('quantity', '<=', 50) // Assumed generic threshold
            ->orderBy('quantity', 'asc')
            ->take(3)
            ->get();

        // 4. Upcoming Deliveries
        $upcomingDeliveries = \App\Models\Transaction::with(['buyer', 'product'])
            ->where('farmer_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        return view('farmers.dashboard', compact(
            'user',
            'activeListings',
            'pendingOrders',
            'totalSales',
            'activities',
            'marketDemands',
            'lowInventory',
            'upcomingDeliveries'
        ));
    }

    /**
     * Display the farmer analytics page.
     */
    public function analytics()
    {
        $user = Auth::user();

        // 1. Revenue Trends (Last 30 days)
        $revenueData = \App\Models\Transaction::where('farmer_id', $user->id)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(updated_at) as date, SUM(final_price) as total_revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $trendLabels = [];
        $trendValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $displayDate = now()->subDays($i)->format('M d');
            $trendLabels[] = $displayDate;

            $record = $revenueData->firstWhere('date', $date);
            $trendValues[] = $record ? (float) $record->total_revenue : 0;
        }

        // 2. Top Performing Products
        $topProducts = \App\Models\Transaction::with('product')
            ->where('farmer_id', $user->id)
            ->where('status', 'completed')
            ->selectRaw('product_id, COUNT(*) as sales_count, SUM(final_price) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        $productLabels = $topProducts->map(function ($transaction) {
            return $transaction->product ? $transaction->product->product_name : 'Unknown Product';
        });
        $productRevenues = $topProducts->pluck('total_revenue');

        // 3. Order Success Rate
        $statusCounts = \App\Models\Transaction::where('farmer_id', $user->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $completed = $statusCounts->where('status', 'completed')->first()->count ?? 0;
        $rejected = $statusCounts->whereIn('status', ['rejected', 'declined'])->sum('count') ?? 0;
        $cancelled = $statusCounts->where('status', 'cancelled')->first()->count ?? 0;

        $orderRateData = [$completed, $cancelled, $rejected];

        // Overall stats
        $totalRevenue = \App\Models\Transaction::where('farmer_id', $user->id)->where('status', 'completed')->sum('final_price');
        $totalOrders = \App\Models\Transaction::where('farmer_id', $user->id)->count();

        return view('farmers.analytics', compact(
            'totalRevenue',
            'totalOrders',
            'topProducts',
            'trendLabels',
            'trendValues',
            'productLabels',
            'productRevenues',
            'orderRateData',
            'user'
        ));
    }

    /**
     * Display the farmer profile.
     */
    public function showProfile()
    {
        $user = Auth::user();

        // Calculate Stats
        $totalProducts = \App\Models\Product::where('farmer_id', $user->id)->count();
        if ($user->farmer) {
            $totalProducts = \App\Models\Product::where('farmer_id', $user->farmer->id)->count();
        }

        $activeListings = \App\Models\Product::where('farmer_id', $user->id)->where('status', 'Available')->count();
        if ($user->farmer) {
            $activeListings = \App\Models\Product::where('farmer_id', $user->farmer->id)->where('status', 'Available')->count();
        }

        $totalSales = \App\Models\Transaction::where('farmer_id', $user->id)->where('status', 'completed')->count();

        // Calculate Profile Completeness
        $fields = [
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->phone_number,
            $user->address,
            $user->profile_picture,
            $user->farmer?->farm_name,
            $user->farmer?->farm_size,
            $user->farmer?->product_type,
            $user->farmer?->experience_years,
            $user->farmer?->farm_address
        ];
        $filled = count(array_filter($fields));
        $completeness = round(($filled / count($fields)) * 100);

        return view('farmers.profile', compact('user', 'totalProducts', 'activeListings', 'totalSales', 'completeness'));
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
            'product_type' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'certification' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:255',
        ]);

        // Update user information
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ];
        $user->update($userData);

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
                'product_type',
                'experience_years',
                'certification',
                'farm_address'
            ]));
        } else {
            // Create farmer profile if it doesn't exist
            $user->farmer()->create($request->only([
                'farm_name',
                'farm_size',
                'product_type',
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
}