<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Demand;
use App\Models\DemandMatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

        if (!in_array($statusFilter, ['Available'], true)) {
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
            ->where('status', 'Available');

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
        $hasFilters =
            $search !== '' ||
            $varietySize !== '' ||
            $location !== '' ||
            $statusFilter !== '' ||
            $unitFilter !== '' ||
            $sort !== 'latest' ||
            $perPage !== 12;

        if ($request->ajax()) {
            return response(view('buyers.partials.product_grid', compact(
                'products',
                'search',
                'varietySize',
                'location',
                'statusFilter',
                'unitFilter',
                'sort',
                'perPage',
                'hasFilters'
            ))->render());
        }

        return response(view('buyers.dashboard', compact(
            'products',
            'search',
            'varietySize',
            'location',
            'statusFilter',
            'unitFilter',
            'sort',
            'perPage'
        )));
    }

    /**
     * Display the buyer profile.
     */
    public function showProfile()
    {
        $user = Auth::user();

        // Calculate Stats
        $totalDemands = \App\Models\Demand::where('buyer_id', $user->id)->count();
        $activeDemands = \App\Models\Demand::where('buyer_id', $user->id)->where('status', 'Available')->count();
        $totalOrders = \App\Models\Transaction::where('buyer_id', $user->id)->count();

        // Calculate Profile Completeness
        $fields = [
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->phone_number,
            $user->address,
            $user->profile_picture,
            $user->buyer?->company_name,
            $user->buyer?->business_type,
            $user->buyer?->categories,
            $user->buyer?->preferred_products,
            $user->buyer?->address
        ];
        $filled = count(array_filter($fields));
        $completeness = round(($filled / count($fields)) * 100);

        return view('buyers.profile', compact('user', 'totalDemands', 'activeDemands', 'totalOrders', 'completeness'));
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

        return response(view('buyers.products.show', compact('product')));
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

        return response(view('buyers.notifications.index', compact('notifications')));
    }

    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return response()->redirectToRoute('buyer.notifications');
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
            'categories' => 'nullable|string',
            'preferred_products' => 'nullable|string',
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
                'categories' => $request->input('categories'),
                'preferred_products' => $request->input('preferred_products'),
                'address' => $request->input('buyer_address'),
            ]);
        } else {
            // Create buyer profile if it doesn't exist
            $user->buyer()->create([
                'company_name' => $request->input('company_name'),
                'business_type' => $request->input('business_type'),
                'categories' => $request->input('categories'),
                'preferred_products' => $request->input('preferred_products'),
                'address' => $request->input('buyer_address'),
            ]);
        }

        return redirect()->route('buyer.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Display the buyer analytics page.
     */
    public function analytics()
    {
        if (!Auth::user()->buyer) {
            return redirect()->route('buyer.dashboard')->with('error', 'You must have a buyer profile to view analytics.');
        }

        $user = Auth::user();

        // 1. Core Metrics
        $totalSpent = Transaction::where('buyer_id', $user->id)
            ->whereIn('status', ['paid', 'completed', 'delivered', 'accepted', 'Accepted', 'Paid', 'Prepared', 'Assigned Logistics'])
            ->sum('total_amount');

        $activeDemandsCount = Demand::where('buyer_id', $user->id)
            ->where('status', 'Available')
            ->count();

        $completedOrdersCount = Transaction::where('buyer_id', $user->id)
            ->whereIn('status', ['paid', 'completed', 'delivered', 'accepted', 'Accepted', 'Paid', 'Prepared', 'Assigned Logistics'])
            ->count();

        // 2. Fulfillment Rate
        $totalDemands = Demand::where('buyer_id', $user->id)->count();
        $matchedDemands = Demand::where('buyer_id', $user->id)
            ->whereHas('matches', function ($query) {
                $query->whereIn('status', ['Matched', 'Transaction Started', 'Ordered']);
            })->count();
        $fulfillmentRate = $totalDemands > 0 ? round(($matchedDemands / $totalDemands) * 100, 1) : 0;

        // 3. Spending & Quantity by Product
        $productStats = Transaction::where('transactions.buyer_id', $user->id)
            ->leftJoin('products', 'transactions.product_id', '=', 'products.id')
            ->leftJoin('demands', 'transactions.demand_id', '=', 'demands.id')
            ->select(
                DB::raw("COALESCE(products.product_name, demands.product_name, 'Other Product') as product_name"),
                DB::raw('SUM(transactions.total_amount) as total_spent'),
                DB::raw('SUM(transactions.final_quantity) as total_quantity')
            )
            ->groupBy(DB::raw("COALESCE(products.product_name, demands.product_name, 'Other Product')"))
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // 4. Monthly Spending & Volume Trends
        $range = request('sourcing_range', 'monthly');
        $query = Transaction::where('transactions.buyer_id', $user->id);

        if ($range === 'yearly') {
            $monthlySpending = $query->where('transactions.created_at', '>=', now()->subYears(5))
                ->select(
                    DB::raw("to_char(transactions.created_at, 'YYYY') as month"),
                    DB::raw('SUM(transactions.total_amount) as total'),
                    DB::raw('SUM(transactions.final_quantity) as volume'),
                    DB::raw('MIN(transactions.created_at) as sort_date')
                )
                ->groupBy(DB::raw("to_char(transactions.created_at, 'YYYY')"))
                ->orderBy('sort_date')
                ->get();
        } elseif ($range === 'daily') {
            $monthlySpending = $query->where('transactions.created_at', '>=', now()->subDays(30))
                ->select(
                    DB::raw("to_char(transactions.created_at, 'Mon DD') as month"),
                    DB::raw('SUM(transactions.total_amount) as total'),
                    DB::raw('SUM(transactions.final_quantity) as volume'),
                    DB::raw('MIN(transactions.created_at) as sort_date')
                )
                ->groupBy(DB::raw("to_char(transactions.created_at, 'Mon DD')"))
                ->orderBy('sort_date')
                ->get();
        } else { // monthly (default)
            $monthlySpending = $query->where('transactions.created_at', '>=', now()->subMonths(12))
                ->select(
                    DB::raw("to_char(transactions.created_at, 'Mon YYYY') as month"),
                    DB::raw('SUM(transactions.total_amount) as total'),
                    DB::raw('SUM(transactions.final_quantity) as volume'),
                    DB::raw('MIN(transactions.created_at) as sort_date')
                )
                ->groupBy(DB::raw("to_char(transactions.created_at, 'Mon YYYY')"))
                ->orderBy('sort_date')
                ->get();
        }

        // 5. Calculate Spending Trend (vs Last Month)
        $thisMonthSpent = Transaction::where('buyer_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['paid', 'completed', 'delivered', 'accepted', 'Accepted', 'Paid', 'Prepared', 'Assigned Logistics'])
            ->sum('total_amount');
        $lastMonthSpent = Transaction::where('buyer_id', $user->id)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereIn('status', ['paid', 'completed', 'delivered', 'accepted', 'Accepted', 'Paid', 'Prepared', 'Assigned Logistics'])
            ->sum('total_amount');
        $spendingTrend = $lastMonthSpent > 0 ? (($thisMonthSpent - $lastMonthSpent) / $lastMonthSpent) * 100 : 0;

        // 5. Top Suppliers
        $topSuppliers = Transaction::where('transactions.buyer_id', $user->id)
            ->join('users as farmer_users', 'transactions.farmer_id', '=', 'farmer_users.id')
            ->select(
                'farmer_id',
                'farmer_users.first_name',
                'farmer_users.last_name',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_amount) as total_spent')
            )
            ->groupBy('farmer_id', 'farmer_users.first_name', 'farmer_users.last_name')
            ->orderByDesc('transaction_count')
            ->take(5)
            ->get();

        // Admin-style charts for Buyer Analytics
        $regionalDemand = \App\Models\Demand::selectRaw('province, COUNT(*) as demand_count')
            ->whereNotNull('province')
            ->groupBy('province')
            ->orderBy('demand_count', 'desc')
            ->limit(5)
            ->get();

        $matchStatusDistribution = \App\Models\DemandMatch::whereHas('demand', function($q) use ($user) {
                $q->where('buyer_id', $user->id);
            })
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $orderStatusDistribution = \App\Models\Transaction::where('buyer_id', $user->id)
            ->selectRaw('delivery_status as status, COUNT(*) as count')
            ->groupBy('delivery_status')
            ->get();

        return view('buyers.analytics', compact(
            'totalSpent',
            'activeDemandsCount',
            'completedOrdersCount',
            'fulfillmentRate',
            'productStats',
            'monthlySpending',
            'spendingTrend',
            'topSuppliers',
            'regionalDemand',
            'matchStatusDistribution',
            'orderStatusDistribution'
        ));
    }
}
