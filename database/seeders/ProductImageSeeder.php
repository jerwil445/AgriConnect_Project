<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products
        $products = Product::all();
        
        if ($products->count() > 0) {
            foreach ($products as $product) {
                // Create 3-5 images for each product
                $imageCount = rand(3, 5);
                
                for ($i = 1; $i <= $imageCount; $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'products/sample_' . $i . '.jpg',
                        'is_primary' => ($i == 1) ? true : false,
                    ]);
                }
            }
        }
    }
}