<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Farmer;

class EggProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farmers = Farmer::all();
        
        if ($farmers->isEmpty()) {
            echo "No farmers found. Please seed farmers first.\n";
            return;
        }

        echo "Creating 1000 egg-related products...\n";

        for ($i = 0; $i < 1000; $i++) {
            $farmer = $farmers->random();

            Product::factory()->create([
                'farmer_id' => $farmer->id,
            ]);

            if (($i + 1) % 100 == 0) {
                echo "Created " . ($i + 1) . " egg products...\n";
            }
        }

        echo "Done! Created 1000 egg-related products.\n";
    }
}
