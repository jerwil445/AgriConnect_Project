<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // Will implement tomorrow
        return back()->with('error', 'Login functionality coming soon!');
    }

    public function register(Request $request)
    {
        // Will implement tomorrow
        return back()->with('error', 'Registration functionality coming soon!');
    }

    public function logout(Request $request)
    {
        // Will implement tomorrow
        return redirect('/')->with('success', 'Logout functionality coming soon!');
    }
}
