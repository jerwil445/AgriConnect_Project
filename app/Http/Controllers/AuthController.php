<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ])->withInput($request->only('email', 'password'));
        }

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->withInput($request->only('email', 'password'));
        }

        $user = Auth::user();

        // Check KYC status for non-admin users
        if ($user->role !== 'admin') {
            if ($user->kyc_status === 'pending') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is currently pending administrative approval. Please wait for the audit to complete.',
                ])->withInput($request->only('email'));
            }

            if ($user->kyc_status === 'rejected') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been rejected. Please contact support for more information.',
                ])->withInput($request->only('email'));
            }
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if (!$user) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to log you in right now. Please try again.',
            ]);
        }

        return match ($user->role) {
            'farmer' => redirect()->route('farmer.dashboard'),
            'buyer' => redirect()->route('buyer.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('home'),
        };
    }

    public function register(Request $request)
    {
        // Will implement tomorrow
        return back()->with('error', 'Registration functionality coming soon!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
