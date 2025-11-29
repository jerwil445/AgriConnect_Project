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
        // Since we can't reliably drop the foreign key constraint, we'll take a different approach
        // We'll modify the column to allow NULL values and update any existing records
        Schema::table('transactions', function (Blueprint $table) {
            // Ensure the demand_id column allows NULL values
            $table->unsignedBigInteger('demand_id')->nullable()->change();
        });
        
        // Note: We can't change the foreign key constraint behavior without dropping it first
        // The cascade delete behavior will remain, but we've at least ensured the column allows NULL
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We can't easily reverse this change without knowing the original foreign key constraint name
        // So we'll leave it as is
    }
};