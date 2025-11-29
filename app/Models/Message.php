<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'transaction_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at',
        'conversation_thread_id' // Add conversation thread relationship
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Relationship to conversation thread
    public function conversationThread()
    {
        return $this->belongsTo(ConversationThread::class);
    }
}