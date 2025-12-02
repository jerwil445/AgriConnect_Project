<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Buyer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Demand>
 */
class DemandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Extended list of product names for demands
        $productNames = [
            'Tomatoes', 'Potatoes', 'Carrots', 'Lettuce', 'Cucumbers',
            'Bell Peppers', 'Onions', 'Garlic', 'Spinach', 'Cabbage',
            'Apples', 'Bananas', 'Oranges', 'Grapes', 'Strawberries',
            'Corn', 'Rice', 'Wheat', 'Soybeans', 'Barley',
            'Milk', 'Cheese', 'Yogurt', 'Butter', 'Cream',
            'Broccoli', 'Cauliflower', 'Celery', 'Mushrooms', 'Avocados',
            'Pineapples', 'Mangoes', 'Watermelons', 'Peaches', 'Cherries',
            'Beef', 'Pork', 'Chicken', 'Lamb', 'Fish',
            'Eggs', 'Honey', 'Flour', 'Sugar', 'Salt',
            'Coffee Beans', 'Tea Leaves', 'Herbs', 'Spices', 'Nuts',
            'Olives', 'Pumpkins', 'Sweet Potatoes', 'Radishes', 'Peas'
        ];

        return [
            'buyer_id' => Buyer::factory(),
            'product_name' => $this->faker->randomElement($productNames),
            'quantity' => $this->faker->numberBetween(10, 1000),
            'location' => $this->faker->city(),
            'delivery_date' => $this->faker->dateTimeBetween('+1 week', '+2 months'),
        ];
    }
}