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
        return [
            'product_name' => $this->faker->randomElement([
                'Tomatoes', 'Potatoes', 'Carrots', 'Lettuce', 'Cucumbers',
                'Bell Peppers', 'Onions', 'Garlic', 'Spinach', 'Cabbage',
                'Apples', 'Bananas', 'Oranges', 'Grapes', 'Strawberries',
                'Corn', 'Rice', 'Wheat', 'Soybeans', 'Barley',
                'Milk', 'Cheese', 'Yogurt', 'Butter', 'Cream'
            ]),
            'quantity' => $this->faker->numberBetween(10, 1000),
            'unit' => $this->faker->randomElement(['kilos', 'pieces', 'bunches', 'boxes', 'tons']),
            'price' => $this->faker->randomFloat(2, 20, 1000),
            'harvest_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'status' => $this->faker->randomElement(['available', 'sold_out', 'pending']),
            'image' => null,
        ];
    }
}
