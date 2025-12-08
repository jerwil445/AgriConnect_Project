# Farmer Module Integration Guide

## ✅ Database Integration Complete!

All new database tables and fields have been successfully integrated into the Farmer Module system. This document outlines the complete integration and how to use the new features.

---

## 📦 Updated Models

### 1. **Farmer Model** (`app/Models/Farmer.php`)

#### New Fillable Fields (40+ added):
```php
'business_registration_number', 'business_type', 'tax_id_number',
'secondary_phone', 'whatsapp_number',
'bank_name', 'bank_account_number', 'bank_account_name',
'mobile_wallet_provider', 'mobile_wallet_number',
'organic_certification', 'certification_expiry_date',
'latitude', 'longitude', 'total_chickens', 'farming_method',
'average_rating', 'total_reviews', 'completed_orders', 'success_rate',
'verification_status', 'verified_at', 'verified_by',
'is_active', 'accepting_orders', 'operation_start_time', 'operation_end_time'
```

#### New Relationships:
- `reviews()` - HasMany FarmerReview
- `inventoryLogs()` - HasMany InventoryLog
- `activityLogs()` - HasMany FarmerActivityLog
- `earnings()` - HasMany FarmerEarning

#### New Helper Methods:
```php
$farmer->isVerified()           // Check if farmer is verified
$farmer->canAcceptOrders()       // Check if can accept new orders
$farmer->updateRating()          // Recalculate average rating
$farmer->incrementCompletedOrders() // Increment order count
$farmer->updateSuccessRate()     // Update success percentage
```

---

### 2. **Product Model** (`app/Models/Product.php`)

#### New Fillable Fields (60+ added):
```php
'quality_grade', 'color', 'average_weight_grams', 'is_organic', 'is_free_range',
'laying_date', 'expiry_date', 'storage_condition', 'storage_temperature',
'initial_quantity', 'sold_quantity', 'reserved_quantity', 'available_quantity',
'minimum_order_quantity', 'maximum_order_quantity', 'reorder_level',
'original_price', 'discount_percentage', 'is_negotiable',
'province', 'city', 'barangay', 'postal_code',
'delivery_radius_km', 'offers_delivery', 'offers_pickup', 'delivery_fee',
'fda_approved', 'batch_number', 'view_count', 'inquiry_count', 'order_count',
'is_featured', 'is_promoted', 'featured_until', 'priority_order'
```

#### New Relationships:
- `inventoryLogs()` - HasMany InventoryLog
- `analytics()` - HasMany ProductAnalytic
- `reviews()` - HasMany FarmerReview

#### New Helper Methods:
```php
$product->incrementView()              // Track product views
$product->incrementInquiry()           // Track inquiries
$product->incrementOrder()             // Track orders
$product->updateConversionRate()       // Calculate conversion
$product->updateInventory($qty, 'sale') // Update stock
$product->calculateEffectivePrice()    // Get discounted price
$product->isAvailable()                // Check availability
$product->isFeatured()                 // Check featured status
```

---

### 3. **Size Model** (`app/Models/Size.php`)

#### New Fillable Fields (20+ added):
```php
'initial_tray_count', 'sold_tray_count', 'reserved_tray_count', 'available_tray_count',
'eggs_per_tray', 'weight_per_egg_grams', 'total_weight_kg',
'original_price_per_tray', 'discount_percentage', 'has_special_offer',
'availability_status', 'low_stock_threshold', 'allow_backorder',
'order_count', 'popularity_score', 'last_sold_at'
```

#### New Relationships:
- `sizeTransactions()` - HasMany SizeTransaction
- `inventoryLogs()` - HasMany InventoryLog

#### New Helper Methods:
```php
$size->updateInventory($trayChange, 'sale')  // Update tray count
$size->incrementOrder()                      // Track sales
$size->calculateEffectivePrice()             // Get special offer price
$size->isAvailable()                         // Check stock status
```

---

## 🆕 New Models

### 4. **FarmerReview Model** (`app/Models/FarmerReview.php`)

Track buyer feedback and ratings.

```php
// Create a review
FarmerReview::create([
    'farmer_id' => $farmer->id,
    'buyer_id' => $buyer->id,
    'transaction_id' => $transaction->id,
    'overall_rating' => 4.5,
    'product_quality_rating' => 5.0,
    'comment' => 'Great quality eggs!',
]);

// Farmer replies to review
$review->addReply('Thank you for your feedback!');

// Moderation
$review->approve();
$review->reject();
$review->flag();
```

---

