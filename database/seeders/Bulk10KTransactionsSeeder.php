<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Demand;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use App\Models\Size;
use App\Models\SizeTransaction;
use Carbon\Carbon;

class Bulk10KTransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get available farmers and buyers
        $farmers = User::where('role', 'farmer')->with('farmer')->get();
        $buyers = User::where('role', 'buyer')->with('buyer')->get();

        if ($farmers->isEmpty() || $buyers->isEmpty()) {
            echo "No farmers or buyers found. Please seed farmers and buyers first.\n";
            return;
        }

        // Transaction statuses with realistic distributions
        $statuses = [
            'Completed' => 65,    // 65%
            'Ordered' => 15,      // 15% 
            'Active' => 10,       // 10%
            'Cancelled' => 10     // 10%
        ];

        $paymentStatuses = [
            'Paid' => 60,         // 60%
            'Pending' => 25,      // 25%
            'Failed' => 15        // 15%
        ];

        $deliveryStatuses = [
            'Delivered' => 55,    // 55%
            'In Transit' => 20,   // 20%
            'Scheduled' => 15,    // 15%
            'Cancelled' => 10     // 10%
        ];

        $paymentMethods = [
            'cash_on_delivery' => 40,  // 40%
            'bank_transfer' => 30,     // 30%
            'e_wallet' => 20,          // 20%
            'credit_card' => 10        // 10%
        ];

        // Egg types and sizes
        $eggTypes = [
            'Native Chicken Eggs', 'Brown Eggs', 'White Eggs', 'Organic Eggs', 'Free Range Eggs',
            'Duck Eggs', 'Quail Eggs', 'Fertilized Eggs', 'Omega-3 Enriched Eggs', 'Cage Free Eggs'
        ];

        $eggSizes = ['Small', 'Medium', 'Large', 'Extra Large', 'Jumbo'];

        echo "Creating 10,000 realistic transactions with historical dates...\n";

        for ($i = 1; $i <= 10000; $i++) {
            $farmer = $farmers->random();
            $buyer = $buyers->random();

            // Generate realistic transaction dates (past 2 years)
            $transactionDate = Carbon::instance(fake()->dateTimeBetween('-2 years', 'now'));
            
            // Realistic quantities (1-50 trays for most transactions)
            $quantity = $this->getRealisticQuantity();
            
            // Realistic pricing based on egg type and size
            $eggType = $eggTypes[array_rand($eggTypes)];
            $eggSize = $eggSizes[array_rand($eggSizes)];
            $pricePerTray = $this->getRealisticPrice($eggType, $eggSize);
            $totalAmount = $quantity * $pricePerTray;

            // Status based on transaction age and realistic distribution
            $status = $this->getStatusBasedOnAge($transactionDate, $statuses);
            $paymentStatus = $this->getWeightedRandom($paymentStatuses);
            $deliveryStatus = $this->getDeliveryStatusBasedOnTransaction($status, $deliveryStatuses);

            // Create transaction
            $transaction = Transaction::create([
                'buyer_id' => $buyer->id,
                'farmer_id' => $farmer->id,
                'product_id' => null, // We'll simulate without requiring existing products
                'demand_id' => null,  // We'll simulate without requiring existing demands
                'final_quantity' => $quantity,
                'final_price' => $pricePerTray,
                'total_amount' => $totalAmount,
                'tray_counts' => json_encode([$eggSize => $quantity]),
                'size_details' => json_encode([
                    'egg_type' => $eggType,
                    'size' => $eggSize,
                    'trays' => $quantity,
                    'price_per_tray' => $pricePerTray
                ]),
                'payment_status' => $paymentStatus,
                'delivery_status' => $deliveryStatus,
                'negotiation_messages' => json_encode($this->generateNegotiationMessages($farmer, $buyer)),
                'status' => $status,
                'initiator_id' => fake()->boolean(70) ? $buyer->id : $farmer->id, // 70% buyer initiated
                'conversation_thread_id' => null,
                'buyer_name' => $buyer->first_name . ' ' . $buyer->last_name,
                'buyer_email' => $buyer->email,
                'buyer_phone' => $buyer->phone_number,
                'buyer_address' => $buyer->address,
                'payment_method' => $this->getWeightedRandom($paymentMethods),
                'created_at' => $transactionDate,
                'updated_at' => $this->getUpdatedDate($transactionDate, $status),
            ]);

            // Create size transaction details
            $this->createSizeTransactionDetails($transaction, $eggType, $eggSize, $quantity, $pricePerTray);

            if ($i % 500 == 0) {
                echo "Created $i transactions...\n";
            }
        }

        echo "Successfully created 10,000 transactions!\n";
        echo "Transaction distribution:\n";
        echo "- Completed: ~6,500 transactions\n";
        echo "- Ordered: ~1,500 transactions\n";
        echo "- Active: ~1,000 transactions\n";
        echo "- Cancelled: ~1,000 transactions\n";
    }

    private function getRealisticQuantity(): int
    {
        $weights = [
            1 => 5,    // Small orders (1-5 trays): 5%
            5 => 25,   // Small-medium orders (6-10 trays): 25%
            10 => 35,  // Medium orders (11-20 trays): 35%
            20 => 20,  // Large orders (21-30 trays): 20%
            30 => 10,  // Very large orders (31-50 trays): 10%
            50 => 5    // Bulk orders (51-100 trays): 5%
        ];

        $rand = fake()->numberBetween(1, 100);
        $cumulative = 0;

        foreach ($weights as $maxQty => $percentage) {
            $cumulative += $percentage;
            if ($rand <= $cumulative) {
                if ($maxQty == 1) {
                    return fake()->numberBetween(1, 5);
                } elseif ($maxQty == 5) {
                    return fake()->numberBetween(6, 10);
                } elseif ($maxQty == 10) {
                    return fake()->numberBetween(11, 20);
                } elseif ($maxQty == 20) {
                    return fake()->numberBetween(21, 30);
                } elseif ($maxQty == 30) {
                    return fake()->numberBetween(31, 50);
                } else {
                    return fake()->numberBetween(51, 100);
                }
            }
        }

        return fake()->numberBetween(1, 20); // Fallback
    }

    private function getRealisticPrice(string $eggType, string $eggSize): float
    {
        // Base prices per tray (30 eggs) in Philippine Peso
        $basePrices = [
            'Native Chicken Eggs' => 180,
            'Brown Eggs' => 160,
            'White Eggs' => 150,
            'Organic Eggs' => 220,
            'Free Range Eggs' => 200,
            'Duck Eggs' => 170,
            'Quail Eggs' => 120,
            'Fertilized Eggs' => 190,
            'Omega-3 Enriched Eggs' => 210,
            'Cage Free Eggs' => 180
        ];

        // Size multipliers
        $sizeMultipliers = [
            'Small' => 0.85,
            'Medium' => 1.0,
            'Large' => 1.15,
            'Extra Large' => 1.30,
            'Jumbo' => 1.45
        ];

        $basePrice = $basePrices[$eggType] ?? 160;
        $sizeMultiplier = $sizeMultipliers[$eggSize] ?? 1.0;
        
        // Add some realistic variation (±10%)
        $variation = fake()->randomFloat(2, 0.9, 1.1);
        
        return round($basePrice * $sizeMultiplier * $variation, 2);
    }

    private function getWeightedRandom(array $weights): string
    {
        $rand = fake()->numberBetween(1, 100);
        $cumulative = 0;

        foreach ($weights as $value => $percentage) {
            $cumulative += $percentage;
            if ($rand <= $cumulative) {
                return $value;
            }
        }

        return array_key_first($weights); // Fallback
    }

    private function getStatusBasedOnAge(Carbon $transactionDate, array $statuses): string
    {
        $ageInDays = $transactionDate->diffInDays(now());

        // Older transactions are more likely to be completed
        if ($ageInDays > 365) {
            // Very old transactions - mostly completed or cancelled
            return fake()->randomElement(['Completed', 'Completed', 'Completed', 'Completed', 'Cancelled']);
        } elseif ($ageInDays > 90) {
            // Older transactions - mostly completed
            return fake()->randomElement(['Completed', 'Completed', 'Completed', 'Cancelled']);
        } elseif ($ageInDays > 30) {
            // Recent transactions - mix of completed and ordered
            return fake()->randomElement(['Completed', 'Completed', 'Ordered', 'Cancelled']);
        } else {
            // Very recent transactions - more active and ordered
            return fake()->randomElement(['Completed', 'Ordered', 'Active', 'Active']);
        }
    }

    private function getDeliveryStatusBasedOnTransaction(string $status, array $deliveryStatuses): string
    {
        if ($status === 'Completed') {
            return 'Delivered';
        } elseif ($status === 'Cancelled') {
            return 'Cancelled';
        } elseif ($status === 'Active') {
            return fake()->randomElement(['Scheduled', 'In Transit']);
        } else {
            return $this->getWeightedRandom($deliveryStatuses);
        }
    }

    private function getUpdatedDate(Carbon $createdAt, string $status): Carbon
    {
        $updatedAt = clone $createdAt;

        if ($status === 'Completed') {
            // Add 1-30 days for completion
            $updatedAt->addDays(fake()->numberBetween(1, 30));
        } elseif ($status === 'Cancelled') {
            // Add 1-7 days for cancellation
            $updatedAt->addDays(fake()->numberBetween(1, 7));
        } elseif ($status === 'Ordered') {
            // Add 1-15 days for ordering
            $updatedAt->addDays(fake()->numberBetween(1, 15));
        } else {
            // Add 1-5 days for active
            $updatedAt->addDays(fake()->numberBetween(1, 5));
        }

        return $updatedAt;
    }

    private function generateNegotiationMessages($farmer, $buyer): array
    {
        $messages = [
            [
                'from' => 'buyer',
                'name' => $buyer->first_name . ' ' . $buyer->last_name,
                'message' => 'Hi! I\'m interested in your eggs. What\'s your best price?',
                'timestamp' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s')
            ],
            [
                'from' => 'farmer',
                'name' => $farmer->first_name . ' ' . $farmer->last_name,
                'message' => 'Hello! Our current price is competitive. When do you need delivery?',
                'timestamp' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s')
            ]
        ];

        // Add more messages sometimes
        if (fake()->boolean(40)) {
            $messages[] = [
                'from' => 'buyer',
                'name' => $buyer->first_name . ' ' . $buyer->last_name,
                'message' => 'Can you do bulk discount? I need regular supply.',
                'timestamp' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s')
            ];

            $messages[] = [
                'from' => 'farmer',
                'name' => $farmer->first_name . ' ' . $farmer->last_name,
                'message' => 'Yes, we can discuss volume pricing for regular orders.',
                'timestamp' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s')
            ];
        }

        return $messages;
    }

    private function createSizeTransactionDetails($transaction, $eggType, $eggSize, $quantity, $pricePerTray)
    {
        // For now, we'll skip creating actual Size and SizeTransaction records
        // since they require complex product/size relationships
        // The main transaction already contains the size details in JSON format
    }
}
