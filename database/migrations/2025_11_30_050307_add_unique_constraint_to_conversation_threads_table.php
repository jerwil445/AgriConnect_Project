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
        // Postgres-compatible unique index creation with safe existence check
        DB::statement(<<<'SQL'
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM pg_indexes
        WHERE schemaname = 'public'
        AND tablename = 'conversation_threads'
        AND indexname = 'conversation_threads_buyer_id_farmer_id_unique'
    ) THEN
        CREATE UNIQUE INDEX conversation_threads_buyer_id_farmer_id_unique
        ON conversation_threads (buyer_id, farmer_id);
    END IF;
END$$;
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Postgres-compatible index drop with safe existence check
        DB::statement(<<<'SQL'
DO $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM pg_indexes
        WHERE schemaname = 'public'
        AND tablename = 'conversation_threads'
        AND indexname = 'conversation_threads_buyer_id_farmer_id_unique'
    ) THEN
        DROP INDEX conversation_threads_buyer_id_farmer_id_unique;
    END IF;
END$$;
SQL
        );
    }
};