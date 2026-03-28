<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Demand;
use App\Models\Product;
use App\Models\DemandMatch;
use Illuminate\Support\Facades\Schema;

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
        $productNames = array_values(array_unique(array_filter([
            $demand->product_name,
            $this->mapEggTypeToProductName($demand->egg_type),
        ])));

        if (empty($productNames)) {
            return;
        }

        // Find products that match the demand criteria
        // Match based on product name, location (partial match), and sufficient remaining quantity
        $matchingProducts = Product::whereIn('product_name', $productNames)
            ->where('status', 'Available')
            ->where(function($query) use ($demand) {
                // Check if product has remaining inventory and sufficient quantity
                $query->whereHas('remainingInventory', function($subQuery) use ($demand) {
                    $subQuery->where('remaining_quantity', '>=', $demand->quantity);
                })
                // Or fallback to original quantity if no remaining inventory exists
                ->orWhere('quantity', '>=', $demand->quantity);
            })
            ->where(function($query) use ($demand) {
                // Match based on location/address (more flexible matching)
                $locationTerms = explode(' ', strtolower($demand->location));
                $query->where(function($subQuery) use ($locationTerms) {
                    foreach ($locationTerms as $term) {
                        if (strlen($term) > 2) { // Only match terms with more than 2 characters
                            $subQuery->where('purok_street', 'LIKE', '%' . $term . '%')
                                ->orWhere('barangay', 'LIKE', '%' . $term . '%')
                                ->orWhere('municipality_city', 'LIKE', '%' . $term . '%')
                                ->orWhere('province', 'LIKE', '%' . $term . '%');
                        }
                    }
                })->orWhereHas('farmer', function($subQuery) use ($locationTerms) {
                    $subQuery->where(function($farmerSubQuery) use ($locationTerms) {
                        foreach ($locationTerms as $term) {
                            if (strlen($term) > 2) { // Only match terms with more than 2 characters
                                $farmerSubQuery->where('farm_address', 'LIKE', '%' . $term . '%');
                            }
                        }
                    });
                });
            })
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
        $legacyEggType = $product->getRawOriginal('egg_type');
        $hasDemandProductName = Schema::hasColumn('demands', 'product_name');
        $hasDemandStatus = Schema::hasColumn('demands', 'status');
        $productNames = array_values(array_unique(array_filter([
            $product->product_name,
            $this->mapEggTypeToProductName($legacyEggType),
        ])));

        if ((empty($productNames) || !$hasDemandProductName) && !$legacyEggType) {
            return;
        }

        // Find demands that match the product criteria
        // Only match with products that are available
        // Match based on product name, location (partial match), and sufficient remaining quantity
        $matchingDemands = Demand::where(function ($query) use ($productNames, $legacyEggType, $hasDemandProductName) {
                $hasCondition = false;

                if ($hasDemandProductName && !empty($productNames)) {
                    $query->whereIn('product_name', $productNames);
                    $hasCondition = true;
                }

                if ($legacyEggType) {
                    if ($hasCondition) {
                        $query->orWhere('egg_type', $legacyEggType);
                    } else {
                        $query->where('egg_type', $legacyEggType);
                    }
                }
            });

        if ($hasDemandStatus) {
            $matchingDemands->where('status', 'Available');
        }

        $matchingDemands = $matchingDemands
            ->where(function($query) use ($product) {
                // Check if product has remaining inventory and sufficient quantity
                if ($product->remainingInventory) {
                    $query->where('quantity', '<=', $product->remainingInventory->remaining_quantity);
                } else {
                    // Fallback to original quantity if no remaining inventory exists
                    $query->where('quantity', '<=', $product->quantity);
                }
            })
            ->where(function($query) use ($product) {
                // Match based on location/address (more flexible matching)
                // Combine all address fields for matching
                $fullAddress = trim(($product->purok_street ?? '') . ' ' . ($product->barangay ?? '') . ' ' . ($product->municipality_city ?? '') . ' ' . ($product->province ?? ''));
                $addressTerms = explode(' ', strtolower($fullAddress));
                $query->where(function($subQuery) use ($addressTerms) {
                    foreach ($addressTerms as $term) {
                        if (strlen($term) > 2) { // Only match terms with more than 2 characters
                            $subQuery->where('location', 'LIKE', '%' . $term . '%');
                        }
                    }
                })->orWhereHas('buyer', function($subQuery) use ($addressTerms) {
                    $subQuery->where(function($buyerSubQuery) use ($addressTerms) {
                        foreach ($addressTerms as $term) {
                            if (strlen($term) > 2) { // Only match terms with more than 2 characters
                                $buyerSubQuery->where('address', 'LIKE', '%' . $term . '%');
                            }
                        }
                    });
                })->orWhereHas('buyer.buyer', function($subQuery) use ($addressTerms) {
                    $subQuery->where(function($buyerProfileSubQuery) use ($addressTerms) {
                        foreach ($addressTerms as $term) {
                            if (strlen($term) > 2) { // Only match terms with more than 2 characters
                                $buyerProfileSubQuery->where('address', 'LIKE', '%' . $term . '%');
                            }
                        }
                    });
                });
            })
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

    private function mapEggTypeToProductName(?string $eggType): ?string
    {
        if (!$eggType) {
            return null;
        }

        return match ($eggType) {
            'chicken' => 'Chicken Eggs',
            'duck' => 'Duck Eggs',
            'quail' => 'Quail Eggs',
            'native_chicken' => 'Native Chicken Eggs',
            'brown' => 'Brown Eggs',
            'white' => 'White Eggs',
            default => ucwords(str_replace('_', ' ', $eggType)),
        };
    }
}
