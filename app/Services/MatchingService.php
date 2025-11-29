<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Demand;
use App\Models\DemandMatch;

class MatchingService
{
    /**
     * Match a specific product with existing demands that have zero matches
     */
    public function matchNewProductWithZeroMatchDemands(Product $product)
    {
        // Find demands that match the product criteria and have zero matches
        $matchingDemands = Demand::where('product_name', 'LIKE', '%' . $product->product_name . '%')
            ->where('quantity', '<=', $product->quantity)
            ->where('status', 'Available')
            ->whereDoesntHave('matches') // Only demands with zero matches
            ->get();

        // For each matching demand, create a match record
        foreach ($matchingDemands as $demand) {
            // Check if a match already exists (shouldn't be needed but just in case)
            $existingMatch = DemandMatch::where('product_id', $product->id)
                ->where('demand_id', $demand->id)
                ->first();

            if (!$existingMatch) {
                DemandMatch::create([
                    'product_id' => $product->id,
                    'demand_id' => $demand->id,
                    'status' => 'New',
                    'matched_date' => now(),
                ]);
            }
        }
    }
}