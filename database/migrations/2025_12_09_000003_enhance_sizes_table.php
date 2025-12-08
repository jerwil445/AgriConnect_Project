<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Enhance sizes table with inventory tracking and availability
     */
    public function up(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            // Inventory Management
            $table->integer('initial_tray_count')->nullable()->after('tray_count');
            $table->integer('sold_tray_count')->default(0)->after('initial_tray_count');
            $table->integer('reserved_tray_count')->default(0)->after('sold_tray_count');
            $table->integer('available_tray_count')->nullable()->after('reserved_tray_count');
            
            // Size Details
            $table->integer('eggs_per_tray')->default(30)->after('size_name'); // 30 is standard
            $table->decimal('weight_per_egg_grams', 6, 2)->nullable()->after('eggs_per_tray');
            $table->decimal('total_weight_kg', 8, 2)->nullable()->after('weight_per_egg_grams');
            
            // Pricing & Discounts
            $table->decimal('original_price_per_tray', 10, 2)->nullable()->after('price_per_tray');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('original_price_per_tray');
            $table->boolean('has_special_offer')->default(false)->after('discount_percentage');
            $table->timestamp('special_offer_until')->nullable()->after('has_special_offer');
            
            // Availability
            $table->enum('availability_status', ['available', 'low_stock', 'out_of_stock', 'discontinued'])->default('available')->after('special_offer_until');
            $table->integer('low_stock_threshold')->default(5)->after('availability_status');
            $table->boolean('allow_backorder')->default(false)->after('low_stock_threshold');
            
            // Performance
            $table->integer('order_count')->default(0)->after('allow_backorder');
            $table->decimal('popularity_score', 5, 2)->default(0)->after('order_count');
            $table->timestamp('last_sold_at')->nullable()->after('popularity_score');
            
            // Soft delete
            $table->softDeletes()->after('updated_at');
            
            // Indexes
            $table->index('availability_status');
            $table->index(['product_id', 'size_name']);
            $table->index(['farmer_id', 'availability_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropIndex(['availability_status']);
            $table->dropIndex(['product_id', 'size_name']);
            $table->dropIndex(['farmer_id', 'availability_status']);
            
            $table->dropSoftDeletes();
            $table->dropColumn([
                'initial_tray_count', 'sold_tray_count', 'reserved_tray_count', 'available_tray_count',
                'eggs_per_tray', 'weight_per_egg_grams', 'total_weight_kg',
                'original_price_per_tray', 'discount_percentage', 'has_special_offer', 'special_offer_until',
                'availability_status', 'low_stock_threshold', 'allow_backorder',
                'order_count', 'popularity_score', 'last_sold_at'
            ]);
        });
    }
};
