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
        if (!Schema::hasColumn('demands', 'product_name')) {
            Schema::table('demands', function (Blueprint $table) {
                $table->string('product_name')->nullable()->after('buyer_id');
            });
        }

        if (!Schema::hasColumn('demands', 'product_name') || !Schema::hasColumn('demands', 'egg_type')) {
            return;
        }

        DB::table('demands')
            ->select('id', 'egg_type', 'product_name')
            ->whereNull('product_name')
            ->orderBy('id')
            ->chunkById(100, function ($demands) {
                foreach ($demands as $demand) {
                    $productName = match ($demand->egg_type) {
                        'chicken' => 'Chicken Eggs',
                        'duck' => 'Duck Eggs',
                        'quail' => 'Quail Eggs',
                        'native_chicken' => 'Native Chicken Eggs',
                        'brown' => 'Brown Eggs',
                        'white' => 'White Eggs',
                        default => $demand->egg_type ? ucwords(str_replace('_', ' ', $demand->egg_type)) : null,
                    };

                    if (!$productName) {
                        continue;
                    }

                    DB::table('demands')
                        ->where('id', $demand->id)
                        ->update(['product_name' => $productName]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('demands', 'product_name')) {
            return;
        }

        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
    }
};
