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
        \Log::info('EmailHandler start', ['chat_id' => $chatId]);
        
        // Check if user is already linked
        $user = User::where('telegram_chat_id', $chatId)->first();
        if ($user) {
            \Log::info('User already linked', ['user_id' => $user->id, 'chat_id' => $chatId]);
            $conversation->update(['state' => 'authenticated']);
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.welcome_back', ['name' => $user->name])
            ]);
        }
        
        \Log::info('Starting new login flow', ['chat_id' => $chatId]);
        $conversation->update(['state' => 'awaiting_email']);
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.enter_email')
        ]);
    }
    
    public function handle($chatId, $email, TelegramConversation $conversation)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.invalid_email')
            ]);
        }
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.email_not_found')
            ]);
        }
        
        $conversation->update([
            'state' => 'awaiting_password',
            'data' => ['email' => $email]
        ]);
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.enter_password')
        ]);
    }
}