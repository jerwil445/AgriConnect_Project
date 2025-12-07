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
        Schema::table('demands', function (Blueprint $table) {
            // Remove the old address column if it exists
            if (Schema::hasColumn('demands', 'address')) {
                $table->dropColumn('address');
            }
            
            // Add new address fields
            $table->string('purok_street')->nullable()->after('location');
            $table->string('barangay')->nullable()->after('purok_street');
            $table->string('municipality_city')->nullable()->after('barangay');
            $table->string('province')->nullable()->after('municipality_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demands', function (Blueprint $table) {
            // Remove the new address fields
            $table->dropColumn(['purok_street', 'barangay', 'municipality_city', 'province']);
            
            // Add back the old address column
            $table->string('address')->nullable()->after('location');
        });
    }
};
