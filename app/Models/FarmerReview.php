<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmerReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farmer_id',
        'buyer_id',
        'transaction_id',
        'product_id',
        'overall_rating',
        'product_quality_rating',
        'delivery_rating',
        'communication_rating',
        'packaging_rating',
        'comment',
        'reply',
        'replied_at',
        'status',
        'is_verified_purchase',
        'is_helpful',
        'helpful_count',
        'moderated_by',
        'moderated_at',
        'moderation_notes',
        'images',
    ];

    protected $casts = [
        'overall_rating' => 'decimal:2',
        'product_quality_rating' => 'decimal:2',
        'delivery_rating' => 'decimal:2',
        'communication_rating' => 'decimal:2',
        'packaging_rating' => 'decimal:2',
        'is_verified_purchase' => 'boolean',
        'is_helpful' => 'boolean',
        'helpful_count' => 'integer',
        'replied_at' => 'datetime',
        'moderated_at' => 'datetime',
        'images' => 'array',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // Helper Methods
    public function approve()
    {
        $this->status = 'approved';
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function flag()
    {
        $this->status = 'flagged';
        $this->save();
    }

    public function addReply($replyText)
    {
        $this->reply = $replyText;
        $this->replied_at = now();
        $this->save();
    }

    public function incrementHelpful()
    {
        $this->increment('helpful_count');
        $this->is_helpful = true;
        $this->save();
    }
}
