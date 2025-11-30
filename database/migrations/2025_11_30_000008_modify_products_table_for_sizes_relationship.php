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
            // Remove the egg_size column as it will now be stored in the sizes table
            $table->dropColumn('egg_size');
            
            // Add a jumbo attribute to the products table
            $table->boolean('jumbo')->default(false)->after('egg_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('egg_size')->nullable()->after('egg_type');
            $table->dropColumn('jumbo');
        });
    }
};