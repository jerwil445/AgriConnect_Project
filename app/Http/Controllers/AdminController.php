<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\Demand;
use App\Models\DemandMatch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with statistics.
     */
    public function dashboard()
    {
        // Summary Stats
        $totalUsers = User::count();
        $totalFarmers = User::where('role', 'farmer')->count();
        $totalBuyers = User::where('role', 'buyer')->count();
        $totalProducts = Product::where('status', 'Available')->count();
        $totalDemands = Demand::count();
        $totalMatches = DemandMatch::count();
        $totalTransactions = Transaction::count();

        // Pending notifications (simplified - in a real app, you might want to count unread messages, pending orders, etc.)
        $pendingNotifications = DemandMatch::where('status', 'Pending')->count();

        // Sales/Revenue Trends (last 7 days)
        $salesTrends = Transaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Product Popularity (top 5 products by transaction count)
        $productPopularity = Transaction::join('products', 'transactions.product_id', '=', 'products.id')
            ->selectRaw('products.product_name, COUNT(transactions.id) as transaction_count')
            ->groupBy('products.product_name')
            ->orderBy('transaction_count', 'desc')
            ->limit(5)
            ->get();

        // Regional Demand (top 5 locations by demand count)
        // Use province field instead of the old location field
        $regionalDemand = Demand::selectRaw('province, COUNT(*) as demand_count')
            ->whereNotNull('province')
            ->groupBy('province')
            ->orderBy('demand_count', 'desc')
            ->limit(5)
            ->get();

        // Match Status Distribution
        $matchStatusDistribution = DemandMatch::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Active Orders by Status
        $orderStatusDistribution = Transaction::selectRaw('delivery_status, COUNT(*) as count')
            ->groupBy('delivery_status')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFarmers',
            'totalBuyers',
            'totalProducts',
            'totalDemands',
            'totalMatches',
            'totalTransactions',
            'pendingNotifications',
            'salesTrends',
            'productPopularity',
            'regionalDemand',
            'matchStatusDistribution',
            'orderStatusDistribution'
        ));
    }

    /**
     * Display a listing of the products.
     */
    public function products(Request $request)
    {
        $search = $request->input('search');
        $farmerFilter = $request->input('farmer');
        $statusFilter = $request->input('status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $perPage = $request->input('per_page', 10);

        // Query products with relationships
        $productsQuery = Product::with(['farmer.user']);

        // Apply search filter
        if ($search) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('variety_size', 'LIKE', "%{$search}%");
            });
        }

        // Apply farmer filter
        if ($farmerFilter) {
            $productsQuery->whereHas('farmer', function ($query) use ($farmerFilter) {
                $query->where('user_id', $farmerFilter);
            });
        }

        // Apply status filter
        if ($statusFilter) {
            $productsQuery->where('status', $statusFilter);
        }

        // Apply sorting
        $productsQuery->orderBy($sortBy, $sortDirection);

        // Paginate results
        $products = $productsQuery->paginate($perPage)->appends([
            'search' => $search,
            'farmer' => $farmerFilter,
            'status' => $statusFilter,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'per_page' => $perPage
        ]);

        // Get farmers for filter dropdown
        $farmers = User::where('role', 'farmer')
            ->with('farmer')
            ->get()
            ->sortBy('first_name');

        // Get unique statuses for filter dropdown
        $statuses = Product::select('status')->distinct()->pluck('status');

        return view('admin.products.index', compact('products', 'farmers', 'statuses', 'search', 'farmerFilter', 'statusFilter', 'sortBy', 'sortDirection'));
    }

    /**
     * Display the specified product.
     */
    public function viewProduct(Product $product)
    {
        $product->load('farmer.user', 'images', 'remainingInventory');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'variety_size' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Pending,Sold Out',
            'harvest_date' => 'nullable|date',
            'purok_street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productData = $request->except(['_token', '_method', 'images']);
        $productData['total_amount'] = number_format(
            ((float) $request->input('quantity')) * ((float) $request->input('price')),
            2,
            '.',
            ''
        );

        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $product->images()->delete();

            foreach ($request->file('images') as $index => $image) {
                if (!$image || !$image->isValid()) {
                    continue;
                }

                $imagePath = $image->store('products', 'public');

                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => $index === 0,
                ]);

                if ($index === 0) {
                    $productData['image'] = $imagePath;
                }
            }
        }

        $product->update($productData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Approve the specified product.
     */
    public function approveProduct(Product $product)
    {
        $product->update(['status' => 'Available']);

        return redirect()->route('admin.products.index')->with('success', 'Product approved successfully.');
    }

    /**
     * Reject the specified product.
     */
    public function rejectProduct(Product $product)
    {
        $product->update(['status' => 'Pending']);

        return redirect()->route('admin.products.index')->with('success', 'Product rejected successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function deleteProduct(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Display a listing of the demands.
     */
    public function demands(Request $request)
    {
        $search = $request->input('search');
        $buyerFilter = $request->input('buyer');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $perPage = $request->input('per_page', 10);

        // Query demands with relationships
        $demandsQuery = Demand::with(['buyer', 'matches']);

        // Apply search filter
        if ($search) {
            $demandsQuery->where(function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('variety_size', 'LIKE', "%{$search}%");
            });
        }

        // Apply buyer filter
        if ($buyerFilter) {
            $demandsQuery->where('buyer_id', $buyerFilter);
        }

        // Apply sorting
        $demandsQuery->orderBy($sortBy, $sortDirection);

        // Paginate results
        $demands = $demandsQuery->paginate($perPage)->appends([
            'search' => $search,
            'buyer' => $buyerFilter,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'per_page' => $perPage
        ]);

        // Get buyers for filter dropdown
        $buyers = User::where('role', 'buyer')
            ->with('buyer')
            ->get()
            ->sortBy('first_name');

        return view('admin.demands.index', compact('demands', 'buyers', 'search', 'buyerFilter', 'sortBy', 'sortDirection'));
    }

    /**
     * Display the specified demand.
     */
    public function viewDemand(Demand $demand)
    {
        return view('admin.demands.show', compact('demand'));
    }

    /**
     * Show the form for editing the specified demand.
     */
    public function editDemand(Demand $demand)
    {
        $buyers = User::where('role', 'buyer')
            ->with('buyer')
            ->get()
            ->sortBy('first_name');

        return view('admin.demands.edit', compact('demand', 'buyers'));
    }

    /**
     * Update the specified demand in storage.
     */
    public function updateDemand(Request $request, Demand $demand)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'variety_size' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'purok_street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'delivery_date' => 'required|date',
            'deadline' => 'nullable|date|after_or_equal:delivery_date',
            'status' => 'required|string|max:50',
            'buyer_id' => 'required|exists:users,id',
        ]);

        $demandData = $request->only([
            'product_name',
            'variety_size',
            'quantity',
            'unit',
            'purok_street',
            'barangay',
            'municipality_city',
            'province',
            'delivery_date',
            'deadline',
            'status',
            'buyer_id',
        ]);

        $demand->update($demandData);

        return redirect()->route('admin.demands.index')->with('success', 'Demand updated successfully.');
    }

    /**
     * Audit matches for the specified demand.
     */
    public function auditDemand(Demand $demand)
    {
        $demand->load(['buyer', 'matches.product.farmer.user']);
        return view('admin.demands.audit', compact('demand'));
    }

    /**
     * Display a listing of the matches.
     */
    public function matches(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $perPage = $request->input('per_page', 10);

        // Query matches with relationships
        $matchesQuery = DemandMatch::with(['product.farmer.user', 'demand.buyer']);

        // Apply search filter
        if ($search) {
            $matchesQuery->whereHas('product', function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%{$search}%");
            })->orWhereHas('demand', function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('variety_size', 'LIKE', "%{$search}%");
            });
        }

        // Apply status filter
        if ($statusFilter) {
            $matchesQuery->where('status', $statusFilter);
        }

        // Apply sorting
        $matchesQuery->orderBy($sortBy, $sortDirection);

        // Paginate results
        $matches = $matchesQuery->paginate($perPage)->appends([
            'search' => $search,
            'status' => $statusFilter,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'per_page' => $perPage
        ]);

        // Get unique statuses for filter dropdown
        $statuses = DemandMatch::select('status')->distinct()->pluck('status');

        return view('admin.matches.index', compact('matches', 'statuses', 'search', 'statusFilter', 'sortBy', 'sortDirection'));
    }

    /**
     * Display the specified match.
     */
    public function viewMatch(DemandMatch $match)
    {
        $match->load(['demand.buyer', 'product.farmer.user']);
        return view('admin.matches.show', compact('match'));
    }

    /**
     * Remove the specified match from storage.
     */
    public function deleteMatch(DemandMatch $match)
    {
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', 'Match deleted successfully.');
    }

    /**
     * Accept a match.
     */
    public function acceptMatch(DemandMatch $match)
    {
        $match->update(['status' => 'Accepted']);

        return back()->with('success', 'Match accepted successfully.');
    }

    /**
     * Reject a match.
     */
    public function rejectMatch(DemandMatch $match)
    {
        $match->update(['status' => 'Rejected']);

        return back()->with('success', 'Match rejected successfully.');
    }

    /**
     * Display a listing of the transactions.
     */
    public function transactions(Request $request)
    {
        $search = $request->input('search');
        $paymentStatusFilter = $request->input('payment_status');
        $deliveryStatusFilter = $request->input('delivery_status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $perPage = $request->input('per_page', 10);

        // Query transactions with relationships
        $transactionsQuery = Transaction::with(['buyer', 'farmer', 'product', 'demand']);

        // Apply search filter
        if ($search) {
            $transactionsQuery->whereHas('product', function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%{$search}%");
            })->orWhereHas('demand', function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('variety_size', 'LIKE', "%{$search}%");
            })->orWhereHas('buyer', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%");
            })->orWhereHas('farmer', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        // Apply payment status filter
        if ($paymentStatusFilter) {
            $transactionsQuery->where('payment_status', $paymentStatusFilter);
        }

        // Apply delivery status filter
        if ($deliveryStatusFilter) {
            $transactionsQuery->where('delivery_status', $deliveryStatusFilter);
        }

        // Apply sorting
        $transactionsQuery->orderBy($sortBy, $sortDirection);

        // Paginate results
        $transactions = $transactionsQuery->paginate($perPage)->appends([
            'search' => $search,
            'payment_status' => $paymentStatusFilter,
            'delivery_status' => $deliveryStatusFilter,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'per_page' => $perPage
        ]);

        // Get unique statuses for filter dropdowns
        $paymentStatuses = Transaction::select('payment_status')->distinct()->pluck('payment_status');
        $deliveryStatuses = Transaction::select('delivery_status')->distinct()->pluck('delivery_status');

        return view('admin.transactions.index', compact('transactions', 'paymentStatuses', 'deliveryStatuses', 'search', 'paymentStatusFilter', 'deliveryStatusFilter', 'sortBy', 'sortDirection'));
    }

    /**
     * Display the specified transaction.
     */
    public function viewTransaction(Transaction $transaction)
    {
        $transaction->load(['buyer', 'farmer', 'product', 'demand']);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Remove the specified demand from storage.
     */
    public function deleteDemand(Demand $demand)
    {
        $demand->delete();

        return redirect()->route('admin.demands.index')->with('success', 'Demand deleted successfully.');
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $roleFilter = $request->input('role');
        $kycStatusFilter = $request->input('kyc_status');

        // Query users with search functionality and order by id descending
        $usersQuery = User::query()->orderBy('id', 'desc');

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('role', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        }

        // Apply role filter
        if ($roleFilter) {
            $usersQuery->where('role', $roleFilter);
        }

        // Apply KYC status filter
        if ($kycStatusFilter) {
            $usersQuery->where('kyc_status', $kycStatusFilter);
        }

        // Paginate results
        $users = $usersQuery->paginate($perPage)->appends([
            'search' => $search,
            'per_page' => $perPage,
            'role' => $roleFilter,
            'kyc_status' => $kycStatusFilter
        ]);

        return view('admin.users.index', compact('users', 'search', 'roleFilter', 'kycStatusFilter'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,farmer,buyer',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'kyc_status' => 'nullable|in:pending,verified,rejected',
        ]);

        $userData = $request->except('password_confirmation');
        $userData['password'] = bcrypt($request->password);

        User::create($userData);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Load related farmer or buyer information
        if ($user->role === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            return view('admin.users.show', compact('user', 'farmer'));
        } elseif ($user->role === 'buyer') {
            $buyer = Buyer::where('user_id', $user->id)->first();
            return view('admin.users.show', compact('user', 'buyer'));
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Load related farmer or buyer information
        if ($user->role === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            return view('admin.users.edit', compact('user', 'farmer'));
        } elseif ($user->role === 'buyer') {
            $buyer = Buyer::where('user_id', $user->id)->first();
            return view('admin.users.edit', compact('user', 'buyer'));
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // If this is a KYC status update from the dropdown
        if ($request->has('kyc_status') && !$request->has('first_name')) {
            $request->validate([
                'kyc_status' => 'required|in:pending,verified,rejected',
            ]);

            $user->update(['kyc_status' => $request->kyc_status]);

            return redirect()->route('admin.users.index')->with('success', 'KYC status updated successfully.');
        }

        // Regular user update
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,farmer,buyer',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'kyc_status' => 'nullable|in:pending,verified,rejected',
        ]);

        $userData = $request->except(['_token', '_method']);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $userData['password'] = bcrypt($request->password);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        // Update farmer or buyer information if applicable
        if ($user->role === 'farmer') {
            $this->updateFarmerInfo($request, $user);
        } elseif ($user->role === 'buyer') {
            $this->updateBuyerInfo($request, $user);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Update farmer information.
     */
    private function updateFarmerInfo(Request $request, User $user)
    {
        $request->validate([
            'farm_name' => 'nullable|string|max:255',
            'farm_size' => 'nullable|string|max:255',
            'product_type' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'certification' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:255',
        ]);

        $farmer = Farmer::where('user_id', $user->id)->first();
        if ($farmer) {
            $farmer->update($request->only([
                'farm_name',
                'farm_size',
                'product_type',
                'experience_years',
                'certification',
                'farm_address'
            ]));
        } else {
            Farmer::create(array_merge(
                $request->only([
                    'farm_name',
                    'farm_size',
                    'product_type',
                    'experience_years',
                    'certification',
                    'farm_address'
                ]),
                ['user_id' => $user->id]
            ));
        }
    }

    /**
     * Update buyer information.
     */
    private function updateBuyerInfo(Request $request, User $user)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
            'preferred_products' => 'nullable|string|max:255',
            'buyer_address' => 'nullable|string|max:255',
            'verified' => 'nullable|boolean',
        ]);

        $buyer = Buyer::where('user_id', $user->id)->first();
        if ($buyer) {
            $buyer->update([
                'company_name' => $request->input('company_name'),
                'business_type' => $request->input('business_type'),
                'preferred_products' => $request->input('preferred_products'),
                'address' => $request->input('buyer_address'),
                'verified' => $request->boolean('verified'),
            ]);
        } else {
            Buyer::create([
                'user_id' => $user->id,
                'company_name' => $request->input('company_name'),
                'business_type' => $request->input('business_type'),
                'preferred_products' => $request->input('preferred_products'),
                'address' => $request->input('buyer_address'),
                'verified' => $request->boolean('verified'),
            ]);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
