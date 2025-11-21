<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandMatch extends Model
{
    use HasFactory;
    
    // Specify the table name since 'match' is a reserved word in some databases
    protected $table = 'matches';
    
    protected $fillable = [
        'product_id',
        'demand_id',
        'status',
        'matched_date'
    ];
    
    protected $casts = [
        'matched_date' => 'datetime',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }
}