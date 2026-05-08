<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'category',
        'product_name',
        'variety_size',
        'quantity',
        'unit',
        'purok_street',
        'barangay',
        'municipality_city',
        'province',
        'delivery_date',
        'deadline',
        'status',
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
