<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Farmer;
use App\Models\Product;
use App\Models\Demand;
use App\Models\DemandMatch;
use App\Models\Transaction;
use App\Models\ConversationThread;
use App\Models\Message;

class ConversationThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $buyer;
    protected $farmer;
    protected $product1;
    protected $product2;
    protected $demand1;
    protected $demand2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create buyer user
        $buyerUser = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Buyer',
            'email' => 'john.buyer@example.com',
            'password' => bcrypt('password'),
            'role' => 'buyer'
        ]);

        $this->buyer = Buyer::factory()->create([
            'user_id' => $buyerUser->id
        ]);

        // Create farmer user
        $farmerUser = User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Farmer',
            'email' => 'jane.farmer@example.com',
            'password' => bcrypt('password'),
            'role' => 'farmer'
        ]);

        $this->farmer = Farmer::factory()->create([
            'user_id' => $farmerUser->id
        ]);

        // Create products
        $this->product1 = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Mangoes',
            'price' => 50,
            'unit' => 'kg'
        ]);

        $this->product2 = Product::factory()->create([
            'farmer_id' => $this->farmer->id,
            'product_name' => 'Oranges',
            'price' => 40,
            'unit' => 'kg'
        ]);

        // Create demands
        $this->demand1 = Demand::factory()->create([
            'buyer_id' => $this->buyer->id,
            'product_name' => 'Mangoes',
            'quantity' => 10
        ]);

        $this->demand2 = Demand::factory()->create([
            'buyer_id' => $this->buyer->id,
            'product_name' => 'Oranges',
            'quantity' => 15
        ]);
    }

    /** @test */
    public function conversation_thread_is_created_when_first_transaction_is_started()
    {
        // Create demand matches
        $match1 = DemandMatch::factory()->create([
            'demand_id' => $this->demand1->id,
            'product_id' => $this->product1->id,
            'status' => 'Matched'
        ]);

        // Start first transaction
        $this->actingAs($this->buyer->user)
            ->post(route('matches.startConversation', $match1))
            ->assertStatus(302);

        // Check that conversation thread was created
        $this->assertEquals(1, ConversationThread::count());
        
        $thread = ConversationThread::first();
        $this->assertEquals($this->buyer->user_id, $thread->buyer_id);
        $this->assertEquals($this->farmer->user_id, $thread->farmer_id);
    }

    /** @test */
    public function same_conversation_thread_is_used_for_multiple_transactions_between_same_users()
    {
        // Create demand matches
        $match1 = DemandMatch::factory()->create([
            'demand_id' => $this->demand1->id,
            'product_id' => $this->product1->id,
            'status' => 'Matched'
        ]);

        $match2 = DemandMatch::factory()->create([
            'demand_id' => $this->demand2->id,
            'product_id' => $this->product2->id,
            'status' => 'Matched'
        ]);

        // Start first transaction
        $this->actingAs($this->buyer->user)
            ->post(route('matches.startConversation', $match1))
            ->assertStatus(302);

        // Start second transaction
        $this->actingAs($this->buyer->user)
            ->post(route('matches.startConversation', $match2))
            ->assertStatus(302);

        // Check that only one conversation thread exists
        $this->assertEquals(1, ConversationThread::count());
        
        $transactions = Transaction::all();
        $this->assertEquals(2, $transactions->count());
        
        // Both transactions should reference the same conversation thread
        $this->assertEquals($transactions[0]->conversation_thread_id, $transactions[1]->conversation_thread_id);
    }

    /** @test */
    public function messages_from_different_transactions_are_grouped_in_same_conversation()
    {
        // Create demand matches
        $match1 = DemandMatch::factory()->create([
            'demand_id' => $this->demand1->id,
            'product_id' => $this->product1->id,
            'status' => 'Matched'
        ]);

        $match2 = DemandMatch::factory()->create([
            'demand_id' => $this->demand2->id,
            'product_id' => $this->product2->id,
            'status' => 'Matched'
        ]);

        // Start first transaction
        $this->actingAs($this->buyer->user)
            ->post(route('matches.startConversation', $match1));

        // Start second transaction
        $this->actingAs($this->buyer->user)
            ->post(route('matches.startConversation', $match2));

        // Get the transactions
        $transactions = Transaction::all();
        
        // Ensure we have two transactions
        $this->assertEquals(2, $transactions->count());
        
        // Find the specific transactions by product
        $mangoTransaction = $transactions->firstWhere('product_id', $this->product1->id);
        $orangeTransaction = $transactions->firstWhere('product_id', $this->product2->id);
        
        // Ensure we found both transactions
        $this->assertNotNull($mangoTransaction);
        $this->assertNotNull($orangeTransaction);
        
        // Send message in first transaction (excluding auto-sent "Is this available?" messages)
        $this->actingAs($this->buyer->user)
            ->post(route('transactions.sendMessage', $mangoTransaction), [
                'message' => 'Hello, is the mango still available?'
            ])
            ->assertJson(['success' => true]);

        // Send message in second transaction (excluding auto-sent "Is this available?" messages)
        $this->actingAs($this->buyer->user)
            ->post(route('transactions.sendMessage', $orangeTransaction), [
                'message' => 'Hi, I\'m also interested in your oranges!'
            ])
            ->assertJson(['success' => true]);

        // Check that all messages are associated with the same conversation thread
        $messages = Message::all();
        $this->assertEquals(4, $messages->count()); // 2 auto-sent + 2 manually sent
        $this->assertEquals($messages[0]->conversation_thread_id, $messages[1]->conversation_thread_id);
        $this->assertEquals($messages[1]->conversation_thread_id, $messages[2]->conversation_thread_id);
        $this->assertEquals($messages[2]->conversation_thread_id, $messages[3]->conversation_thread_id);
        
        // Both messages should be in the same conversation thread
        $threadId = $messages[0]->conversation_thread_id;
        $this->assertNotNull($threadId);
        
        // When loading conversation for either transaction, all messages should appear
        $response = $this->actingAs($this->buyer->user)
            ->get(route('messages.loadConversation', $mangoTransaction));
            
        $responseContent = $response->getContent();
        $this->assertStringContainsString('Hello, is the mango still available?', $responseContent);
        $this->assertStringContainsString('Hi, I&#039;m also interested in your oranges!', $responseContent);
        $this->assertStringContainsString('Mangoes', $responseContent);
        $this->assertStringContainsString('Oranges', $responseContent);
    }

    /** @test */
    public function is_this_available_message_is_sent_with_product_details_for_new_transactions()
    {
        // Create demand match
        $match = DemandMatch::factory()->create([
            'demand_id' => $this->demand1->id,
            'product_id' => $this->product1->id,
            'status' => 'Matched'
        ]);

        // Start transaction
        $this->actingAs($this->buyer->user)
            ->post(route('matches.startConversation', $match));

        // Check that a message was automatically sent
        $this->assertEquals(1, Message::count());
        
        $message = Message::first();
        $this->assertStringContainsString('Is this available?', $message->message);
        $this->assertStringContainsString($this->product1->egg_type, $message->message);
        $this->assertStringContainsString((string)$this->product1->quantity, $message->message);
        $this->assertStringContainsString($this->product1->unit, $message->message);
        $this->assertStringContainsString(number_format($this->product1->price, 2), $message->message);
    }
}