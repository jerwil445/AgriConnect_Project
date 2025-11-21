<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'farmer_id',
        'product_name',
        'quantity',
        'unit',
        'price',
        'harvest_date',
        'status',
        'image'
    ];
    
    protected $casts = [
        'harvest_date' => 'date',
        'price' => 'decimal:2',
    ];
    
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function matches()
    {
        return $this->hasMany(DemandMatch::class, 'product_id');
    }
}
