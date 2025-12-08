<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\MatchingService;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'farmer_id',
        'egg_type',
        'quality_grade',
        'color',
        'average_weight_grams',
        'is_organic',
        'is_free_range',
        'hen_breed',
        'jumbo',
        'description',
        'quantity',
        'unit',
        'price',
        'harvest_date',
        'laying_date',
        'shelf_life_days',
        'expiry_date',
        'storage_condition',
        'storage_temperature',
        'address',
        'province',
        'city',
        'barangay',
        'postal_code',
        'status',
        'image',
        // Inventory Management
        'initial_quantity',
        'sold_quantity',
        'reserved_quantity',
        'available_quantity',
        'minimum_order_quantity',
        'maximum_order_quantity',
        'reorder_level',
        'low_stock_alert',
        // Pricing
        'original_price',
        'discount_percentage',
        'is_negotiable',
        'minimum_acceptable_price',
        // Delivery
        'delivery_radius_km',
        'offers_delivery',
        'offers_pickup',
        'delivery_fee',
        // Certifications
        'fda_approved',
        'batch_number',
        'certification_documents',
        // Performance
        'view_count',
        'inquiry_count',
        'order_count',
        'conversion_rate',
        // Visibility
        'is_featured',
        'is_promoted',
        'featured_until',
        'priority_order',
        // Timestamps
        'last_restocked_at',
        'last_sold_at',
        'published_at',
    ];
    
    protected $casts = [
        'harvest_date' => 'date',
        'laying_date' => 'date',
        'expiry_date' => 'date',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'minimum_acceptable_price' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'average_weight_grams' => 'decimal:2',
        'storage_temperature' => 'decimal:1',
        'delivery_radius_km' => 'decimal:2',
        'jumbo' => 'boolean',
        'is_organic' => 'boolean',
        'is_free_range' => 'boolean',
        'low_stock_alert' => 'boolean',
        'is_negotiable' => 'boolean',
        'offers_delivery' => 'boolean',
        'offers_pickup' => 'boolean',
        'fda_approved' => 'boolean',
        'is_featured' => 'boolean',
        'is_promoted' => 'boolean',
        'certification_documents' => 'array',
        'last_restocked_at' => 'datetime',
        'last_sold_at' => 'datetime',
        'published_at' => 'datetime',
        'featured_until' => 'datetime',
        'deleted_at' => 'datetime',
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
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'product_id');
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function analytics()
    {
        return $this->hasMany(ProductAnalytic::class);
    }

    public function reviews()
    {
        return $this->hasMany(FarmerReview::class);
    }

    // Helper Methods
    public function incrementView()
    {
        $this->increment('view_count');
    }

    public function incrementInquiry()
    {
        $this->increment('inquiry_count');
    }

    public function incrementOrder()
    {
        $this->increment('order_count');
        $this->last_sold_at = now();
        $this->save();
        $this->updateConversionRate();
    }

    public function updateConversionRate()
    {
        if ($this->view_count > 0) {
            $this->conversion_rate = ($this->order_count / $this->view_count) * 100;
            $this->save();
        }
    }

    public function updateInventory($quantityChange, $type = 'sale')
    {
        if ($type === 'sale') {
            $this->sold_quantity += abs($quantityChange);
            $this->quantity -= abs($quantityChange);
        } elseif ($type === 'restock') {
            $this->quantity += abs($quantityChange);
        } elseif ($type === 'reservation') {
            $this->reserved_quantity += abs($quantityChange);
        } elseif ($type === 'cancellation') {
            $this->reserved_quantity -= abs($quantityChange);
        }

        $this->available_quantity = $this->quantity - $this->reserved_quantity;
        
        // Check low stock
        if ($this->available_quantity <= $this->reorder_level) {
            $this->low_stock_alert = true;
        }

        // Update status
        if ($this->available_quantity <= 0) {
            $this->status = 'Sold Out';
        } elseif ($this->status === 'Sold Out' && $this->available_quantity > 0) {
            $this->status = 'Available';
        }

        $this->save();
    }

    public function calculateEffectivePrice()
    {
        if ($this->discount_percentage > 0) {
            return $this->price * (1 - ($this->discount_percentage / 100));
        }
        return $this->price;
    }

    public function isAvailable()
    {
        return $this->status === 'Available' && $this->available_quantity > 0;
    }

    public function isFeatured()
    {
        return $this->is_featured && (!$this->featured_until || $this->featured_until->isFuture());
    }
}