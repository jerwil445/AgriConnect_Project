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
        Schema::table('conversation_threads', function (Blueprint $table) {
            $table->unique(['buyer_id', 'farmer_id'], 'conversation_threads_buyer_id_farmer_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: constraint already handled in database or by separate maintenance.
        // Avoid errors when restructure cannot be returned exactly due existing objects.
    }
};