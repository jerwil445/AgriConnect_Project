<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('farm_name');
            $table->decimal('farm_size', 8, 2)->nullable();
            $table->string('product_type')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('certification')->nullable();
            $table->text('farm_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('farmers');
    }
};
