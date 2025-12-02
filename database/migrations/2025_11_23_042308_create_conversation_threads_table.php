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
        Schema::create('conversation_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('farmer_id');
            $table->timestamps();
            
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('farmer_id')->references('id')->on('users')->onDelete('cascade');
            
            // Add unique constraint to prevent duplicate conversation threads between the same buyer and farmer
            $table->unique(['buyer_id', 'farmer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_threads');
    }
};