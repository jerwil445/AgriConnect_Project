<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public Routes
Route::get('/', function () {
    return view('landing.index');
})->name('home');
Route::get('/', function () {
    return view('landing.index');
})->name('home');

Route::get('/error', function () {
    return view('error.404');
})->name('error');

Route::get('/login', function ()  {
    return view('auth.login');
})->name('login');

Route::get('/register', function ()  {
    return view('auth.register');
})->name('register');
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard.index');
//     })->name('dashboard');
// });
/*
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Test route to verify CDN is working
Route::get('/test-cdn', function () {
    return view('test-cdn');
});

// Authentication routes (we'll implement these tomorrow)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
 */



Route::get('/admin', function () {
    return view('layouts.admin_page');
});
Route::get('/buyer', function () {
    return view('buyers.dashboard');
});
Route::get('/farmer', function () {
    return view('farmers.dashboard');
});
