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
        'egg_category',
        'egg_size',
        'quantity',
        // Removed 'location' as it's no longer used for matching
        'purok_street',
        'barangay',
        'municipality_city',
        'province',
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
}