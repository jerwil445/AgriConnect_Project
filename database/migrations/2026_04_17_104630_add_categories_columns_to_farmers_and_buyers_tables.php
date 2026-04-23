<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->text('categories')->nullable()->after('product_type');
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->text('categories')->nullable()->after('preferred_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            //
        });
    }
};
