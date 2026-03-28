<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
DO $$
BEGIN
    ALTER TABLE conversation_threads
    ADD CONSTRAINT conversation_threads_buyer_id_farmer_id_unique
    UNIQUE (buyer_id, farmer_id);
EXCEPTION
    WHEN duplicate_table THEN
        NULL;
    WHEN duplicate_object THEN
        NULL;
END $$;
SQL);
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
