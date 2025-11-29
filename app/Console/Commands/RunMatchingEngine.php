<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Demand;
use App\Models\Product;
use App\Models\DemandMatch;

class RunMatchingEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matching:run {--demand-id=} {--product-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the matching engine to find suitable products for demands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $demandId = $this->option('demand-id');
        $productId = $this->option('product-id');

        if ($demandId) {
            // Run matching for a specific demand
            $demand = Demand::find($demandId);
            if ($demand) {
                $this->matchDemand($demand);
                $this->info("Matching completed for demand ID: {$demandId}");
            } else {
                $this->error("Demand with ID {$demandId} not found.");
            }
        } elseif ($productId) {
            // Run matching for a specific product
            $product = Product::find($productId);
            if ($product) {
                $this->matchProduct($product);
                $this->info("Matching completed for product ID: {$productId}");
            } else {
                $this->error("Product with ID {$productId} not found.");
            }
        } else {
            // Run matching for all demands and products
            $demands = Demand::all();
            foreach ($demands as $demand) {
                $this->matchDemand($demand);
            }
            $this->info('Matching completed for all demands.');
        }

        return 0;
    }

    /**
     * Match a specific demand with available products
     */
    private function matchDemand(Demand $demand)
    {
        // Find products that match the demand criteria
        $matchingProducts = Product::where('product_name', 'LIKE', '%' . $demand->product_name . '%')
            ->where('quantity', '>=', $demand->quantity)
            ->where('status', 'Available')
            ->get();

        // For each matching product, create a match record
        foreach ($matchingProducts as $product) {
            // Check if a match already exists
            $existingMatch = DemandMatch::where('product_id', $product->id)
                ->where('demand_id', $demand->id)
                ->first();

            if (!$existingMatch) {
                DemandMatch::create([
                    'product_id' => $product->id,
                    'demand_id' => $demand->id,
                    'status' => 'Pending',
                    'matched_date' => now(),
                ]);
            }
        }
    }

    /**
     * Match a specific product with available demands
     */
    private function matchProduct(Product $product)
    {
        // Find demands that match the product criteria
        // Only match with products that are available
        $matchingDemands = Demand::where('product_name', 'LIKE', '%' . $product->product_name . '%')
            ->where('quantity', '<=', $product->quantity)
            ->where('status', 'Available')
            ->get();

        // For each matching demand, create a match record
        foreach ($matchingDemands as $demand) {
            // Check if a match already exists
            $existingMatch = DemandMatch::where('product_id', $product->id)
                ->where('demand_id', $demand->id)
                ->first();

            if (!$existingMatch) {
                DemandMatch::create([
                    'product_id' => $product->id,
                    'demand_id' => $demand->id,
                    'status' => 'Pending',
                    'matched_date' => now(),
                ]);
            }
        }
    }
}
