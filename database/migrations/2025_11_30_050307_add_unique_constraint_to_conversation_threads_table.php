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
        Schema::table('conversation_threads', function (Blueprint $table) {
            // Add unique constraint to prevent duplicate conversation threads between the same buyer and farmer
            $table->unique(['buyer_id', 'farmer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_threads', function (Blueprint $table) {
            // Remove unique constraint
            $table->dropUnique(['buyer_id', 'farmer_id']);
        });
    }
};
