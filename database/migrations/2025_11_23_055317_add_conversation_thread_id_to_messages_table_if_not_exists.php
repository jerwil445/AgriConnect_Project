<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the column already exists
        if (!Schema::hasColumn('messages', 'conversation_thread_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->unsignedBigInteger('conversation_thread_id')->nullable()->after('transaction_id');
                $table->foreign('conversation_thread_id')->references('id')->on('conversation_threads')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('messages', 'conversation_thread_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropForeign(['conversation_thread_id']);
                $table->dropColumn('conversation_thread_id');
            });
        }
    }
};