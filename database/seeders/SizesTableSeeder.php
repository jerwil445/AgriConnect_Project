<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data as requested:
        // Small (50 trays) @ ₱190.00/tray
        // Medium (50 trays) @ ₱230.00/tray
        // Large (50 trays) @ ₱250.00/tray
        // Extra Large (50 trays) @ ₱300.00/tray
        
        Size::create([
            'farmer_id' => 1, // Assuming a farmer with ID 1 exists
            'product_id' => 1, // Assuming a product with ID 1 exists
            'egg_type' => 'Chicken',
            'size_name' => 'small',
            'tray_count' => 50,
            'price_per_tray' => 190.00,
            'total_price' => 9500.00 // 50 trays * ₱190.00/tray
        ]);
        
        Size::create([
            'farmer_id' => 1,
            'product_id' => 1,
            'egg_type' => 'Chicken',
            'size_name' => 'medium',
            'tray_count' => 50,
            'price_per_tray' => 230.00,
            'total_price' => 11500.00 // 50 trays * ₱230.00/tray
        ]);
        
        Size::create([
            'farmer_id' => 1,
            'product_id' => 1,
            'egg_type' => 'Chicken',
            'size_name' => 'large',
            'tray_count' => 50,
            'price_per_tray' => 250.00,
            'total_price' => 12500.00 // 50 trays * ₱250.00/tray
        ]);
        
        Size::create([
            'farmer_id' => 1,
            'product_id' => 1,
            'egg_type' => 'Chicken',
            'size_name' => 'extra_large',
            'tray_count' => 50,
            'price_per_tray' => 300.00,
            'total_price' => 15000.00 // 50 trays * ₱300.00/tray
        ]);
    }
}