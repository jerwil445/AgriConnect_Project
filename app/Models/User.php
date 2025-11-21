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
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'kyc_status',
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
}