### 5. **InventoryLog Model** (`app/Models/InventoryLog.php`)

Complete audit trail of stock movements.

```php
// Log a sale
InventoryLog::logSale($product, $quantity, $transactionId, $userId);

// Log a restock
InventoryLog::logRestock($product, $quantity, $userId, 'New harvest');

// Custom log
InventoryLog::logInventoryChange([
    'farmer_id' => $farmer->id,
    'product_id' => $product->id,
    'type' => 'damage',
    'quantity_before' => 100,
    'quantity_change' => -5,
    'quantity_after' => 95,
    'reason' => 'Broken during transport',
]);
```

---

### 6. **ProductAnalytic Model** (`app/Models/ProductAnalytic.php`)

Daily performance metrics.

```php
// Record daily metrics
$analytic = ProductAnalytic::recordDailyMetrics($productId);

// Track views
$analytic->incrementView();
$analytic->incrementUniqueView();
$analytic->incrementDetailView();

// Track inquiry
$analytic->incrementInquiry();

// Track order
$analytic->incrementOrder($revenue, $units);

// Get analytics for date range
$analytics = ProductAnalytic::where('product_id', $productId)
    ->whereBetween('date', [$startDate, $endDate])
    ->get();
```

---

### 7. **FarmerActivityLog Model** (`app/Models/FarmerActivityLog.php`)

Audit trail for all farmer actions.

```php
// Log product creation
FarmerActivityLog::logCreate(
    $farmer,
    $user,
    'Product',
    $product->id,
    'Created new product: Chicken Eggs',
    ['egg_type' => 'chicken', 'quantity' => 100]
);

// Log product update
FarmerActivityLog::logUpdate(
    $farmer,
    $user,
    'Product',
    $product->id,
    'Updated product price',
    ['price' => 100],
    ['price' => 120]
);

// Log deletion
FarmerActivityLog::logDelete(
    $farmer,
    $user,
    'Product',
    $product->id,
    'Deleted expired product',
    $product->toArray()
);
```

---

### 8. **FarmerEarning Model** (`app/Models/FarmerEarning.php`)

Financial tracking and payouts.

```php
// Create earning record from transaction
$earning = FarmerEarning::createFromTransaction($transaction, 5); // 5% platform fee

// Mark as paid
$earning->markAsPaid('bank_transfer', 'REF12345', $adminUserId);

// Put on hold
$earning->markAsOnHold('Verification required');

// Get unpaid earnings
$unpaid = FarmerEarning::unpaid()
    ->where('farmer_id', $farmer->id)
    ->sum('net_amount');

// Get earnings for period
$monthlyEarnings = FarmerEarning::forPeriod('2025-12')
    ->where('farmer_id', $farmer->id)
    ->get();
```

---

## 🔄 Usage Examples

### Example 1: Create Product with Full Details

```php
$product = Product::create([
    'farmer_id' => $farmer->id,
    'egg_type' => 'chicken',
    'quality_grade' => 'AA',
    'color' => 'Brown',
    'is_organic' => true,
    'is_free_range' => true,
    'quantity' => 100,
    'initial_quantity' => 100,
    'available_quantity' => 100,
    'unit' => 'trays',
    'price' => 150,
    'harvest_date' => now(),
    'expiry_date' => now()->addDays(30),
    'storage_condition' => 'Refrigerated',
    'province' => 'Davao del Sur',
    'city' => 'Davao City',
    'offers_delivery' => true,
    'delivery_fee' => 50,
    'status' => 'Available',
]);

// Log the creation
FarmerActivityLog::logCreate(
    $farmer,
    $user,
    'Product',
    $product->id,
    'Created new organic product',
    $product->toArray()
);
```

### Example 2: Process Order with Inventory Tracking

```php
// When order is placed
$product->updateInventory($orderQuantity, 'reservation');
InventoryLog::logInventoryChange([
    'farmer_id' => $farmer->id,
    'product_id' => $product->id,
    'type' => 'reservation',
    'quantity_before' => $product->quantity + $orderQuantity,
    'quantity_change' => -$orderQuantity,
    'quantity_after' => $product->quantity,
    'transaction_id' => $transaction->id,
    'performed_by' => $buyer->id,
    'reason' => 'Order placed',
]);

// When order is confirmed
$product->updateInventory($orderQuantity, 'sale');
$product->incrementOrder();

// Create earning record
$earning = FarmerEarning::createFromTransaction($transaction);

// Track analytics
$analytic = ProductAnalytic::recordDailyMetrics($product->id);
$analytic->incrementOrder($transaction->total_amount, $orderQuantity);
```

