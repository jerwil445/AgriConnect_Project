<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create inventory_logs table for tracking stock movements
     */
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('size_id')->nullable();
            
            // Log Type
            $table->enum('type', [
                'initial_stock',
                'restock',
                'sale',
                'reservation',
                'cancellation',
                'return',
                'damage',
                'expiry',
                'adjustment',
                'transfer'
            ]);
            
            // Quantity Changes
            $table->integer('quantity_before');
            $table->integer('quantity_change'); // Positive for additions, negative for deductions
            $table->integer('quantity_after');
            $table->string('unit')->default('trays');
            
            // Related Entities
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('performed_by'); // User who made the change
            
            // Details
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('reference_number')->nullable();
            
            // Pricing at time of change
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_value', 10, 2)->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('farmer_id');
            $table->index('product_id');
            $table->index('type');
            $table->index('created_at');
            $table->index(['farmer_id', 'product_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
