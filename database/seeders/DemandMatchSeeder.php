<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Demand;
use App\Models\DemandMatch;
use App\Models\Product;
use App\Models\User;

class DemandMatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample buyer and farmer users
        $buyer = User::where('role', 'buyer')->first();
        $farmer = User::where('role', 'farmer')->first();
        
        if (!$buyer || !$farmer) {
            return; // Exit if we don't have the required users
        }
        
        // Create sample demand
        $demand = Demand::create([
            'buyer_id' => $buyer->id,
            'product_name' => 'Corn',
            'quantity' => 50,
            'location' => 'Town A',
            'delivery_date' => '2025-11-25',
        ]);
        
        // Get a sample product from the farmer
        $product = Product::where('farmer_id', $farmer->farmer->id)->first();
        
        if ($product) {
            // Create a match between the demand and product
            DemandMatch::create([
                'product_id' => $product->id,
                'demand_id' => $demand->id,
                'status' => 'Pending',
                'matched_date' => now(),
            ]);
        }
        
        // Create another demand
        $demand2 = Demand::create([
            'buyer_id' => $buyer->id,
            'product_name' => 'Rice',
            'quantity' => 100,
            'location' => 'Town B',
            'delivery_date' => '2025-11-30',
        ]);
    }
}