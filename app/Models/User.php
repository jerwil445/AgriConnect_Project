<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'sex',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'kyc_status',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the farmer profile associated with the user.
     */
    public function farmer()
    {
        return $this->hasOne(Farmer::class);
    }
    
    /**
     * Get the buyer profile associated with the user.
     */
    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }
    
    /**
     * Get the demands posted by the user (if they are a buyer).
     */
    public function demands()
    {
        return $this->hasMany(Demand::class, 'buyer_id');
    }
    
    /**
     * Get the matches related to demands posted by the user.
     */
    public function demandMatches()
    {
        return $this->hasManyThrough(DemandMatch::class, Demand::class, 'buyer_id', 'demand_id');
    }

    /**
     * Get all verifications for the user.
     */
    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }

    /**
     * Check if the user is fully verified.
     */
    public function isVerified()
    {
        if ($this->role === 'farmer') {
            return $this->farmer && $this->farmer->is_verified;
        } elseif ($this->role === 'buyer') {
            return $this->buyer && $this->buyer->verified;
        }
        return $this->kyc_status === 'verified';
    }

    /**
     * Calculate profile completeness percentage.
     */
    public function profileCompleteness()
    {
        $baseFields = ['first_name', 'last_name', 'email', 'phone_number', 'address', 'profile_picture'];
        $filled = 0;

        foreach ($baseFields as $field) {
            if (!empty($this->$field)) $filled++;
        }

        $totalFields = count($baseFields);

        if ($this->role === 'farmer' && $this->farmer) {
            $farmerFields = ['farm_name', 'farm_size', 'main_category', 'farm_address'];
            foreach ($farmerFields as $field) {
                if (!empty($this->farmer->$field)) $filled++;
            }
            $totalFields += count($farmerFields);
        } elseif ($this->role === 'buyer' && $this->buyer) {
            $buyerFields = ['company_name', 'business_type', 'address'];
            foreach ($buyerFields as $field) {
                if (!empty($this->buyer->$field)) $filled++;
            }
            $totalFields += count($buyerFields);
        }

        return round(($filled / $totalFields) * 100);
    }
}