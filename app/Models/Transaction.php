<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'farmer_id',
        'product_id',
        'demand_id',
        'final_quantity',
        'final_price',
        'total_amount',
        'tray_counts',
        'size_details',
        'payment_status',
        'delivery_status',
        'negotiation_messages',
        'status',
        'initiator_id', // Add the new field
        'conversation_thread_id', // Add conversation thread relationship
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_address',
        'payment_method'
    ];

    protected $casts = [
        'tray_counts' => 'array',
        'size_details' => 'array',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    // Relationship to the user who initiated the conversation
    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    // Relationship to conversation thread
    public function conversationThread()
    {
        return $this->belongsTo(ConversationThread::class);
    }

    // Relationship to size transactions
    public function sizeTransactions()
    {
        return $this->hasMany(SizeTransaction::class);
    }
}