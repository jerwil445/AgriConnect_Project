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

class AutomaticMatchingTest extends TestCase
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
    public function it_automatically_matches_new_product_with_existing_demand_that_has_zero_matches()
    {
        // Create a demand for 10 boxes of mango with zero matches
        $demand = Demand::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'product_name' => 'Mango',
            'quantity' => 10
        ]);

        // Verify the demand has zero matches initially
        $this->assertEquals(0, $demand->matches()->count());

        // Create a product that matches the demand
        $product = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 20,
            'unit' => 'boxes',
            'status' => 'Available'
        ]);

        // Refresh the demand from database
        $demand->refresh();

        // Assert that a match was automatically created
        $this->assertEquals(1, $demand->matches()->count());
        
        // Assert that the match has the correct product and demand
        $match = $demand->matches()->first();
        $this->assertEquals($product->id, $match->product_id);
        $this->assertEquals($demand->id, $match->demand_id);
        $this->assertEquals('New', $match->status);
    }

    /** @test */
    public function it_does_not_match_new_product_with_existing_demand_that_already_has_matches()
    {
        // Create a demand for 10 boxes of mango
        $demand = Demand::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'product_name' => 'Mango',
            'quantity' => 10
        ]);

        // Create another product that matches this demand to create an existing match
        $existingProduct = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 20,
            'unit' => 'boxes',
            'status' => 'Available'
        ]);

        // Create an existing match
        DemandMatch::factory()->create([
            'product_id' => $existingProduct->id,
            'demand_id' => $demand->id,
            'status' => 'New'
        ]);

        // Verify the demand has at least one match
        $this->assertGreaterThanOrEqual(1, $demand->matches()->count());

        // Create a new product that would match the demand
        $newProduct = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 15,
            'unit' => 'boxes',
            'status' => 'Available'
        ]);

        // Refresh the demand from database
        $demand->refresh();

        // Assert that no additional match was created for the new product
        // (The demand already had matches, so it shouldn't get new automatic matches)
        $this->assertEquals(1, $demand->matches()->count());
    }

    /** @test */
    public function it_does_not_match_new_product_when_product_is_not_available()
    {
        // Create a demand for 10 boxes of mango with zero matches
        $demand = Demand::factory()->create([
            'buyer_id' => $this->buyerUser->id,
            'product_name' => 'Mango',
            'quantity' => 10
        ]);

        // Verify the demand has zero matches initially
        $this->assertEquals(0, $demand->matches()->count());

        // Create a product that matches the demand but is not available
        $product = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mango',
            'quantity' => 20,
            'unit' => 'boxes',
            'status' => 'Sold Out'
        ]);

        // Refresh the demand from database
        $demand->refresh();

        // Assert that no match was automatically created because the product is not available
        $this->assertEquals(0, $demand->matches()->count());
    }
}