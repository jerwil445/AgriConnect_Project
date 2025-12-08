<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'product_id',
        'size_id',
        'type',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'unit',
        'transaction_id',
        'order_id',
        'performed_by',
        'reason',
        'notes',
        'reference_number',
        'unit_price',
        'total_value',
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_change' => 'integer',
        'quantity_after' => 'integer',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
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

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // Static Helper Methods
    public static function logInventoryChange($data)
    {
        return self::create($data);
    }

    public static function logSale($product, $quantity, $transactionId, $userId)
    {
        return self::create([
            'farmer_id' => $product->farmer_id,
            'product_id' => $product->id,
            'type' => 'sale',
            'quantity_before' => $product->quantity + $quantity,
            'quantity_change' => -$quantity,
            'quantity_after' => $product->quantity,
            'unit' => $product->unit,
            'transaction_id' => $transactionId,
            'performed_by' => $userId,
            'reason' => 'Product sold',
            'unit_price' => $product->price,
            'total_value' => $product->price * $quantity,
        ]);
    }

    public static function logRestock($product, $quantity, $userId, $reason = null)
    {
        return self::create([
            'farmer_id' => $product->farmer_id,
            'product_id' => $product->id,
            'type' => 'restock',
            'quantity_before' => $product->quantity - $quantity,
            'quantity_change' => $quantity,
            'quantity_after' => $product->quantity,
            'unit' => $product->unit,
            'performed_by' => $userId,
            'reason' => $reason ?? 'Inventory restocked',
            'unit_price' => $product->price,
            'total_value' => $product->price * $quantity,
        ]);
    }
}
