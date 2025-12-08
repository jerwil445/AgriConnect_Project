<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create product_analytics table for tracking product performance metrics
     */
    public function up(): void
    {
        Schema::create('product_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('product_id');
            $table->date('date');
            
            // View Metrics
            $table->integer('views')->default(0);
            $table->integer('unique_views')->default(0);
            $table->integer('detail_views')->default(0);
            
            // Engagement Metrics
            $table->integer('inquiries')->default(0);
            $table->integer('messages_received')->default(0);
            $table->integer('favorites')->default(0);
            $table->integer('shares')->default(0);
            
            // Sales Metrics
            $table->integer('orders_placed')->default(0);
            $table->integer('orders_completed')->default(0);
            $table->integer('orders_cancelled')->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->integer('units_sold')->default(0);
            
            // Performance Indicators
            $table->decimal('conversion_rate', 5, 2)->default(0); // (orders / views) * 100
            $table->decimal('avg_order_value', 10, 2)->default(0);
            $table->integer('avg_response_time_minutes')->default(0);
            
            // Match Metrics
            $table->integer('matches_generated')->default(0);
            $table->integer('matches_accepted')->default(0);
            $table->decimal('match_conversion_rate', 5, 2)->default(0);
            
            // Pricing Insights
            $table->decimal('avg_selling_price', 10, 2)->default(0);
            $table->decimal('price_at_date', 10, 2)->default(0);
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Indexes
            $table->index('date');
            $table->index(['farmer_id', 'date']);
            $table->index(['product_id', 'date']);
            $table->unique(['product_id', 'date']); // One record per product per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_analytics');
    }
};
