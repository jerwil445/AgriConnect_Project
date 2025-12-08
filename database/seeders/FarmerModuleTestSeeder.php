<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Transaction;
use App\Models\FarmerEarning;
use App\Models\FarmerReview;
use App\Models\FarmerActivityLog;
use App\Models\InventoryLog;
use App\Models\Product;
use Carbon\Carbon;

class FarmerModuleTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🌱 Seeding Farmer Module Test Data...\n\n";

        // Get first farmer (or create one if none exists)
        $farmer = Farmer::first();
        
        if (!$farmer) {
            echo "⚠️  No farmer found. Please create a farmer account first.\n";
            echo "   Use: php artisan db:seed --class=UsersSeeder\n";
            return;
        }

        $farmerId = $farmer->id;
        $userId = $farmer->user_id;

        echo "✓ Using Farmer ID: {$farmerId}\n";
        echo "✓ User ID: {$userId}\n\n";

        // Get a product for the farmer
        $product = Product::where('farmer_id', $farmerId)->first();
        
        if (!$product) {
            echo "⚠️  No products found for this farmer.\n";
            echo "   Creating a sample product...\n";
            $product = Product::create([
                'farmer_id' => $farmerId,
                'egg_type' => 'chicken',
                'quality_grade' => 'AA',
                'quantity' => 100,
                'unit' => 'trays',
                'price' => 150,
                'harvest_date' => now(),
                'status' => 'Available',
                'published_at' => now(),
            ]);
            echo "   ✓ Product created (ID: {$product->id})\n\n";
        }

        // Get or create a buyer
        $buyer = User::where('role', 'buyer')->first();
        if (!$buyer) {
            echo "⚠️  No buyer found. Creating sample buyer...\n";
            $buyer = User::create([
                'first_name' => 'Test',
                'last_name' => 'Buyer',
                'email' => 'buyer@test.com',
                'password' => bcrypt('password'),
                'role' => 'buyer',
            ]);
            echo "   ✓ Buyer created (ID: {$buyer->id})\n\n";
        }

        // 1. CREATE SAMPLE TRANSACTIONS
        echo "📦 Creating Sample Transactions...\n";
        $transactions = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $transaction = Transaction::create([
                'buyer_id' => $buyer->id,
                'farmer_id' => $userId,
                'product_id' => $product->id,
                'final_quantity' => rand(5, 20),
                'final_price' => rand(100, 200),
                'total_amount' => rand(500, 2000),
                'status' => ['Ordered', 'Accepted', 'Delivered', 'Delivered', 'Delivered'][rand(0, 4)],
                'payment_status' => 'Paid',
                'delivery_status' => 'Delivered',
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
            ]);
            $transactions[] = $transaction;
            echo "   ✓ Transaction #{$transaction->id} created\n";
        }
        echo "   Total: " . count($transactions) . " transactions\n\n";

        // 2. CREATE FARMER EARNINGS
        echo "💰 Creating Earnings Records...\n";
        
        foreach ($transactions as $index => $transaction) {
            if ($transaction->status === 'Delivered') {
                $grossAmount = $transaction->total_amount;
                $platformFee = ($grossAmount * 5) / 100; // 5% fee
                
                $earning = FarmerEarning::create([
                    'farmer_id' => $farmerId,
                    'transaction_id' => $transaction->id,
                    'gross_amount' => $grossAmount,
                    'platform_fee' => $platformFee,
                    'platform_fee_percentage' => 5,
                    'platform_fee_type' => 'percentage',
                    'payment_gateway_fee' => 0,
                    'delivery_fee' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'net_amount' => $grossAmount - $platformFee,
                    'status' => 'completed',
                    'payout_status' => ['paid', 'unpaid', 'pending'][rand(0, 2)],
                    'earning_date' => $transaction->created_at,
                    'period' => $transaction->created_at->format('Y-m'),
                    'payout_date' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                    'payout_method' => rand(0, 1) ? 'bank_transfer' : null,
                    'payout_reference' => rand(0, 1) ? 'REF' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) : null,
                ]);
                echo "   ✓ Earning record created for Transaction #{$transaction->id} - ₱{$earning->net_amount}\n";
            }
        }
        echo "\n";

        // 3. CREATE FARMER REVIEWS
        echo "⭐ Creating Customer Reviews...\n";
        
        $reviewComments = [
            "Excellent quality eggs! Very fresh and well-packaged.",
            "Good product, delivery was on time. Will order again!",
            "The eggs are of premium quality. Highly recommended!",
            "Fast delivery and responsive seller. 5 stars!",
            "Great experience buying from this farmer.",
            "Product quality exceeded my expectations!",
            "Professional service and quality products.",
            "The best eggs I've bought online!",
        ];

        foreach (array_slice($transactions, 0, 8) as $index => $transaction) {
            if ($transaction->status === 'Delivered') {
                $review = FarmerReview::create([
                    'farmer_id' => $farmerId,
                    'buyer_id' => $buyer->id,
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'overall_rating' => rand(4, 5) + (rand(0, 1) * 0.5),
                    'product_quality_rating' => rand(4, 5) + (rand(0, 1) * 0.5),
                    'delivery_rating' => rand(3, 5) + (rand(0, 1) * 0.5),
                    'communication_rating' => rand(4, 5) + (rand(0, 1) * 0.5),
                    'packaging_rating' => rand(4, 5) + (rand(0, 1) * 0.5),
                    'comment' => $reviewComments[$index] ?? 'Great product!',
                    'status' => 'approved',
                    'is_verified_purchase' => true,
                    'created_at' => $transaction->created_at->addDays(rand(1, 7)),
                ]);

                // Add farmer reply to some reviews
                if (rand(0, 1)) {
                    $review->reply = "Thank you for your positive feedback! We're glad you enjoyed our products.";
                    $review->replied_at = $review->created_at->addHours(rand(1, 24));
                    $review->save();
                }

                echo "   ✓ Review created with {$review->overall_rating} stars\n";
            }
        }
        echo "\n";

        // 4. CREATE ACTIVITY LOGS
        echo "📝 Creating Activity Logs...\n";
        
        $activities = [
            ['action' => 'created', 'entity_type' => 'Product', 'description' => 'Created new product listing'],
            ['action' => 'updated', 'entity_type' => 'Product', 'description' => 'Updated product price'],
            ['action' => 'updated', 'entity_type' => 'Farmer', 'description' => 'Updated farm profile'],
            ['action' => 'viewed', 'entity_type' => 'Order', 'description' => 'Viewed order details'],
            ['action' => 'accepted', 'entity_type' => 'Order', 'description' => 'Accepted new order'],
            ['action' => 'replied', 'entity_type' => 'Review', 'description' => 'Replied to customer review'],
            ['action' => 'updated', 'entity_type' => 'Product', 'description' => 'Updated product stock'],
        ];

        foreach ($activities as $activity) {
            FarmerActivityLog::create([
                'farmer_id' => $farmerId,
                'user_id' => $userId,
                'action' => $activity['action'],
                'entity_type' => $activity['entity_type'],
                'entity_id' => rand(1, 10),
                'description' => $activity['description'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'device_type' => 'Desktop',
                'severity' => 'info',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
            echo "   ✓ {$activity['description']}\n";
        }
        echo "\n";

        // 5. CREATE INVENTORY LOGS
        echo "📊 Creating Inventory Logs...\n";
        
        $inventoryActions = [
            ['type' => 'sale', 'change' => -10, 'reason' => 'Product sold to buyer'],
            ['type' => 'restock', 'change' => 50, 'reason' => 'New harvest restocked'],
            ['type' => 'sale', 'change' => -5, 'reason' => 'Product sold to buyer'],
            ['type' => 'adjustment', 'change' => -2, 'reason' => 'Damaged during handling'],
            ['type' => 'sale', 'change' => -15, 'reason' => 'Bulk order fulfilled'],
        ];

        $currentQty = 100;
        foreach ($inventoryActions as $action) {
            $qtyBefore = $currentQty;
            $qtyChange = $action['change'];
            $qtyAfter = $qtyBefore + $qtyChange;
            $currentQty = $qtyAfter;

            InventoryLog::create([
                'farmer_id' => $farmerId,
                'product_id' => $product->id,
                'type' => $action['type'],
                'quantity_before' => $qtyBefore,
                'quantity_change' => $qtyChange,
                'quantity_after' => $qtyAfter,
                'unit' => 'trays',
                'performed_by' => $userId,
                'reason' => $action['reason'],
                'unit_price' => 150,
                'total_value' => abs($qtyChange) * 150,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
            echo "   ✓ {$action['type']}: {$qtyChange} trays - {$action['reason']}\n";
        }
        echo "\n";

        // 6. UPDATE FARMER STATS
        echo "📈 Updating Farmer Statistics...\n";
        
        $farmer->average_rating = FarmerReview::where('farmer_id', $farmerId)->avg('overall_rating');
        $farmer->total_reviews = FarmerReview::where('farmer_id', $farmerId)->count();
        $farmer->completed_orders = Transaction::where('farmer_id', $userId)
            ->where('status', 'Delivered')
            ->count();
        
        $totalOrders = Transaction::where('farmer_id', $userId)->count();
        $farmer->success_rate = $totalOrders > 0 ? ($farmer->completed_orders / $totalOrders) * 100 : 0;
        
        $farmer->save();
        
        echo "   ✓ Average Rating: " . number_format($farmer->average_rating, 2) . "\n";
        echo "   ✓ Total Reviews: {$farmer->total_reviews}\n";
        echo "   ✓ Completed Orders: {$farmer->completed_orders}\n";
        echo "   ✓ Success Rate: " . number_format($farmer->success_rate, 2) . "%\n\n";

        // SUMMARY
        echo "✅ SEEDING COMPLETE!\n\n";
        echo "📊 Summary:\n";
        echo "   • Transactions: " . count($transactions) . "\n";
        echo "   • Earnings: " . FarmerEarning::where('farmer_id', $farmerId)->count() . "\n";
        echo "   • Reviews: " . FarmerReview::where('farmer_id', $farmerId)->count() . "\n";
        echo "   • Activity Logs: " . FarmerActivityLog::where('farmer_id', $farmerId)->count() . "\n";
        echo "   • Inventory Logs: " . InventoryLog::where('farmer_id', $farmerId)->count() . "\n\n";
        
        echo "🎯 Ready to test! Login as:\n";
        echo "   Email: {$farmer->user->email}\n";
        echo "   (Use your password)\n\n";
        
        echo "🔗 Test URLs:\n";
        echo "   Earnings: http://localhost:8000/farmer/earnings\n";
        echo "   Reviews: http://localhost:8000/farmer/reviews\n";
        echo "   Profile: http://localhost:8000/farmer/profile/edit\n";
        echo "   Activities: http://localhost:8000/farmer/activities\n";
        echo "   Inventory Logs: http://localhost:8000/farmer/inventory/logs\n";
    }
}
