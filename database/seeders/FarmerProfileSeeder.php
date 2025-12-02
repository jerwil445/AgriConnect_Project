<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Farmer;

class FarmerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with farmer role who don't already have a farmer profile
        $farmers = User::where('role', 'farmer')
                      ->whereDoesntHave('farmer')
                      ->get();
        
        foreach ($farmers as $user) {
            Farmer::create([
                'user_id' => $user->id,
                'farm_name' => $user->first_name . "'s Farm",
                'farm_size' => rand(10, 100),
                'product_type' => ['Vegetables', 'Fruits', 'Grains', 'Livestock'][rand(0, 3)],
                'experience_years' => rand(1, 30),
                'certification' => ['Organic', 'Non-GMO', 'Conventional', 'Biodynamic'][rand(0, 3)],
                'farm_address' => 'Sample Address ' . rand(1, 100) . ', Sample City',
            ]);
        }
    }
}
