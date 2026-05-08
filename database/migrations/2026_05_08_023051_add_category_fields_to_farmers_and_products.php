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
        Schema::table('farmers', function (Blueprint $table) {
            $table->string('main_category')->nullable()->after('farm_size');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->after('farmer_id');
            $table->string('variety')->nullable()->after('product_name');
            $table->string('size_grade')->nullable()->after('variety');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('main_category');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category', 'variety', 'size_grade']);
        });
    }
};
