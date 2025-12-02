<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use App\Models\Demand;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'buyer_id' => User::factory(),
            'farmer_id' => User::factory(),
            'product_id' => Product::factory(),
            'demand_id' => Demand::factory(),
            'final_quantity' => $this->faker->randomNumber(2),
            'final_price' => $this->faker->randomFloat(2, 10, 1000),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'payment_status' => $this->faker->randomElement(['Pending', 'Paid']),
            'delivery_status' => $this->faker->randomElement(['Scheduled', 'In Transit', 'Delivered']),
            'status' => $this->faker->randomElement(['Active', 'Ordered', 'Completed']),
            'buyer_name' => $this->faker->name(),
            'buyer_email' => $this->faker->email(),
            'buyer_phone' => $this->faker->phoneNumber(),
            'buyer_address' => $this->faker->address(),
            'payment_method' => $this->faker->randomElement(['cash_on_delivery', 'bank_transfer', 'credit_card', 'e_wallet']),
        ];
    }
}