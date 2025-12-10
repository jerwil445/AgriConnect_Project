<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if (!$user) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to log you in right now. Please try again.',
            ]);
        }

        // Check if farmer or buyer needs admin verification
        if (in_array($user->role, ['farmer', 'buyer'])) {
            if ($user->kyc_status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is pending verification by an administrator. Please wait for approval.',
                ])->withInput($request->only('email'));
            }

            if ($user->kyc_status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been rejected. Please contact the administrator for more information.',
                ])->withInput($request->only('email'));
            }
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
