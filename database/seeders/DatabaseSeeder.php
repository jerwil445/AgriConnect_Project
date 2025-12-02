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

        // Create demo farmer user only if it doesn't exist
        if (!User::where('email', 'farmer@example.com')->exists()) {
            User::factory()->create([
                'first_name' => 'Demo',
                'last_name' => 'Farmer',
                'email' => 'farmer@example.com',
                'password' => bcrypt('password'),
                'role' => 'farmer',
                'kyc_status' => 'verified',
            ]);
        }

        // Create demo buyer user only if it doesn't exist
        if (!User::where('email', 'buyer@example.com')->exists()) {
            User::factory()->create([
                'first_name' => 'Demo',
                'last_name' => 'Buyer',
                'email' => 'buyer@example.com',
                'password' => bcrypt('password'),
                'role' => 'buyer',
                'kyc_status' => 'verified',
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

        // Seed products and demands
        $this->call([
            ProductSeeder::class,
            FarmerProfileSeeder::class,
            ProductImageSeeder::class,
            DemandSeeder::class,
            DemandMatchSeeder::class,
        ]);
    }
}