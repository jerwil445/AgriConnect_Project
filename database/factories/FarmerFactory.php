<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farmer>
 */
class FarmerFactory extends Factory
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
            'farm_name' => $this->faker->company(),
            'farm_size' => $this->faker->randomFloat(2, 1, 100),
            'product_type' => $this->faker->randomElement(['Vegetables', 'Fruits', 'Grains', 'Livestock']),
            'experience_years' => $this->faker->numberBetween(1, 30),
            'certification' => $this->faker->randomElement(['Organic', 'Non-GMO', 'Conventional', 'Biodynamic']),
            'farm_address' => $this->faker->address(),
        ];
    }
}
