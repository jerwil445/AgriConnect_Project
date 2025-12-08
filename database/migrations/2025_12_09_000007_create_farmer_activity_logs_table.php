<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create farmer_activity_logs table for audit trail
     */
    public function up(): void
    {
        Schema::create('farmer_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('user_id'); // The user who performed the action
            
            // Activity Details
            $table->string('action'); // created, updated, deleted, viewed, etc.
            $table->string('entity_type'); // Product, Order, Message, etc.
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('description');
            
            // Context
            $table->json('old_values')->nullable(); // Before changes
            $table->json('new_values')->nullable(); // After changes
            $table->json('metadata')->nullable(); // Additional context
            
            // Request Information
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // Mobile, Desktop, Tablet
            
            // Tags for filtering
            $table->json('tags')->nullable(); // ['important', 'security', etc.]
            $table->enum('severity', ['info', 'warning', 'error', 'critical'])->default('info');
            
            $table->timestamp('created_at');
            
            // Foreign Keys
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('farmer_id');
            $table->index('action');
            $table->index('entity_type');
            $table->index('created_at');
            $table->index(['farmer_id', 'created_at']);
            $table->index(['farmer_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_activity_logs');
    }
};
