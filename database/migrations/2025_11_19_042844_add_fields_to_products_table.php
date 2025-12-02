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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('farmer_id');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('unit');
            $table->decimal('price', 8, 2);
            $table->date('harvest_date');
            $table->string('status')->default('available');
            $table->string('image')->nullable();
            
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
            $table->dropColumn(['farmer_id', 'product_name', 'quantity', 'unit', 'price', 'harvest_date', 'status', 'image']);
        });
    }
};
