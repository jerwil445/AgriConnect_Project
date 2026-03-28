<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('demands', 'status')) {
            Schema::table('demands', function (Blueprint $table) {
                $table->string('status')->default('Available')->after('delivery_date');
            });
        }

        if (Schema::hasColumn('demands', 'status')) {
            DB::table('demands')
                ->whereNull('status')
                ->update(['status' => 'Available']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('demands', 'status')) {
            return;
        }

        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
