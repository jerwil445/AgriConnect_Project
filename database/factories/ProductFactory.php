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
        // Extended list of egg or product types for more variety
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
            'farmer_id' => Farmer::factory(), // Add farmer_id
            'egg_type' => $this->faker->randomElement($productNames),
            'quantity' => $this->faker->numberBetween(10, 2000),
            'unit' => $this->faker->randomElement(['kilos', 'pieces', 'bunches', 'boxes', 'tons', 'liters', 'grams']),
            'price' => $this->faker->randomFloat(2, 10, 2000),
            'harvest_date' => $this->faker->dateTimeBetween('-2 weeks', '+2 weeks'),
            'status' => $this->faker->randomElement(['Available', 'Available', 'Available', 'Sold Out', 'Pending']), // More available products
            'image' => null,
        ];
    }
}