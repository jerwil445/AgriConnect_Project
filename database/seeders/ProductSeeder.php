<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Farmer;
use Database\Factories\ProductFactory;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all farmers
        $farmers = Farmer::all();
        
        if ($farmers->count() > 0) {
            // Create 50 products distributed among farmers
            Product::factory()->count(50)->create([
                'farmer_id' => function() use ($farmers) {
                    return $farmers->random()->id;
                },
            ]);
        }
    }
}