### Example 3: Handle Customer Review

```php
// Buyer submits review
$review = FarmerReview::create([
    'farmer_id' => $farmer->id,
    'buyer_id' => $buyer->id,
    'transaction_id' => $transaction->id,
    'product_id' => $product->id,
    'overall_rating' => 5.0,
    'product_quality_rating' => 5.0,
    'delivery_rating' => 4.5,
    'communication_rating' => 5.0,
    'packaging_rating' => 5.0,
    'comment' => 'Excellent quality and fast delivery!',
    'status' => 'approved',
    'is_verified_purchase' => true,
]);

// Update farmer's rating
$farmer->updateRating();

// Farmer replies
$review->addReply('Thank you so much for your kind words!');
```

### Example 4: Update Farmer Verification

```php
// Admin verifies farmer
$farmer->verification_status = 'verified';
$farmer->verified_at = now();
$farmer->verified_by = $admin->id;
$farmer->verification_notes = 'All documents verified';
$farmer->save();

// Log the verification
FarmerActivityLog::logActivity([
    'farmer_id' => $farmer->id,
    'user_id' => $admin->id,
    'action' => 'verified',
    'entity_type' => 'Farmer',
    'entity_id' => $farmer->id,
    'description' => 'Farmer account verified by admin',
    'severity' => 'info',
]);
```

---

## 🎯 Controller Integration

Update your controllers to use the new features:

### ProductController Example:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'egg_type' => 'required',
        'quantity' => 'required|integer',
        'price' => 'required|numeric',
        'is_organic' => 'boolean',
        'is_free_range' => 'boolean',
        'province' => 'nullable|string',
        'city' => 'nullable|string',
        'offers_delivery' => 'boolean',
        'delivery_fee' => 'nullable|numeric',
        // ... more fields
    ]);

    $product = Product::create(array_merge($validated, [
        'farmer_id' => auth()->user()->farmer->id,
        'initial_quantity' => $validated['quantity'],
        'available_quantity' => $validated['quantity'],
        'published_at' => now(),
        'status' => 'Available',
    ]));

    // Log the activity
    FarmerActivityLog::logCreate(
        auth()->user()->farmer,
        auth()->user(),
        'Product',
        $product->id,
        'Created product: ' . $product->egg_type,
        $product->toArray()
    );

    return redirect()->route('products.index')
        ->with('success', 'Product created successfully!');
}
```

---

## 📊 Analytics Dashboard Integration

Use the new models in your analytics:

```php
public function analytics()
{
    $farmer = auth()->user()->farmer;
    
    // Get earnings summary
    $totalEarnings = FarmerEarning::where('farmer_id', $farmer->id)
        ->sum('net_amount');
    
    $unpaidEarnings = FarmerEarning::unpaid()
        ->where('farmer_id', $farmer->id)
        ->sum('net_amount');
    
    // Get product performance
    $topProducts = Product::where('farmer_id', $farmer->id)
        ->orderBy('order_count', 'desc')
        ->take(5)
        ->get();
    
    // Get recent activities
    $recentActivities = FarmerActivityLog::where('farmer_id', $farmer->id)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    // Get average rating
    $averageRating = $farmer->average_rating;
    $totalReviews = $farmer->total_reviews;
    
    return view('farmers.analytics', compact(
        'totalEarnings',
        'unpaidEarnings',
        'topProducts',
        'recentActivities',
        'averageRating',
        'totalReviews'
    ));
}
```

---

## ✨ Summary

### What's Integrated:

✅ **3 Enhanced Models** - Farmer, Product, Size with 130+ new fields
✅ **5 New Models** - FarmerReview, InventoryLog, ProductAnalytic, FarmerActivityLog, FarmerEarning
✅ **Soft Deletes** - Data recovery capability
✅ **Helper Methods** - Convenient methods for common operations
✅ **Relationships** - Proper Eloquent relationships throughout
✅ **Type Casting** - Automatic data type conversion
✅ **Activity Logging** - Complete audit trail
✅ **Financial Tracking** - Transparent earnings management
✅ **Performance Metrics** - Analytics and insights

### Ready to Use:

- Farmer verification system
- Product inventory management
- Customer review system
- Earnings and payouts
- Activity logging
- Performance analytics

---

**Next Steps:**
1. Update your controllers to use new features
2. Update views to display new information
3. Test all new functionality
4. Add validation rules for new fields
5. Update API documentation if applicable

All models are fully integrated and ready to use! 🎉
