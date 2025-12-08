<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'farmer_id',
        'product_id',
        'egg_type',
        'size_name',
        'tray_count',
        'price_per_tray',
        'total_price',
        // Inventory Management
        'initial_tray_count',
        'sold_tray_count',
        'reserved_tray_count',
        'available_tray_count',
        // Size Details
        'eggs_per_tray',
        'weight_per_egg_grams',
        'total_weight_kg',
        // Pricing & Discounts
        'original_price_per_tray',
        'discount_percentage',
        'has_special_offer',
        'special_offer_until',
        // Availability
        'availability_status',
        'low_stock_threshold',
        'allow_backorder',
        // Performance
        'order_count',
        'popularity_score',
        'last_sold_at',
    ];
    
    protected $casts = [
        'tray_count' => 'integer',
        'initial_tray_count' => 'integer',
        'sold_tray_count' => 'integer',
        'reserved_tray_count' => 'integer',
        'available_tray_count' => 'integer',
        'eggs_per_tray' => 'integer',
        'order_count' => 'integer',
        'low_stock_threshold' => 'integer',
        'price_per_tray' => 'decimal:2',
        'total_price' => 'decimal:2',
        'original_price_per_tray' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'weight_per_egg_grams' => 'decimal:2',
        'total_weight_kg' => 'decimal:2',
        'popularity_score' => 'decimal:2',
        'has_special_offer' => 'boolean',
        'allow_backorder' => 'boolean',
        'special_offer_until' => 'datetime',
        'last_sold_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    // Relationships
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sizeTransactions()
    {
        return $this->hasMany(SizeTransaction::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Helper Methods
    public function updateInventory($trayChange, $type = 'sale')
    {
        if ($type === 'sale') {
            $this->sold_tray_count += abs($trayChange);
            $this->tray_count -= abs($trayChange);
        } elseif ($type === 'restock') {
            $this->tray_count += abs($trayChange);
        } elseif ($type === 'reservation') {
            $this->reserved_tray_count += abs($trayChange);
        } elseif ($type === 'cancellation') {
            $this->reserved_tray_count -= abs($trayChange);
        }

        $this->available_tray_count = $this->tray_count - $this->reserved_tray_count;
        
        // Update availability status
        if ($this->available_tray_count <= 0) {
            $this->availability_status = 'out_of_stock';
        } elseif ($this->available_tray_count <= $this->low_stock_threshold) {
            $this->availability_status = 'low_stock';
        } else {
            $this->availability_status = 'available';
        }

        $this->save();
    }

    public function incrementOrder()
    {
        $this->increment('order_count');
        $this->last_sold_at = now();
        $this->save();
    }

    public function calculateEffectivePrice()
    {
        if ($this->has_special_offer && $this->special_offer_until && $this->special_offer_until->isFuture()) {
            return $this->price_per_tray * (1 - ($this->discount_percentage / 100));
        } elseif ($this->discount_percentage > 0) {
            return $this->price_per_tray * (1 - ($this->discount_percentage / 100));
        }
        return $this->price_per_tray;
    }

    public function isAvailable()
    {
        return $this->availability_status === 'available' || 
               ($this->availability_status === 'out_of_stock' && $this->allow_backorder);
    }
}