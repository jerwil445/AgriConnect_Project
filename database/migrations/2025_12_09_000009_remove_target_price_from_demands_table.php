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
        if (Schema::hasColumn('demands', 'target_price')) {
            Schema::table('demands', function (Blueprint $table) {
                $table->dropColumn('target_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('demands', 'target_price')) {
            Schema::table('demands', function (Blueprint $table) {
                $table->decimal('target_price', 10, 2)->nullable();
            });
        }
    }
};
