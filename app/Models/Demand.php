<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'buyer_id',
        'egg_type',
        'egg_size',
        'quantity',
        'location',
        'address',
        'delivery_date'
    ];
    
    protected $casts = [
        'delivery_date' => 'date',
    ];
    
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    
    public function matches()
    {
        return $this->hasMany(DemandMatch::class, 'demand_id');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'demand_id');
    }
    
    /**
     * Check if demand has any accepted matches (status = 'Matched')
     */
    public function hasAcceptedMatch()
    {
        return $this->matches()->where('status', 'Matched')->exists();
    }
    
    /**
     * Check if demand has any transactions
     */
    public function hasTransaction()
    {
        return $this->transactions()->exists();
    }
}