<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MatchingService;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'farmer_id',
        'egg_type',
        'jumbo',
        'description',
        'quantity',
        'unit',
        'price',
        'harvest_date',
        'address',
        'status',
        'image'
    ];
    
    protected $casts = [
        'harvest_date' => 'date',
        'price' => 'decimal:2',
        'jumbo' => 'boolean',
    ];
    
    // Boot the model
    protected static function boot()
    {
        parent::boot();
        
        // When a product is created, check for matching demands with zero matches
        static::created(function ($product) {
            // Only run this for available products
            if ($product->status === 'Available') {
                // Resolve the matching service from the container and call the method
                app()->make(MatchingService::class)->matchNewProductWithZeroMatchDemands($product);
            }
        });
    }
    
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
    
    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
}