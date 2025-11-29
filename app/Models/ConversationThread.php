<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationThread extends Model
{
    protected $fillable = [
        'buyer_id',
        'farmer_id'
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}