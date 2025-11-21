<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user only if it doesn't exist
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'kyc_status' => 'verified'
            ]);
        }

        // Create sample farmers
        User::factory()->count(5)->create([
            'role' => 'farmer'
        ]);

        // Create sample buyers
        User::factory()->count(5)->create([
            'role' => 'buyer'
        ]);

        // Seed products
        $this->call([
            ProductSeeder::class,
            FarmerProfileSeeder::class,
            DemandMatchSeeder::class,
        ]);
    }
}