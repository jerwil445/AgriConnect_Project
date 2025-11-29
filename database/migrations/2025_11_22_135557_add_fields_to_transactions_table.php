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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'buyer_id')) {
                $table->unsignedBigInteger('buyer_id')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'farmer_id')) {
                $table->unsignedBigInteger('farmer_id')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'demand_id')) {
                $table->unsignedBigInteger('demand_id')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'final_quantity')) {
                $table->integer('final_quantity')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'final_price')) {
                $table->decimal('final_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('transactions', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->string('payment_status')->default('Pending');
            }
            if (!Schema::hasColumn('transactions', 'delivery_status')) {
                $table->string('delivery_status')->default('Scheduled');
            }
            if (!Schema::hasColumn('transactions', 'negotiation_messages')) {
                $table->text('negotiation_messages')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('Active');
            }
            
            // Add foreign key constraints only if they don't exist
            if (Schema::hasColumn('transactions', 'buyer_id')) {
                $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (Schema::hasColumn('transactions', 'farmer_id')) {
                $table->foreign('farmer_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (Schema::hasColumn('transactions', 'product_id')) {
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }
            if (Schema::hasColumn('transactions', 'demand_id')) {
                $table->foreign('demand_id')->references('id')->on('demands')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'buyer_id')) {
                $table->dropForeign(['buyer_id']);
                $table->dropColumn('buyer_id');
            }
            if (Schema::hasColumn('transactions', 'farmer_id')) {
                $table->dropForeign(['farmer_id']);
                $table->dropColumn('farmer_id');
            }
            if (Schema::hasColumn('transactions', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('transactions', 'demand_id')) {
                $table->dropForeign(['demand_id']);
                $table->dropColumn('demand_id');
            }
            if (Schema::hasColumn('transactions', 'final_quantity')) {
                $table->dropColumn('final_quantity');
            }
            if (Schema::hasColumn('transactions', 'final_price')) {
                $table->dropColumn('final_price');
            }
            if (Schema::hasColumn('transactions', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('transactions', 'delivery_status')) {
                $table->dropColumn('delivery_status');
            }
            if (Schema::hasColumn('transactions', 'negotiation_messages')) {
                $table->dropColumn('negotiation_messages');
            }
            if (Schema::hasColumn('transactions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};