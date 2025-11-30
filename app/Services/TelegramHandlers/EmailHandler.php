<?php

namespace App\Services\TelegramHandlers;

use App\Models\TelegramConversation;
use App\Models\User;
use Telegram\Bot\Api;

class EmailHandler
{
    protected $telegram;
    
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }
    
    public function start($chatId, TelegramConversation $conversation)
    {
        // Check if user is already linked
        $user = User::where('telegram_chat_id', $chatId)->first();
        if ($user) {
            $conversation->update(['state' => 'authenticated']);
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Welcome back, {$user->name}! You are already logged in."
            ]);
        }
        
        $conversation->update(['state' => 'awaiting_email']);
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Please enter your email address:'
        ]);
    }
    
    public function handle($chatId, $email, TelegramConversation $conversation)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Invalid email format. Please enter a valid email address:'
            ]);
        }
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Email not found. Please enter a valid email address:'
            ]);
        }
        
        $conversation->update([
            'state' => 'awaiting_password',
            'data' => ['email' => $email]
        ]);
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Please enter your password:'
        ]);
    }
}