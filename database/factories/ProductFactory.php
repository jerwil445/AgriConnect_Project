<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Farmer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Egg-specific types for egg farming business
        $eggTypes = [
            'Chicken Eggs',
            'Duck Eggs', 
            'Quail Eggs',
            'Native Chicken Eggs',
            'Brown Eggs',
            'White Eggs',
            'Free Range Chicken Eggs',
            'Organic Chicken Eggs',
            'Pasture Raised Eggs',
            'Omega-3 Enriched Eggs'
        ];

        return [
            'farmer_id' => Farmer::factory(), // Add farmer_id
            'egg_type' => $this->faker->randomElement($eggTypes),
            'quantity' => $this->faker->numberBetween(50, 5000),
            'unit' => $this->faker->randomElement(['pieces', 'dozens', 'trays', 'crates']),
            'price' => $this->faker->randomFloat(2, 10, 2000),
            'harvest_date' => $this->faker->dateTimeBetween('-2 weeks', '+2 weeks'),
            'status' => $this->faker->randomElement(['Available', 'Available', 'Available', 'Sold Out', 'Pending']), // More available products
            'image' => null,
        ];
    }
}