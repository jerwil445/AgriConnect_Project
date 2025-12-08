<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Enhance products table with quality tracking and inventory management
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Quality & Grading
            $table->enum('quality_grade', ['AA', 'A', 'B', 'C'])->default('A')->after('egg_type');
            $table->string('color')->nullable()->after('quality_grade'); // White, Brown, Blue, etc.
            $table->decimal('average_weight_grams', 5, 2)->nullable()->after('color');
            $table->boolean('is_organic')->default(false)->after('average_weight_grams');
            $table->boolean('is_free_range')->default(false)->after('is_organic');
            $table->string('hen_breed')->nullable()->after('is_free_range'); // Leghorn, Rhode Island, etc.
            
            // Storage & Freshness
            $table->date('laying_date')->nullable()->after('harvest_date');
            $table->integer('shelf_life_days')->default(30)->after('laying_date');
            $table->date('expiry_date')->nullable()->after('shelf_life_days');
            $table->string('storage_condition')->default('Refrigerated')->after('expiry_date'); // Refrigerated, Room Temperature
            $table->decimal('storage_temperature', 4, 1)->nullable()->after('storage_condition'); // in Celsius
            
            // Inventory Management
            $table->integer('initial_quantity')->nullable()->after('quantity');
            $table->integer('sold_quantity')->default(0)->after('initial_quantity');
            $table->integer('reserved_quantity')->default(0)->after('sold_quantity');
            $table->integer('available_quantity')->nullable()->after('reserved_quantity'); // Virtual column
            $table->integer('minimum_order_quantity')->default(1)->after('available_quantity');
            $table->integer('maximum_order_quantity')->nullable()->after('minimum_order_quantity');
            $table->integer('reorder_level')->nullable()->after('maximum_order_quantity');
            $table->boolean('low_stock_alert')->default(false)->after('reorder_level');
            
            // Pricing
            $table->decimal('original_price', 10, 2)->nullable()->after('price'); // For discount tracking
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('original_price');
            $table->boolean('is_negotiable')->default(false)->after('discount_percentage');
            $table->decimal('minimum_acceptable_price', 10, 2)->nullable()->after('is_negotiable');
            
            // Location & Delivery
            $table->string('province')->nullable()->after('address');
            $table->string('city')->nullable()->after('province');
            $table->string('barangay')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('barangay');
            $table->decimal('delivery_radius_km', 6, 2)->nullable()->after('postal_code');
            $table->boolean('offers_delivery')->default(false)->after('delivery_radius_km');
            $table->boolean('offers_pickup')->default(true)->after('offers_delivery');
            $table->decimal('delivery_fee', 8, 2)->nullable()->after('offers_pickup');
            
            // Certifications & Compliance
            $table->boolean('fda_approved')->default(false)->after('delivery_fee');
            $table->string('batch_number')->nullable()->after('fda_approved');
            $table->string('certification_documents')->nullable()->after('batch_number'); // JSON array of file paths
            
            // Performance Metrics
            $table->integer('view_count')->default(0)->after('certification_documents');
            $table->integer('inquiry_count')->default(0)->after('view_count');
            $table->integer('order_count')->default(0)->after('inquiry_count');
            $table->decimal('conversion_rate', 5, 2)->default(0)->after('order_count');
            
            // Product Visibility
            $table->boolean('is_featured')->default(false)->after('conversion_rate');
            $table->boolean('is_promoted')->default(false)->after('is_featured');
            $table->timestamp('featured_until')->nullable()->after('is_promoted');
            $table->integer('priority_order')->default(0)->after('featured_until'); // For sorting
            
            // Timestamps
            $table->timestamp('last_restocked_at')->nullable()->after('priority_order');
            $table->timestamp('last_sold_at')->nullable()->after('last_restocked_at');
            $table->timestamp('published_at')->nullable()->after('last_sold_at');
            
            // Soft delete
            $table->softDeletes()->after('updated_at');
            
            // Indexes
            $table->index('status');
            $table->index('egg_type');
            $table->index('quality_grade');
            $table->index('is_organic');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index(['farmer_id', 'status']);
            $table->index(['province', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['egg_type']);
            $table->dropIndex(['quality_grade']);
            $table->dropIndex(['is_organic']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['farmer_id', 'status']);
            $table->dropIndex(['province', 'city']);
            
            $table->dropSoftDeletes();
            $table->dropColumn([
                'quality_grade', 'color', 'average_weight_grams', 'is_organic', 'is_free_range', 'hen_breed',
                'laying_date', 'shelf_life_days', 'expiry_date', 'storage_condition', 'storage_temperature',
                'initial_quantity', 'sold_quantity', 'reserved_quantity', 'available_quantity',
                'minimum_order_quantity', 'maximum_order_quantity', 'reorder_level', 'low_stock_alert',
                'original_price', 'discount_percentage', 'is_negotiable', 'minimum_acceptable_price',
                'province', 'city', 'barangay', 'postal_code', 'delivery_radius_km',
                'offers_delivery', 'offers_pickup', 'delivery_fee',
                'fda_approved', 'batch_number', 'certification_documents',
                'view_count', 'inquiry_count', 'order_count', 'conversion_rate',
                'is_featured', 'is_promoted', 'featured_until', 'priority_order',
                'last_restocked_at', 'last_sold_at', 'published_at'
            ]);
        });
    }
};
