<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Demand;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DemandMatch>
 */
class DemandMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'demand_id' => Demand::factory(),
            'status' => $this->faker->randomElement(['New', 'Pending', 'Matched', 'Rejected', 'Transaction Started']),
            'matched_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}