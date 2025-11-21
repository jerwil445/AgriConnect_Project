<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display the buyer dashboard with all available products.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Fetch all products with their farmer information
        $products = Product::with('farmer.user')
            ->where('status', 'available')
            ->paginate(12);
        
        return view('buyers.dashboard', compact('products'));
    }
    
    /**
     * Display the specified product details.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showProduct(Product $product)
    {
        // Load the farmer, user information, and product images
        $product->load('farmer.user', 'images');
        
        return view('buyers.products.show', compact('product'));
    }
}