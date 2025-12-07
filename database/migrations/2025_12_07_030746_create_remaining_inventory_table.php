<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('remaining_inventory', function (Blueprint $table) {
            $table->id();
            
            // Add foreign key to products table
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Original values
            $table->integer('original_quantity')->default(0);
            $table->decimal('original_price', 10, 2)->default(0);
            $table->integer('original_total_trays')->default(0);
            
            // Remaining values
            $table->integer('remaining_quantity')->default(0);
            $table->decimal('remaining_price', 10, 2)->default(0);
            $table->integer('remaining_total_trays')->default(0);
            
            // Per size remaining values (JSON format to store all sizes)
            $table->json('per_size_remaining')->nullable();
            
            // Timestamps for tracking
            $table->timestamp('last_updated')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remaining_inventory');
    }
};