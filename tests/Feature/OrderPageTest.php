<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Farmer;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Demand;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function buyer_can_view_their_orders_page()
    {
        // Create a buyer user
        $buyerUser = User::factory()->create();
        $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);
        
        // Create a farmer user
        $farmerUser = User::factory()->create();
        $farmer = Farmer::factory()->create(['user_id' => $farmerUser->id]);
        
        // Create a product
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        
        // Create a demand
        $demand = Demand::factory()->create(['buyer_id' => $buyer->id]);
        
        // Create an order/transaction
        $transaction = Transaction::factory()->create([
            'buyer_id' => $buyerUser->id,
            'farmer_id' => $farmerUser->id,
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Ordered'
        ]);

        // Act as the buyer and visit the orders page
        $response = $this->actingAs($buyerUser)->get(route('buyer.orders'));

        // Assert that the response is successful
        $response->assertStatus(200);
        
        // Assert that the order is visible on the page
        $response->assertSee($transaction->id);
        $response->assertSee($product->product_name);
    }

    /** @test */
    public function farmer_can_view_their_orders_page()
    {
        // Create a buyer user
        $buyerUser = User::factory()->create();
        $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);
        
        // Create a farmer user
        $farmerUser = User::factory()->create();
        $farmer = Farmer::factory()->create(['user_id' => $farmerUser->id]);
        
        // Create a product
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        
        // Create a demand
        $demand = Demand::factory()->create(['buyer_id' => $buyer->id]);
        
        // Create an order/transaction
        $transaction = Transaction::factory()->create([
            'buyer_id' => $buyerUser->id,
            'farmer_id' => $farmerUser->id,
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Ordered'
        ]);

        // Act as the farmer and visit the orders page
        $response = $this->actingAs($farmerUser)->get(route('farmer.orders'));

        // Assert that the response is successful
        $response->assertStatus(200);
        
        // Assert that the order is visible on the page
        $response->assertSee($transaction->id);
        $response->assertSee($product->product_name);
    }
}