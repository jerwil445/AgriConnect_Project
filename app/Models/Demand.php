<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'buyer_id',
        'product_name',
        'quantity',
        'location',
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