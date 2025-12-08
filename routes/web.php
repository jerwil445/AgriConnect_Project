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
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{user}', [AdminController::class, 'show'])->name('admin.users.show');
Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

// Admin Product Routes
Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products.index');
Route::get('/admin/products/{product}', [AdminController::class, 'viewProduct'])->name('admin.products.view');
Route::get('/admin/products/{product}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
Route::put('/admin/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
Route::get('/admin/products/{product}/approve', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
Route::get('/admin/products/{product}/reject', [AdminController::class, 'rejectProduct'])->name('admin.products.reject');
Route::delete('/admin/products/{product}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

// Admin Demand Routes
Route::get('/admin/demands', [AdminController::class, 'demands'])->name('admin.demands.index');
Route::get('/admin/demands/{demand}', [AdminController::class, 'viewDemand'])->name('admin.demands.view');
Route::get('/admin/demands/{demand}/edit', [AdminController::class, 'editDemand'])->name('admin.demands.edit');
Route::put('/admin/demands/{demand}', [AdminController::class, 'updateDemand'])->name('admin.demands.update');
Route::get('/admin/demands/{demand}/audit', [AdminController::class, 'auditDemand'])->name('admin.demands.audit');
Route::delete('/admin/demands/{demand}', [AdminController::class, 'deleteDemand'])->name('admin.demands.delete');

// Admin Match Routes
Route::get('/admin/matches', [AdminController::class, 'matches'])->name('admin.matches.index');
Route::post('/admin/matches/{match}/accept', [AdminController::class, 'acceptMatch'])->name('admin.matches.accept');
Route::post('/admin/matches/{match}/reject', [AdminController::class, 'rejectMatch'])->name('admin.matches.reject');
Route::get('/admin/matches/{match}', [AdminController::class, 'viewMatch'])->name('admin.matches.view');
Route::delete('/admin/matches/{match}', [AdminController::class, 'deleteMatch'])->name('admin.matches.delete');

// Admin Transaction Routes
Route::get('/admin/transactions', [AdminController::class, 'transactions'])->name('admin.transactions.index');
Route::get('/admin/transactions/{transaction}', [AdminController::class, 'viewTransaction'])->name('admin.transactions.view');

// Farmer Product Routes
Route::middleware('auth')->group(function () {
    Route::get('/farmer', [FarmerController::class, 'dashboard'])->name('farmer.dashboard');
    
    Route::get('/farmer/profile', [FarmerController::class, 'showProfile'])->name('farmer.profile');
    Route::get('/farmer/profile/edit', [FarmerController::class, 'editProfile'])->name('farmer.profile.edit');
    Route::put('/farmer/profile', [FarmerController::class, 'updateProfile'])->name('farmer.profile.update');
    
    Route::get('/farmer/notifications', [FarmerController::class, 'notifications'])->name('farmer.notifications');
    Route::post('/farmer/notifications/{id}/read', [FarmerController::class, 'markNotificationAsRead'])->name('farmer.notifications.read');
    
    Route::get('/farmer/analytics', [FarmerController::class, 'analytics'])->name('farmer.analytics');
    
    // Earnings & Payouts
    Route::get('/farmer/earnings', [FarmerController::class, 'earnings'])->name('farmer.earnings');
    
    // Reviews & Ratings
    Route::get('/farmer/reviews', [FarmerController::class, 'reviews'])->name('farmer.reviews');
    Route::post('/farmer/reviews/{review}/reply', [FarmerController::class, 'replyToReview'])->name('farmer.reviews.reply');
    
    // Activity & Inventory Logs
    Route::get('/farmer/activities', [FarmerController::class, 'activityLogs'])->name('farmer.activities');
    Route::get('/farmer/inventory/logs', [FarmerController::class, 'inventoryLogs'])->name('farmer.inventory.logs');
    
    Route::resource('/farmer/products', ProductController::class);
    Route::put('/farmer/products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
    
    // Farmer messages route
    Route::get('/farmer/messages', [DemandMatchingController::class, 'listTransactions'])->name('farmer.messages');
    
    // Order tracking route for farmer
    Route::get('/farmer/orders', [DemandMatchingController::class, 'listOrders'])->name('farmer.orders');
});

// Buyer Routes
Route::middleware('auth')->group(function () {
    Route::get('/buyer', [BuyerController::class, 'dashboard'])->name('buyer.dashboard');
    Route::get('/buyer/profile', [BuyerController::class, 'showProfile'])->name('buyer.profile');
    Route::get('/buyer/profile/edit', [BuyerController::class, 'editProfile'])->name('buyer.profile.edit');
    Route::put('/buyer/profile', [BuyerController::class, 'updateProfile'])->name('buyer.profile.update');
    Route::get('/buyer/products/{product}', [BuyerController::class, 'showProduct'])->name('buyer.products.show');
    Route::get('/buyer/notifications', [BuyerController::class, 'notifications'])->name('buyer.notifications');
    Route::post('/buyer/notifications/{id}/read', [BuyerController::class, 'markNotificationAsRead'])->name('buyer.notifications.read');
    
    // Buyer messages route
    Route::get('/buyer/messages', [DemandMatchingController::class, 'listTransactions'])->name('buyer.messages');
    
    // Order tracking route for buyer
    Route::get('/buyer/orders', [DemandMatchingController::class, 'listOrders'])->name('buyer.orders');
});

// Demand and Matching Routes
Route::middleware('auth')->group(function () {
    Route::get('/demands', [DemandMatchingController::class, 'index'])->name('demands.index');
    Route::post('/demands', [DemandMatchingController::class, 'store'])->name('demands.store');
    Route::get('/demands/{demand}', [DemandMatchingController::class, 'show'])->name('demands.show');
    Route::delete('/demands/{demand}', [DemandMatchingController::class, 'destroy'])->name('demands.destroy');
    
    Route::get('/farmer/matches', [DemandMatchingController::class, 'farmerMatches'])->name('farmer.matches');
    Route::get('/farmer/products/{product}/matches', [DemandMatchingController::class, 'farmerProductMatches'])->name('farmer.product.matches');
    Route::post('/buyer/products/{product}/message-farmer', [DemandMatchingController::class, 'messageFarmer'])->name('buyer.message-farmer');
    
    Route::post('/matches/{demandMatch}/accept', [DemandMatchingController::class, 'acceptMatch'])->name('matches.accept');
    Route::post('/matches/{demandMatch}/reject', [DemandMatchingController::class, 'rejectMatch'])->name('matches.reject');
    Route::delete('/matches/{demandMatch}', [DemandMatchingController::class, 'destroyMatch'])->name('matches.destroy');
    Route::post('/matches/{demandMatch}/start-conversation', [DemandMatchingController::class, 'startConversation'])->name('matches.startConversation');
    
    // Transaction and Messaging Routes
    Route::post('/matches/{demandMatch}/start-transaction', [DemandMatchingController::class, 'startTransaction'])->name('matches.startTransaction');
    Route::get('/transactions/{transaction}', [DemandMatchingController::class, 'showTransaction'])->name('transactions.show');
    Route::get('/orders/{transaction}', [DemandMatchingController::class, 'showOrder'])->name('orders.show');
    Route::post('/transactions/{transaction}/messages', [DemandMatchingController::class, 'sendMessage'])->name('transactions.sendMessage');
    Route::get('/messages/unread-count', [DemandMatchingController::class, 'getUnreadMessagesCount'])->name('messages.unreadCount');
    Route::get('/messages/unread-count-by-conversation', [DemandMatchingController::class, 'getUnreadMessagesCountByConversation'])->name('messages.unreadCountByConversation');
    Route::get('/messages/conversation/{transaction}', [DemandMatchingController::class, 'loadConversation'])->name('messages.loadConversation');
    Route::get('/messages/transaction-details/{transaction}', [DemandMatchingController::class, 'loadTransactionDetails'])->name('messages.loadTransactionDetails');
    
    // Order placement route
    Route::post('/transactions/{transaction}/order', [DemandMatchingController::class, 'placeOrder'])->name('transactions.order');
    
    // Order action routes
    Route::post('/transactions/{transaction}/mark-paid', [DemandMatchingController::class, 'markOrderAsPaid'])->name('transactions.markPaid');
    Route::post('/transactions/{transaction}/mark-delivered', [DemandMatchingController::class, 'markOrderAsDelivered'])->name('transactions.markDelivered');
    Route::post('/transactions/{transaction}/accept', [DemandMatchingController::class, 'acceptOrder'])->name('transactions.accept');
    Route::post('/transactions/{transaction}/reject', [DemandMatchingController::class, 'rejectOrder'])->name('transactions.reject');
    Route::post('/transactions/{transaction}/mark-prepared', [DemandMatchingController::class, 'markOrderAsPrepared'])->name('transactions.markPrepared');
    Route::post('/transactions/{transaction}/assign-logistics', [DemandMatchingController::class, 'assignLogistics'])->name('transactions.assignLogistics');
    Route::post('/transactions/{transaction}/mark-delivered-by-buyer', [DemandMatchingController::class, 'markOrderAsDeliveredByBuyer'])->name('transactions.markDeliveredByBuyer');
    
    // List all transactions for a user
    Route::get('/transactions', [DemandMatchingController::class, 'listTransactions'])->name('transactions.index');
});