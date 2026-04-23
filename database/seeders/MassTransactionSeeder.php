<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Demand;
use App\Models\DemandMatch;
use Carbon\Carbon;

class MassTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $farmers = User::where('role', 'farmer')->whereHas('farmer')->get();
        $buyers = User::where('role', 'buyer')->get();

        if ($farmers->isEmpty() || $buyers->isEmpty()) {
            $this->command->error('Make sure you have farmers and buyers in the database.');
            return;
        }

        $this->command->info('Seeding 60 realistic transactions over the last 30 days...');

        $provinces = ['Cebu', 'Bohol', 'Leyte', 'Negros Oriental', 'Siquijor'];
        $productTypes = ['Rice', 'Corn', 'Mango', 'Banana', 'Tomato', 'Cabbage', 'Onion'];
        $statuses = ['completed', 'completed', 'completed', 'cancelled', 'rejected', 'pending', 'accepted'];
        $deliveryStatuses = ['delivered', 'delivered', 'shipped', 'cancelled', 'returned', 'pending', 'accepted'];
        $paymentStatuses = ['paid', 'paid', 'pending', 'refunded', 'failed', 'pending', 'pending'];

        // Create some Demands first for Regional Demand chart
        foreach ($provinces as $province) {
            foreach ($productTypes as $type) {
                if (rand(0, 1)) {
                    Demand::create([
                        'buyer_id' => $buyers->random()->id,
                        'product_name' => $type,
                        'variety_size' => 'Standard',
                        'quantity' => rand(50, 200),
                        'unit' => 'kilos',
                        'province' => $province,
                        'delivery_date' => now()->addDays(rand(1, 10)),
                        'status' => 'Available'
                    ]);
                }
            }
        }

        // Create Transactions
        for ($i = 0; $i < 60; $i++) {
            $farmer = $farmers->random();
            $buyer = $buyers->random();
            
            $product = Product::where('farmer_id', $farmer->farmer->id)->first();
            if (!$product) {
                $product = Product::create([
                    'farmer_id' => $farmer->farmer->id,
                    'product_name' => $productTypes[array_rand($productTypes)],
                    'variety_size' => 'Standard',
                    'quantity' => rand(500, 1000),
                    'unit' => 'kilos',
                    'price' => rand(20, 100),
                    'total_amount' => 0,
                    'status' => 'Available',
                    'harvest_date' => now()->addDays(rand(1, 30)),
                    'province' => $provinces[array_rand($provinces)]
                ]);
            }

            $daysAgo = rand(0, 30);
            $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 23));
            
            $qty = rand(5, 50);
            $unitPrice = $product->price;
            $total = $qty * $unitPrice;
            
            $statusIdx = array_rand($statuses);
            
            Transaction::create([
                'buyer_id' => $buyer->id,
                'farmer_id' => $farmer->id,
                'product_id' => $product->id,
                'final_quantity' => $qty,
                'final_price' => $unitPrice,
                'total_amount' => $total,
                'payment_status' => $paymentStatuses[$statusIdx],
                'delivery_status' => $deliveryStatuses[$statusIdx],
                'status' => $statuses[$statusIdx],
                'initiator_id' => $buyer->id,
                'buyer_name' => $buyer->first_name . ' ' . $buyer->last_name,
                'buyer_email' => $buyer->email,
                'buyer_phone' => $buyer->phone_number ?? '09XX-XXX-XXXX',
                'buyer_address' => $buyer->address ?? 'Default Address',
                'payment_method' => 'Cash/COD',
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]);
        }

        // Create some Matches
        $matchStatuses = ['New', 'Matched', 'Transaction Started', 'Rejected'];
        for ($i = 0; $i < 20; $i++) {
            $product = Product::inRandomOrder()->first();
            $demand = Demand::inRandomOrder()->first();
            
            if ($product && $demand) {
                DemandMatch::updateOrCreate(
                    ['product_id' => $product->id, 'demand_id' => $demand->id],
                    ['status' => $matchStatuses[array_rand($matchStatuses)], 'matched_date' => now()->subDays(rand(0, 30))]
                );
            }
        }

        $this->command->info('Seeding completed successfully!');
    }
}
