<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\DemandMatchingController;
use App\Http\Controllers\FarmerController;

// Public Routes
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

// Admin Routes with User CRUD
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{user}', [AdminController::class, 'show'])->name('admin.users.show');
Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

// Farmer Product Routes
Route::middleware('auth')->group(function () {
    Route::get('/farmer', function () {
        return view('farmers.dashboard');
    })->name('farmer.dashboard');
    
    Route::get('/farmer/profile', function () {
        return view('farmers.profile');
    })->name('farmer.profile');
    
    Route::get('/farmer/notifications', [FarmerController::class, 'notifications'])->name('farmer.notifications');
    Route::post('/farmer/notifications/{id}/read', [FarmerController::class, 'markNotificationAsRead'])->name('farmer.notifications.read');
    
    Route::resource('/farmer/products', ProductController::class);
    Route::put('/farmer/products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
});

// Buyer Routes
Route::middleware('auth')->group(function () {
    Route::get('/buyer', [BuyerController::class, 'dashboard'])->name('buyer.dashboard');
    Route::get('/buyer/profile', function () {
        return view('buyers.profile');
    })->name('buyer.profile');
    Route::get('/buyer/products/{product}', [BuyerController::class, 'showProduct'])->name('buyer.products.show');
});

// Demand and Matching Routes
Route::middleware('auth')->group(function () {
    Route::get('/demands', [DemandMatchingController::class, 'index'])->name('demands.index');
    Route::post('/demands', [DemandMatchingController::class, 'store'])->name('demands.store');
    Route::get('/demands/{demand}', [DemandMatchingController::class, 'show'])->name('demands.show');
    
    Route::get('/farmer/matches', [DemandMatchingController::class, 'farmerMatches'])->name('farmer.matches');
    Route::get('/farmer/products/{product}/matches', [DemandMatchingController::class, 'farmerProductMatches'])->name('farmer.product.matches');
    
    Route::post('/matches/{demandMatch}/accept', [DemandMatchingController::class, 'acceptMatch'])->name('matches.accept');
    Route::post('/matches/{demandMatch}/reject', [DemandMatchingController::class, 'rejectMatch'])->name('matches.reject');
});