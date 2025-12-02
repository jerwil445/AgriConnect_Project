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
        Schema::table('products', function (Blueprint $table) {
            // Update existing records to use the new status format
            DB::table('products')->where('status', 'available')->update(['status' => 'Available']);
            DB::table('products')->where('status', 'sold_out')->update(['status' => 'Sold Out']);
            DB::table('products')->where('status', 'pending')->update(['status' => 'Pending']);
            
            // Update the default value for the status column
            $table->string('status')->default('Available')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert the default value for the status column
            $table->string('status')->default('available')->change();
            
            // Update existing records to use the old status format
            DB::table('products')->where('status', 'Available')->update(['status' => 'available']);
            DB::table('products')->where('status', 'Sold Out')->update(['status' => 'sold_out']);
            DB::table('products')->where('status', 'Pending')->update(['status' => 'pending']);
        });
    }
};