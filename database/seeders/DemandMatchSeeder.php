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
        // Get all buyers and farmers
        $buyers = User::where('role', 'buyer')->get();
        $farmers = User::where('role', 'farmer')->get();
        
        if ($buyers->isEmpty() || $farmers->isEmpty()) {
            return; // Exit if we don't have the required users
        }
        
        // Get all demands and products
        $demands = Demand::all();
        $products = Product::all();
        
        if ($demands->isEmpty() || $products->isEmpty()) {
            return; // Exit if we don't have demands or products
        }
        
        // Create matches between demands and products
        foreach ($demands->take(20) as $demand) {
            // Get a random product
            $product = $products->random();
            
            // Create a match between the demand and product
            DemandMatch::create([
                'product_id' => $product->id,
                'demand_id' => $demand->id,
                'status' => 'New',
                'matched_date' => now(),
            ]);
        }
        
        // Create sample demand for demonstration
        $buyer = $buyers->first();
        $farmer = $farmers->first();
        
        $demand = Demand::create([
            'buyer_id' => $buyer->id,
            'product_name' => 'Corn',
            'quantity' => 50,
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
    }
}