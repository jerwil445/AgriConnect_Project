<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create farmer_earnings table for financial tracking and payouts
     */
    public function up(): void
    {
        Schema::create('farmer_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('transaction_id');
            
            // Financial Breakdown
            $table->decimal('gross_amount', 10, 2); // Total order amount
            $table->decimal('platform_fee', 10, 2)->default(0); // AgriConnect commission
            $table->decimal('payment_gateway_fee', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2); // Amount farmer receives
            
            // Platform Fee Details
            $table->decimal('platform_fee_percentage', 5, 2)->default(0);
            $table->string('platform_fee_type')->default('percentage'); // percentage, fixed
            
            // Payment Status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'disputed'])->default('pending');
            $table->enum('payout_status', ['unpaid', 'pending', 'paid', 'on_hold'])->default('unpaid');
            
            // Payout Details
            $table->string('payout_method')->nullable(); // bank_transfer, mobile_wallet, cash
            $table->string('payout_reference')->nullable();
            $table->decimal('payout_amount', 10, 2)->nullable();
            $table->timestamp('payout_date')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            
            // Period Tracking
            $table->date('earning_date'); // Date when earning was generated
            $table->string('period')->nullable(); // e.g., "2025-12", for monthly aggregation
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Additional details
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('farmer_id');
            $table->index('status');
            $table->index('payout_status');
            $table->index('earning_date');
            $table->index('period');
            $table->index(['farmer_id', 'payout_status']);
            $table->index(['farmer_id', 'earning_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_earnings');
    }
};
