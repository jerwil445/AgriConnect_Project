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
            // Check if the unique constraint already exists before adding it
            $indexExists = \DB::select(
                "SHOW KEYS FROM conversation_threads WHERE Key_name = 'conversation_threads_buyer_id_farmer_id_unique'"
            );
            
            if (empty($indexExists)) {
                // Add unique constraint to prevent duplicate conversation threads between the same buyer and farmer
                $table->unique(['buyer_id', 'farmer_id'], 'conversation_threads_buyer_id_farmer_id_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_threads', function (Blueprint $table) {
            // Check if the unique constraint exists before dropping it
            $indexExists = \DB::select(
                "SHOW KEYS FROM conversation_threads WHERE Key_name = 'conversation_threads_buyer_id_farmer_id_unique'"
            );
            
            if (!empty($indexExists)) {
                // Remove unique constraint
                $table->dropUnique('conversation_threads_buyer_id_farmer_id_unique');
            }
        });
    }
};