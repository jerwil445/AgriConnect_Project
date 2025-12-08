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
            'phone_number'   => 'required|string|max:20',
            'city_region'    => 'required|string|max:255',
            'address'  => 'required|string|max:255',
            // Farmer fields
            'farm_name' => 'nullable|string|max:255',
            'farm_size' => 'required_if:role,farmer|numeric|min:0',
            'experience_years' => 'required_if:role,farmer|numeric|min:0|max:100',
            // Buyer fields
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'required_if:role,buyer|string|max:50',
            'preferred_products' => 'required_if:role,buyer|string|max:255',
            'buyer_address' => 'required_if:role,buyer|string|max:255',
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
                'farm_size' => $request->farm_size !== null ? (float)$request->farm_size : null,
                'experience_years' => $request->experience_years ? (int)$request->experience_years : null,
                'certification' => null,
                'farm_address' => $request->address ?? null,
            ]);
        } elseif ($request->role === 'buyer') {
            Buyer::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name ?? null,
                'business_type' => $request->business_type ?? null,
                'preferred_products' => $request->preferred_products ?? null,
                'address' => $request->buyer_address ?? null,
            ]);
        }

        // Redirect to login with success message
        return redirect()->route('login')->with('success', ucfirst($request->role) . ' registered successfully! Please login.');
    }
}
