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
        // Match based on egg type and sufficient remaining quantity
        // Location matching is now based on address fields (Province, City/Municipality, Barangay)
        $matchingDemands = Demand::where('egg_type', $product->egg_type)
            ->where('status', 'Available')
            ->whereDoesntHave('matches') // Only demands with zero matches
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
                // Match based on address fields (Province, City/Municipality, Barangay)
                // Combine all address fields for matching
                $province = $product->province ?? '';
                $municipalityCity = $product->municipality_city ?? '';
                $barangay = $product->barangay ?? '';
                
                // Skip location matching if no address fields are provided
                if (empty($province) && empty($municipalityCity) && empty($barangay)) {
                    return $query; // No location filtering
                }
                
                $query->where(function($subQuery) use ($province, $municipalityCity, $barangay) {
                    // Match province if provided
                    if (!empty($province)) {
                        $subQuery->where('province', 'LIKE', '%' . $province . '%');
                    }
                    
                    // Match municipality/city if provided
                    if (!empty($municipalityCity)) {
                        $subQuery->orWhere('municipality_city', 'LIKE', '%' . $municipalityCity . '%');
                    }
                    
                    // Match barangay if provided
                    if (!empty($barangay)) {
                        $subQuery->orWhere('barangay', 'LIKE', '%' . $barangay . '%');
                    }
                })->orWhereHas('buyer', function($subQuery) use ($province, $municipalityCity, $barangay) {
                    $subQuery->where(function($buyerSubQuery) use ($province, $municipalityCity, $barangay) {
                        // Match province if provided
                        if (!empty($province)) {
                            $buyerSubQuery->where('buyer_address', 'LIKE', '%' . $province . '%');
                        }
                        
                        // Match municipality/city if provided
                        if (!empty($municipalityCity)) {
                            $buyerSubQuery->orWhere('buyer_address', 'LIKE', '%' . $municipalityCity . '%');
                        }
                        
                        // Match barangay if provided
                        if (!empty($barangay)) {
                            $buyerSubQuery->orWhere('buyer_address', 'LIKE', '%' . $barangay . '%');
                        }
                    });
                });
            })
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