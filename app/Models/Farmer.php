<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'farm_name',
        'farm_size',
        'farm_size_unit',
        'experience_years',
        'certification',
        'farm_address',
        // Business Information
        'business_registration_number',
        'business_type',
        'tax_id_number',
        // Contact & Communication
        'secondary_phone',
        'whatsapp_number',
        // Payment Information
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'mobile_wallet_provider',
        'mobile_wallet_number',
        // Certifications & Compliance
        'organic_certification',
        'certification_expiry_date',
        'food_safety_certification',
        'gmp_certified',
        'halal_certified',
        // Farm Details
        'latitude',
        'longitude',
        'total_chickens',
        'farming_method',
        // Performance Metrics
        'average_rating',
        'total_reviews',
        'completed_orders',
        'success_rate',
        // Status & Verification
        'verification_status',
        'verified_at',
        'verified_by',
        'verification_notes',
        // Operational
        'is_active',
        'accepting_orders',
        'operation_start_time',
        'operation_end_time',
        'operation_days',
    ];

    protected $casts = [
        'farm_size' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'average_rating' => 'decimal:2',
        'success_rate' => 'decimal:2',
        'gmp_certified' => 'boolean',
        'halal_certified' => 'boolean',
        'is_active' => 'boolean',
        'accepting_orders' => 'boolean',
        'operation_days' => 'array',
        'verified_at' => 'datetime',
        'certification_expiry_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function reviews()
    {
        return $this->hasMany(FarmerReview::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(FarmerActivityLog::class);
    }

    public function earnings()
    {
        return $this->hasMany(FarmerEarning::class);
    }

    // Helper Methods
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function canAcceptOrders()
    {
        return $this->is_active && $this->accepting_orders && $this->isVerified();
    }

    public function updateRating()
    {
        $this->average_rating = $this->reviews()->avg('overall_rating');
        $this->total_reviews = $this->reviews()->count();
        $this->save();
    }

    public function incrementCompletedOrders()
    {
        $this->increment('completed_orders');
        $this->updateSuccessRate();
    }

    public function updateSuccessRate()
    {
        $totalOrders = Transaction::where('farmer_id', $this->user_id)->count();
        if ($totalOrders > 0) {
            $this->success_rate = ($this->completed_orders / $totalOrders) * 100;
            $this->save();
        }
    }
}