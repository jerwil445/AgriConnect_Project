<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
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