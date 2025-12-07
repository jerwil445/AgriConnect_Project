<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;

class BulkUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        echo "Creating 1000 farmers...\n";
        
        // Create 1000 farmers
        for ($i = 0; $i < 1000; $i++) {
            $user = User::factory()->create([
                'role' => 'farmer',
                'kyc_status' => 'verified'
            ]);

            Farmer::create([
                'user_id' => $user->id,
                'farm_name' => $faker->company(),
                'farm_size' => $faker->randomFloat(2, 1, 100),
                'product_type' => $faker->randomElement(['Vegetables', 'Fruits', 'Grains', 'Livestock']),
                'experience_years' => $faker->numberBetween(1, 30),
                'certification' => $faker->randomElement(['Organic', 'Non-GMO', 'Conventional', 'Biodynamic']),
                'farm_address' => $faker->address(),
            ]);

            if (($i + 1) % 100 == 0) {
                echo "Created " . ($i + 1) . " farmers...\n";
            }
        }

        echo "Creating 1000 buyers...\n";

        // Create 1000 buyers
        for ($i = 0; $i < 1000; $i++) {
            $user = User::factory()->create([
                'role' => 'buyer',
                'kyc_status' => 'verified'
            ]);

            Buyer::create([
                'user_id' => $user->id,
                'company_name' => $faker->company(),
                'business_type' => $faker->randomElement(['Restaurant', 'Retail', 'Wholesale', 'Supermarket', 'Individual']),
                'preferred_products' => $faker->words(3, true),
                'address' => $faker->address(),
                'verified' => true,
            ]);

            if (($i + 1) % 100 == 0) {
                echo "Created " . ($i + 1) . " buyers...\n";
            }
        }

        echo "Done! Created 1000 farmers and 1000 buyers.\n";
    }
}
