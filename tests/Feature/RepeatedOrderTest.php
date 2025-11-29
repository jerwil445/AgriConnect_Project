<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\Demand;
use App\Models\DemandMatch;
use App\Models\Transaction;

class RepeatedOrderTest extends TestCase
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
    public function it_creates_new_transaction_when_buyer_orders_same_product_again()
    {
        // Create a product with 20 boxes of mango
        $product = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 20,
            'unit' => 'boxes',
            'status' => 'Available'
        ]);

        // Create a demand for 5 boxes of mango
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

        // Create first transaction
        $firstTransaction = Transaction::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'farmer_id' => $this->farmerUser->id,
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Active'
        ]);

        // Place first order
        $response = $this->actingAs($this->buyerUser)->post(route('transactions.order', $firstTransaction), [
            'buyer_name' => 'Test Buyer',
            'buyer_email' => 'buyer@test.com',
            'buyer_phone' => '1234567890',
            'buyer_address' => 'Test Address',
            'payment_method' => 'cash_on_delivery'
        ]);

        // Assert the first order was placed successfully
        $response->assertStatus(302); // Redirect response
        $response->assertSessionHas('success');

        // Get the first transaction after order placement
        $firstTransaction->refresh();
        
        // Verify first transaction remains active (not updated)
        $this->assertEquals('Active', $firstTransaction->status);

        // Get the ID of the first transaction
        $firstTransactionId = $firstTransaction->id;

        // Create a second transaction for the same product
        $secondTransaction = Transaction::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'farmer_id' => $this->farmerUser->id,
            'product_id' => $product->id,
            'demand_id' => $demand->id,
            'status' => 'Active'
        ]);

        // Place second order
        $response2 = $this->actingAs($this->buyerUser)->post(route('transactions.order', $secondTransaction), [
            'buyer_name' => 'Test Buyer',
            'buyer_email' => 'buyer@test.com',
            'buyer_phone' => '1234567890',
            'buyer_address' => 'Test Address',
            'payment_method' => 'cash_on_delivery'
        ]);

        // Assert the second order was placed successfully
        $response2->assertStatus(302); // Redirect response
        $response2->assertSessionHas('success');

        // Get the second transaction after order placement
        $secondTransaction->refresh();
        
        // Verify second transaction remains active (not updated)
        $this->assertEquals('Active', $secondTransaction->status);

        // Verify that we have ordered transactions (created by our new implementation)
        $orderedTransactions = Transaction::where('buyer_id', $this->buyerUser->id)
            ->where('product_id', $product->id)
            ->where('status', 'Ordered')
            ->get();

        // Assert we have two ordered transactions (created by our implementation)
        $this->assertEquals(2, $orderedTransactions->count());

        // Assert they are different transactions
        $this->assertNotEquals($orderedTransactions[0]->id, $orderedTransactions[1]->id);

        // Assert product quantity was deducted correctly (should be 10 now: 20 - 5 - 5)
        $product->refresh();
        $this->assertEquals(10, $product->quantity);
    }
}