<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'farmer_id',
        'product_id',
        'egg_type',
        'size_name',
        'tray_count',
        'price_per_tray',
        'total_price'
    ];
    
    protected $casts = [
        'tray_count' => 'integer',
        'price_per_tray' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];
    
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}