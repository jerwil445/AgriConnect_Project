<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Demand;
use App\Models\User;

class DemandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all buyers
        $buyers = User::where('role', 'buyer')->get();
        
        if ($buyers->count() > 0) {
            // Create 30 demands distributed among buyers
            Demand::factory()->count(30)->create([
                'buyer_id' => function() use ($buyers) {
                    return $buyers->random()->id;
                },
            ]);
        } else {
            // If no buyers exist, create 5 sample buyers first
            $buyers = User::factory()->count(5)->create(['role' => 'buyer']);
            
            // Then create 30 demands distributed among these buyers
            Demand::factory()->count(30)->create([
                'buyer_id' => function() use ($buyers) {
                    return $buyers->random()->id;
                },
            ]);
        }
    }
}