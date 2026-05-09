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
            $table->decimal('response_rate', 5, 2)->default(100.00)->after('main_category');
            $table->string('response_time')->default('Within 24h')->after('response_rate');
            $table->integer('reputation_score')->default(0)->after('response_time');
            $table->boolean('is_verified')->default(false)->after('reputation_score');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->decimal('response_rate', 5, 2)->default(100.00)->after('categories');
            $table->string('response_time')->default('Within 24h')->after('response_rate');
            $table->integer('reputation_score')->default(0)->after('response_time');
            // 'verified' column already exists in buyers table, but we might want to standardize it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn(['response_rate', 'response_time', 'reputation_score', 'is_verified', 'verified_at']);
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn(['response_rate', 'response_time', 'reputation_score']);
        });
    }
};
