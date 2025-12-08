<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'transaction_id',
        'gross_amount',
        'platform_fee',
        'payment_gateway_fee',
        'delivery_fee',
        'tax_amount',
        'discount_amount',
        'net_amount',
        'platform_fee_percentage',
        'platform_fee_type',
        'status',
        'payout_status',
        'payout_method',
        'payout_reference',
        'payout_amount',
        'payout_date',
        'processed_by',
        'earning_date',
        'period',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'payment_gateway_fee' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'platform_fee_percentage' => 'decimal:2',
        'payout_amount' => 'decimal:2',
        'earning_date' => 'date',
        'payout_date' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Static Helper Methods
    public static function createFromTransaction(Transaction $transaction, $platformFeePercentage = 5)
    {
        $grossAmount = $transaction->total_amount;
        $platformFee = ($grossAmount * $platformFeePercentage) / 100;
        $netAmount = $grossAmount - $platformFee;

        return self::create([
            'farmer_id' => $transaction->farmer_id,
            'transaction_id' => $transaction->id,
            'gross_amount' => $grossAmount,
            'platform_fee' => $platformFee,
            'platform_fee_percentage' => $platformFeePercentage,
            'platform_fee_type' => 'percentage',
            'payment_gateway_fee' => 0,
            'delivery_fee' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'net_amount' => $netAmount,
            'status' => 'pending',
            'payout_status' => 'unpaid',
            'earning_date' => now()->toDateString(),
            'period' => now()->format('Y-m'),
        ]);
    }

    // Helper Methods
    public function markAsPaid($payoutMethod, $payoutReference, $processedBy)
    {
        $this->payout_status = 'paid';
        $this->payout_method = $payoutMethod;
        $this->payout_reference = $payoutReference;
        $this->payout_amount = $this->net_amount;
        $this->payout_date = now();
        $this->processed_by = $processedBy;
        $this->save();
    }

    public function markAsOnHold($reason = null)
    {
        $this->payout_status = 'on_hold';
        if ($reason) {
            $this->notes = $reason;
        }
        $this->save();
    }

    public function recalculateNetAmount()
    {
        $this->net_amount = $this->gross_amount 
                          - $this->platform_fee 
                          - $this->payment_gateway_fee 
                          - $this->tax_amount 
                          - $this->discount_amount 
                          + $this->delivery_fee; // Delivery fee goes to farmer

        $this->save();
    }

    // Scope Methods
    public function scopeUnpaid($query)
    {
        return $query->where('payout_status', 'unpaid');
    }

    public function scopePending($query)
    {
        return $query->where('payout_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payout_status', 'paid');
    }

    public function scopeForPeriod($query, $period)
    {
        return $query->where('period', $period);
    }
}
