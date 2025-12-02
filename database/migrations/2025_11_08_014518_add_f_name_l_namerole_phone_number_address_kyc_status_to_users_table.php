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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->enum('role', ['admin','farmer','buyer'])->default('buyer')->after('password');
            $table->string('phone_number')->nullable()->after('role');
            $table->string('address')->nullable()->after('phone_number');
            $table->enum('kyc_status', ['pending','verified','rejected'])->default('pending')->after('address');
        });
    }

    /**
     * Reverse the migrations.3
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name','last_name','role','phone_number','address','kyc_status']);
        });
    }
};
