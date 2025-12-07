<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Demand;
use App\Models\User;

class BulkTransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $demands = Demand::all();
        
        if ($products->isEmpty() || $demands->isEmpty()) {
            echo "No products or demands found. Please seed products and demands first.\n";
            return;
        }

        $faker = FakerFactory::create();
        echo "Creating 5000 transactions...\n";

        for ($i = 0; $i < 5000; $i++) {
            $product = $products->random();
            $demand = $demands->random();

            // Get the farmer and buyer users
            $farmerUserId = $product->farmer->user_id;
            $buyerUserId = $demand->buyer_id;
            $buyerUser = User::find($buyerUserId);

            $maxQty = max(1, min($product->quantity ?? 100, $demand->quantity ?? 100));
            $qty = $faker->numberBetween(1, $maxQty);
            $price = $product->price ?? $faker->randomFloat(2, 10, 1000);

            Transaction::create([
                'buyer_id' => $buyerUserId,
                'farmer_id' => $farmerUserId,
                'product_id' => $product->id,
                'demand_id' => $demand->id,
                'final_quantity' => $qty,
                'final_price' => $price,
                'total_amount' => $price * $qty,
                'payment_status' => $faker->randomElement(['Pending', 'Paid', 'Failed']),
                'delivery_status' => $faker->randomElement(['Scheduled', 'In Transit', 'Delivered', 'Cancelled']),
                'status' => $faker->randomElement(['Active', 'Ordered', 'Completed', 'Cancelled']),
                'initiator_id' => $buyerUserId,
                'buyer_name' => $buyerUser ? ($buyerUser->first_name . ' ' . $buyerUser->last_name) : $faker->name(),
                'buyer_email' => $buyerUser ? $buyerUser->email : $faker->safeEmail(),
                'buyer_phone' => $buyerUser && $buyerUser->phone_number ? $buyerUser->phone_number : $faker->phoneNumber(),
                'buyer_address' => $buyerUser && $buyerUser->address ? $buyerUser->address : $faker->address(),
                'payment_method' => $faker->randomElement(['cash_on_delivery', 'bank_transfer', 'credit_card', 'e_wallet']),
            ]);

            if (($i + 1) % 500 == 0) {
                echo "Created " . ($i + 1) . " transactions...\n";
            }
        }

        echo "Done! Created 5000 transactions.\n";
    }
}
