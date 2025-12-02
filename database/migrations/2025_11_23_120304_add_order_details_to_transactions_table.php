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
            if (!Schema::hasColumn('transactions', 'buyer_name')) {
                $table->string('buyer_name')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'buyer_email')) {
                $table->string('buyer_email')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'buyer_phone')) {
                $table->string('buyer_phone')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'buyer_address')) {
                $table->text('buyer_address')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'buyer_name')) {
                $table->dropColumn('buyer_name');
            }
            if (Schema::hasColumn('transactions', 'buyer_email')) {
                $table->dropColumn('buyer_email');
            }
            if (Schema::hasColumn('transactions', 'buyer_phone')) {
                $table->dropColumn('buyer_phone');
            }
            if (Schema::hasColumn('transactions', 'buyer_address')) {
                $table->dropColumn('buyer_address');
            }
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};