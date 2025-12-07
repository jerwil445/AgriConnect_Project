<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemainingInventory extends Model
{
    protected $table = 'remaining_inventory';
    
    protected $fillable = [
        'product_id',
        'original_quantity',
        'original_price',
        'original_total_trays',
        'remaining_quantity',
        'remaining_price',
        'remaining_total_trays',
        'per_size_remaining',
        'last_updated'
    ];
    
    protected $casts = [
        'per_size_remaining' => 'array',
        'original_quantity' => 'integer',
        'original_price' => 'decimal:2',
        'original_total_trays' => 'integer',
        'remaining_quantity' => 'integer',
        'remaining_price' => 'decimal:2',
        'remaining_total_trays' => 'integer',
        'last_updated' => 'datetime'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
