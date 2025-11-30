<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramConversation extends Model
{
    protected $fillable = ['chat_id', 'state', 'data'];
    
    protected $casts = [
        'data' => 'array',
    ];
    
    public const STATES = [
        'awaiting_email' => 'awaiting_email',
        'awaiting_password' => 'awaiting_password',
        'authenticated' => 'authenticated',
    ];
}