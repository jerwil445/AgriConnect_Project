<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate form input
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required|in:farmer,buyer',
            'phone_number'   => 'nullable|string|max:20',
            'city-region'   => 'nullable|string|max:50',
            'full-address'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'phone_number'=> $request->phone_number,
            'address'     => $request->address,
            'kyc_status'  => 'pending',
        ]);

        // Create related farmer or buyer record
        if ($request->role === 'farmer') {
            Farmer::create([
                'user_id' => $user->id,
                'farm_name' => $request->farm_name ?? 'Unnamed Farm',
                'farm_size' => $request->farm_size ?? null,
                'product_type' => $request->product_type ?? null,
                'experience_years' => $request->experience_years ?? null,
                'certification' => $request->certification ?? null,
                'farm_address' => $request->farm_address ?? null,
            ]);
        } elseif ($request->role === 'buyer') {
            Buyer::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name ?? null,
                'business_type' => $request->business_type ?? null,
                'preferred_products' => $request->preferred_products ?? null,
                'address' => $request->address ?? null,
            ]);
        }

        // Redirect back with success message
        return redirect()->back()->with('success', ucfirst($request->role) . ' registered successfully!');
    }
}
