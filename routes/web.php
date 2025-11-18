<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

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

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Registration POST route
Route::post('/register', [RegisterController::class, 'register']);
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
})->name('admin.dashboard');
Route::get('/buyer', function () {
    return view('buyers.dashboard');
})->name('buyer.dashboard');
Route::get('/farmer', function () {
    return view('farmers.dashboard');
})->name('farmer.dashboard');
