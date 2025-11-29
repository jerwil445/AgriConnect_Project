<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\Demand;
use App\Models\DemandMatch;
use App\Models\Transaction;

class ProductQuantityTest extends TestCase
{
    use RefreshDatabase;

    protected $farmerUser;
    protected $buyerUser;
    protected $farmer;
    protected $buyer;

    protected function setUp(): void
    {
        parent::setUp();

        // Create farmer user
        $this->farmerUser = User::factory()->create([
            'role' => 'farmer'
        ]);

        // Create farmer profile
        $this->farmer = Farmer::factory()->create([
            'user_id' => $this->farmerUser->id
        ]);

        // Create buyer user
        $this->buyerUser = User::factory()->create([
            'role' => 'buyer'
        ]);

        // Create buyer profile
        $this->buyer = Buyer::factory()->create([
            'user_id' => $this->buyerUser->id
        ]);
    }

    /** @test */
    public function it_deducts_product_quantity_when_order_is_placed()
    {
        // Create a product with 20 boxes of mango
        $product = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 20,
            'unit' => 'boxes',
            'status' => 'Available'
        ]);

        // Create a demand for 10 boxes of mango
        $demand = Demand::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'product_name' => 'Mango',
            'quantity' => 10
        ]);

        // Create a match between the product and demand
        $match = DemandMatch::factory()->create([
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Matched'
        ]);

        // Create a transaction
        $transaction = Transaction::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'farmer_id' => $this->farmerUser->id,
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Active'
        ]);

        // Place an order
        $response = $this->actingAs($this->buyerUser)->post(route('transactions.order', $transaction), [
            'buyer_name' => 'Test Buyer',
            'buyer_email' => 'buyer@test.com',
            'buyer_phone' => '1234567890',
            'buyer_address' => 'Test Address',
            'payment_method' => 'cash_on_delivery'
        ]);

        // Assert the order was placed successfully
        $response->assertSessionHas('success');

        // Refresh the product from database
        $product->refresh();

        // Assert the quantity was deducted correctly
        $this->assertEquals(10, $product->quantity);

        // Assert the product is still available
        $this->assertEquals('Available', $product->status);
    }

    /** @test */
    public function it_marks_product_as_sold_out_when_quantity_reaches_zero()
    {
        // Create a product with 20 boxes of mango
        $product = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 20,
            'unit' => 'boxes',
            'status' => 'Available'
        ]);

        // Create a demand for 20 boxes of mango (exact quantity)
        $demand = Demand::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'product_name' => 'Mango',
            'quantity' => 20
        ]);

        // Create a match between the product and demand
        $match = DemandMatch::factory()->create([
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Matched'
        ]);

        // Create a transaction
        $transaction = Transaction::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'farmer_id' => $this->farmerUser->id,
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Active'
        ]);

        // Place an order
        $response = $this->actingAs($this->buyerUser)->post(route('transactions.order', $transaction), [
            'buyer_name' => 'Test Buyer',
            'buyer_email' => 'buyer@test.com',
            'buyer_phone' => '1234567890',
            'buyer_address' => 'Test Address',
            'payment_method' => 'cash_on_delivery'
        ]);

        // Assert the order was placed successfully
        $response->assertSessionHas('success');

        // Refresh the product from database
        $product->refresh();

        // Assert the quantity is now zero
        $this->assertEquals(0, $product->quantity);

        // Assert the product is marked as sold out
        $this->assertEquals('Sold Out', $product->status);
    }

    /** @test */
    public function it_prevents_ordering_sold_out_products()
    {
        // Create a product that is sold out
        $product = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 0,
            'unit' => 'boxes',
            'status' => 'Sold Out'
        ]);

        // Create a demand
        $demand = Demand::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'product_name' => 'Mango',
            'quantity' => 5
        ]);

        // Create a match between the product and demand
        $match = DemandMatch::factory()->create([
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Matched'
        ]);

        // Create a transaction
        $transaction = Transaction::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'farmer_id' => $this->farmerUser->id,
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Active'
        ]);

        // Try to place an order for a sold out product
        $response = $this->actingAs($this->buyerUser)->post(route('transactions.order', $transaction), [
            'buyer_name' => 'Test Buyer',
            'buyer_email' => 'buyer@test.com',
            'buyer_phone' => '1234567890',
            'buyer_address' => 'Test Address',
            'payment_method' => 'cash_on_delivery'
        ]);

        // Assert we get an error
        $response->assertSessionHas('error');
        $response->assertSessionHas('error', 'This product is sold out and no longer available for purchase. You cannot place any more orders for this product.');
    }
}