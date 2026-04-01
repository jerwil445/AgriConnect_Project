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
        Schema::table('demands', function (Blueprint $table) {
            if (!Schema::hasColumn('demands', 'unit')) {
                $table->string('unit')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('demands', 'deadline')) {
                $table->date('deadline')->nullable()->after('delivery_date');
            }
            if (!Schema::hasColumn('demands', 'variety_size')) {
                $table->string('variety_size')->nullable()->after('product_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn(['unit', 'deadline', 'variety_size']);
        });
    }
};