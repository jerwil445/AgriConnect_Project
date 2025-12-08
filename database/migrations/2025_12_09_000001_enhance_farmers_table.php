<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Enhance farmers table with business and payment information
     */
    public function up(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            // Business Information
            $table->string('business_registration_number')->nullable()->after('farm_address');
            $table->string('business_type')->default('Individual')->after('business_registration_number'); // Individual, Partnership, Corporation
            $table->string('tax_id_number')->nullable()->after('business_type');
            
            // Contact & Communication
            $table->string('secondary_phone')->nullable()->after('tax_id_number');
            $table->string('whatsapp_number')->nullable()->after('secondary_phone');
            
            // Payment Information
            $table->string('bank_name')->nullable()->after('whatsapp_number');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
            $table->string('mobile_wallet_provider')->nullable()->after('bank_account_name'); // GCash, PayMaya, etc.
            $table->string('mobile_wallet_number')->nullable()->after('mobile_wallet_provider');
            
            // Certifications & Compliance
            $table->string('organic_certification')->nullable()->after('certification');
            $table->date('certification_expiry_date')->nullable()->after('organic_certification');
            $table->string('food_safety_certification')->nullable()->after('certification_expiry_date');
            $table->boolean('gmp_certified')->default(false)->after('food_safety_certification'); // Good Manufacturing Practice
            $table->boolean('halal_certified')->default(false)->after('gmp_certified');
            
            // Farm Details
            $table->string('farm_size_unit')->default('hectares')->after('farm_size'); // hectares, acres, sq meters
            $table->decimal('latitude', 10, 7)->nullable()->after('farm_size_unit');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->integer('total_chickens')->nullable()->after('longitude');
            $table->string('farming_method')->nullable()->after('total_chickens'); // Free-range, Cage-free, Organic, etc.
            
            // Performance Metrics
            $table->decimal('average_rating', 3, 2)->default(0.00)->after('farming_method');
            $table->integer('total_reviews')->default(0)->after('average_rating');
            $table->integer('completed_orders')->default(0)->after('total_reviews');
            $table->decimal('success_rate', 5, 2)->default(0.00)->after('completed_orders');
            
            // Status & Verification
            $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified')->after('success_rate');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            $table->text('verification_notes')->nullable()->after('verified_by');
            
            // Operational
            $table->boolean('is_active')->default(true)->after('verification_notes');
            $table->boolean('accepting_orders')->default(true)->after('is_active');
            $table->time('operation_start_time')->nullable()->after('accepting_orders');
            $table->time('operation_end_time')->nullable()->after('operation_start_time');
            $table->json('operation_days')->nullable()->after('operation_end_time'); // ['Monday', 'Tuesday', etc.]
            
            // Soft delete
            $table->softDeletes()->after('updated_at');
            
            // Indexes for better query performance
            $table->index('verification_status');
            $table->index('is_active');
            $table->index('average_rating');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropIndex(['verification_status']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['average_rating']);
            $table->dropIndex(['latitude', 'longitude']);
            
            $table->dropSoftDeletes();
            $table->dropColumn([
                'business_registration_number',
                'business_type',
                'tax_id_number',
                'secondary_phone',
                'whatsapp_number',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'mobile_wallet_provider',
                'mobile_wallet_number',
                'organic_certification',
                'certification_expiry_date',
                'food_safety_certification',
                'gmp_certified',
                'halal_certified',
                'farm_size_unit',
                'latitude',
                'longitude',
                'total_chickens',
                'farming_method',
                'average_rating',
                'total_reviews',
                'completed_orders',
                'success_rate',
                'verification_status',
                'verified_at',
                'verified_by',
                'verification_notes',
                'is_active',
                'accepting_orders',
                'operation_start_time',
                'operation_end_time',
                'operation_days'
            ]);
        });
    }
};
