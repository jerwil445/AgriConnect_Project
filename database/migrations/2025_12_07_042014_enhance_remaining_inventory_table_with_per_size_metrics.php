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
        Schema::table('remaining_inventory', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('remaining_inventory', 'product_id')) {
                // Add foreign key to products table
                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'original_quantity')) {
                // Original values
                $table->integer('original_quantity')->default(0);
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'original_price')) {
                $table->decimal('original_price', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'original_total_trays')) {
                $table->integer('original_total_trays')->default(0);
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'remaining_quantity')) {
                // Remaining values
                $table->integer('remaining_quantity')->default(0);
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'remaining_price')) {
                $table->decimal('remaining_price', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'remaining_total_trays')) {
                $table->integer('remaining_total_trays')->default(0);
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'per_size_remaining')) {
                // Per size remaining values (JSON format to store all sizes)
                $table->json('per_size_remaining')->nullable();
            }
            
            if (!Schema::hasColumn('remaining_inventory', 'last_updated')) {
                // Timestamps for tracking
                $table->timestamp('last_updated')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remaining_inventory', function (Blueprint $table) {
            // Check if columns exist before dropping them
            if (Schema::hasColumn('remaining_inventory', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'original_quantity')) {
                $table->dropColumn('original_quantity');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'original_price')) {
                $table->dropColumn('original_price');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'original_total_trays')) {
                $table->dropColumn('original_total_trays');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'remaining_quantity')) {
                $table->dropColumn('remaining_quantity');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'remaining_price')) {
                $table->dropColumn('remaining_price');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'remaining_total_trays')) {
                $table->dropColumn('remaining_total_trays');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'per_size_remaining')) {
                $table->dropColumn('per_size_remaining');
            }
            
            if (Schema::hasColumn('remaining_inventory', 'last_updated')) {
                $table->dropColumn('last_updated');
            }
        });
    }
};
