<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'size_id',
        'size_name',
        'tray_count',
        'price_per_tray',
        'total_price'
    ];

    protected $casts = [
        'price_per_tray' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}