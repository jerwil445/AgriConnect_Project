<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buyer>
 */
class BuyerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => $this->faker->company(),
            'business_type' => $this->faker->randomElement(['Restaurant', 'Retail', 'Wholesale', 'Supermarket', 'Individual']),
            'preferred_products' => $this->faker->words(3, true),
            'address' => $this->faker->address(),
            'verified' => $this->faker->boolean(),
        ];
    }
}