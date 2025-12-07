<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\DemandMatch;
use App\Models\Product;
use App\Models\Demand;

class BulkMatchesSeeder extends Seeder
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
        echo "Creating 1000 demand matches...\n";

        for ($i = 0; $i < 1000; $i++) {
            $product = $products->random();
            $demand = $demands->random();

            DemandMatch::create([
                'product_id' => $product->id,
                'demand_id' => $demand->id,
                'status' => $faker->randomElement(['New', 'Pending', 'Matched', 'Rejected']),
                'matched_date' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);

            if (($i + 1) % 100 == 0) {
                echo "Created " . ($i + 1) . " matches...\n";
            }
        }

        echo "Done! Created 1000 demand matches.\n";
    }
}
