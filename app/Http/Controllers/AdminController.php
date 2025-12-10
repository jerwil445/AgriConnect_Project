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
        $totalProducts = Product::count(); // Count all products, not just available
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
            ->selectRaw('products.egg_type, COUNT(transactions.id) as transaction_count')
            ->groupBy('products.egg_type')
            ->orderBy('transaction_count', 'desc')
            ->limit(5)
            ->get();
        
        // Regional Demand (top 5 locations by demand count)
        $regionalDemand = Demand::selectRaw('location, COUNT(*) as demand_count')
            ->groupBy('location')
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
            $productsQuery->where('egg_type', 'LIKE', "%{$search}%");
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
        $product->load('sizes');
        return view('admin.products.show', compact('product'));
    }
    
    /**
     * Remove the specified product from storage.
     */
    public function deleteProduct(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            \Storage::delete($product->image);
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
            $demandsQuery->where('egg_type', 'LIKE', "%{$search}%");
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
            'egg_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'delivery_date' => 'required|date',
            'buyer_id' => 'required|exists:users,id',
            'egg_type' => 'nullable|string|max:50',
            'egg_size' => 'nullable|string|max:255',
            'small_trays' => 'nullable|integer|min:1',
            'medium_trays' => 'nullable|integer|min:1',
            'large_trays' => 'nullable|integer|min:1',
            'extra_large_trays' => 'nullable|integer|min:1',
            'jumbo_trays' => 'nullable|integer|min:1',
        ]);
        
        // Process egg sizes if they come from checkboxes
        $eggSize = $request->input('egg_size');
        if ($request->has('egg_sizes')) {
            $eggSizes = $request->input('egg_sizes');
            $processedSizes = [];
            
            foreach ($eggSizes as $size) {
                $trayCount = null;
                
                switch ($size) {
                    case 'small':
                        $trayCount = $request->input('small_trays');
                        break;
                    case 'medium':
                        $trayCount = $request->input('medium_trays');
                        break;
                    case 'large':
                        $trayCount = $request->input('large_trays');
                        break;
                    case 'extra_large':
                        $trayCount = $request->input('extra_large_trays');
                        break;
                    case 'jumbo':
                        $trayCount = $request->input('jumbo_trays');
                        break;
                }
                
                if ($trayCount) {
                    $processedSizes[] = "{$size} ({$trayCount} tray" . ($trayCount > 1 ? 's' : '') . ')';
                } else {
                    $processedSizes[] = $size;
                }
            }
            
            $eggSize = implode(', ', $processedSizes);
        }
        
        $demandData = $request->except(['_token', '_method', 'egg_sizes', 'small_trays', 'medium_trays', 'large_trays', 'extra_large_trays', 'jumbo_trays']);
        $demandData['egg_size'] = $eggSize;
        $demandData['egg_type'] = $request->input('egg_type');
        
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
                $query->where('egg_type', 'LIKE', "%{$search}%");
            })->orWhereHas('demand', function ($query) use ($search) {
                $query->where('egg_type', 'LIKE', "%{$search}%");
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
                $query->where('egg_type', 'LIKE', "%{$search}%");
            })->orWhereHas('demand', function ($query) use ($search) {
                $query->where('egg_type', 'LIKE', "%{$search}%");
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
            $usersQuery->where(function($query) use ($search) {
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
            'email' => 'required|email|unique:users,email,'.$user->id,
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
            'experience_years' => 'nullable|integer|min:0',
            'certification' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:255',
        ]);

        $farmer = Farmer::where('user_id', $user->id)->first();
        if ($farmer) {
            $farmer->update($request->only([
                'farm_name',
                'farm_size',
                'experience_years',
                'certification',
                'farm_address'
            ]));
        } else {
            Farmer::create(array_merge(
                $request->only([
                    'farm_name',
                    'farm_size',
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
            $buyer->update($request->only([
                'company_name',
                'business_type',
                'preferred_products',
                'buyer_address',
                'verified'
            ]));
        } else {
            Buyer::create(array_merge(
                $request->only([
                    'company_name',
                    'business_type',
                    'preferred_products',
                    'buyer_address',
                    'verified'
                ]),
                ['user_id' => $user->id]
            ));
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