<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create farmer_reviews table for buyer feedback and ratings
     */
    public function up(): void
    {
        Schema::create('farmer_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('product_id')->nullable();
            
            // Rating Breakdown
            $table->decimal('overall_rating', 3, 2); // 1.00 to 5.00
            $table->decimal('product_quality_rating', 3, 2)->nullable();
            $table->decimal('delivery_rating', 3, 2)->nullable();
            $table->decimal('communication_rating', 3, 2)->nullable();
            $table->decimal('packaging_rating', 3, 2)->nullable();
            
            // Review Content
            $table->text('comment')->nullable();
            $table->text('reply')->nullable(); // Farmer's reply to review
            $table->timestamp('replied_at')->nullable();
            
            // Review Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'flagged'])->default('approved');
            $table->boolean('is_verified_purchase')->default(true);
            $table->boolean('is_helpful')->default(false);
            $table->integer('helpful_count')->default(0);
            
            // Moderation
            $table->unsignedBigInteger('moderated_by')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->text('moderation_notes')->nullable();
            
            // Media
            $table->json('images')->nullable(); // Array of image paths
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            
            // Indexes
            $table->index('farmer_id');
            $table->index('overall_rating');
            $table->index('status');
            $table->index(['farmer_id', 'overall_rating']);
            $table->unique(['buyer_id', 'transaction_id']); // One review per transaction
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_reviews');
    }
};
