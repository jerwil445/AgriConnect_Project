<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Demand;
use Carbon\Carbon;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // 1. Find a farmer user
            $user = User::where('role', 'farmer')->first();
            if (!$user) {
                $this->command->error('No farmer user found in the system. Create one first or run DatabaseSeeder.');
                return;
            }
            
            // Ensure they have a farmer profile
            if (!$user->farmer) {
                $farmer = Farmer::create(['user_id' => $user->id, 'farm_name' => 'Test Farm']);
            } else {
                $farmer = $user->farmer;
            }

            // 2. Find a buyer
            $buyer = User::where('role', 'buyer')->first();
            if (!$buyer) {
                $buyer = User::factory()->create(['role' => 'buyer']);
            }

            $this->command->info("Seeding realistic Analytics data for Farmer: " . $user->first_name);

            // 3. Create active products
            $productNames = ['Premium Rice', 'Sweet Corn', 'Carabao Mangoes', 'Native Tomatoes', 'Organic Cabbage'];
            $productIds = [];
            foreach ($productNames as $name) {
                $product = Product::create([
                    'farmer_id' => $farmer->id, // This should reference farmers.id not users.id
                    'product_name' => $name,
                    'variety_size' => 'Grade A',
                    'quantity' => rand(100, 500),
                    'unit' => 'kilos',
                    'price' => rand(30, 150),
                    'total_amount' => 0,
                    'harvest_date' => now()->subDays(rand(1, 10)),
                    'status' => 'Available'
                ]);
                $productIds[] = $product->id;
            }

            // 4. Generate 30 days of Transactions
            // Using standard statuses like pending, processing, shipped, delivered, completed, cancelled, rejected from previous implementations or standard platforms
            // Usually we have pending, accepted, rejected, completed, cancelled
            $statuses = ['completed', 'completed', 'completed', 'completed', 'cancelled', 'rejected', 'pending', 'accepted'];
            
            for ($i = 0; $i < 45; $i++) {
                $randomDaysAgo = rand(0, 30);
                $createdAt = now()->subDays($randomDaysAgo)->subHours(rand(1, 23));
                $productId = $productIds[array_rand($productIds)];
                $product = Product::find($productId);
                $qty = rand(10, 50);
                $finalPrice = $qty * $product->price;
                $status = $statuses[array_rand($statuses)];

                try {
                    Transaction::create([
                        'buyer_id' => $buyer->id,
                        'farmer_id' => $user->id, // Transactions reference users.id
                        'product_id' => $productId,
                        'final_quantity' => $qty,
                        'final_price' => $finalPrice,
                        'total_amount' => $finalPrice,
                        'payment_status' => $status == 'completed' ? 'paid' : 'pending',
                        'delivery_status' => $status == 'completed' ? 'delivered' : 'pending',
                        'status' => $status,
                        'buyer_name' => $buyer->first_name,
                        'buyer_email' => $buyer->email,
                        'buyer_phone' => '09123456789',
                        'buyer_address' => 'Test Address',
                        'payment_method' => 'Cash/COD',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt
                    ]);
                } catch (\Exception $e) {
                    // Try to reconnect in case the transaction aborted
                    DB::reconnect();
                    // Catch the specific iteration failure but just log and continue, we want at least *some* seeds
                    file_put_contents('seeder_err_iteration.txt', $e->getMessage(), FILE_APPEND);
                }
            }

            // 5. Generate some Market Demands
            // (Skipped demand generation as columns differ from assumptions and it's not needed for the analytics charts)

            $this->command->info("Analytics Seeder Finished. Navigate to /farmer/analytics to see the populated charts!");
        } catch (\Exception $e) {
            file_put_contents('seeder_err.txt', $e->getMessage() . "\n" . $e->getTraceAsString());
            echo "Failed. Check seeder_err.txt";
        }
    }
}
