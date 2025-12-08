<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'product_id',
        'date',
        'views',
        'unique_views',
        'detail_views',
        'inquiries',
        'messages_received',
        'favorites',
        'shares',
        'orders_placed',
        'orders_completed',
        'orders_cancelled',
        'revenue',
        'units_sold',
        'conversion_rate',
        'avg_order_value',
        'avg_response_time_minutes',
        'matches_generated',
        'matches_accepted',
        'match_conversion_rate',
        'avg_selling_price',
        'price_at_date',
    ];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'unique_views' => 'integer',
        'detail_views' => 'integer',
        'inquiries' => 'integer',
        'messages_received' => 'integer',
        'favorites' => 'integer',
        'shares' => 'integer',
        'orders_placed' => 'integer',
        'orders_completed' => 'integer',
        'orders_cancelled' => 'integer',
        'units_sold' => 'integer',
        'avg_response_time_minutes' => 'integer',
        'matches_generated' => 'integer',
        'matches_accepted' => 'integer',
        'revenue' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'avg_order_value' => 'decimal:2',
        'match_conversion_rate' => 'decimal:2',
        'avg_selling_price' => 'decimal:2',
        'price_at_date' => 'decimal:2',
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

    // Static Helper Methods
    public static function recordDailyMetrics($productId, $date = null)
    {
        $date = $date ?? Carbon::today();
        $product = Product::find($productId);

        if (!$product) {
            return null;
        }

        $analytic = self::firstOrCreate(
            [
                'product_id' => $productId,
                'date' => $date,
            ],
            [
                'farmer_id' => $product->farmer_id,
                'price_at_date' => $product->price,
            ]
        );

        return $analytic;
    }

    public function incrementView()
    {
        $this->increment('views');
    }

    public function incrementUniqueView()
    {
        $this->increment('unique_views');
    }

    public function incrementDetailView()
    {
        $this->increment('detail_views');
    }

    public function incrementInquiry()
    {
        $this->increment('inquiries');
    }

    public function incrementOrder($revenue, $units)
    {
        $this->increment('orders_placed');
        $this->revenue += $revenue;
        $this->units_sold += $units;
        $this->updateConversionRate();
        $this->updateAvgOrderValue();
        $this->save();
    }

    public function updateConversionRate()
    {
        if ($this->views > 0) {
            $this->conversion_rate = ($this->orders_placed / $this->views) * 100;
        }
    }

    public function updateAvgOrderValue()
    {
        if ($this->orders_placed > 0) {
            $this->avg_order_value = $this->revenue / $this->orders_placed;
        }
    }
}
