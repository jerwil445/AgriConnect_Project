<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Demand;
use App\Models\Buyer;

class BulkDemandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyers = Buyer::all();
        
        if ($buyers->isEmpty()) {
            echo "No buyers found. Please seed buyers first.\n";
            return;
        }

        echo "Creating 1000 demands...\n";

        for ($i = 0; $i < 1000; $i++) {
            $buyer = $buyers->random();

            Demand::factory()->create([
                'buyer_id' => $buyer->user_id,
            ]);

            if (($i + 1) % 100 == 0) {
                echo "Created " . ($i + 1) . " demands...\n";
            }
        }

        echo "Done! Created 1000 demands.\n";
    }
}
