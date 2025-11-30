<?php

namespace App\Services\TelegramHandlers;

use App\Models\TelegramConversation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Telegram\Bot\Api;

class PasswordHandler
{
    protected $telegram;
    
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }
    
    public function handle($chatId, $password, TelegramConversation $conversation)
    {
        $email = $conversation->data['email'] ?? null;
        if (!$email) {
            $conversation->update(['state' => 'awaiting_email']);
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Session expired. Please enter your email address:'
            ]);
        }
        
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Invalid password. Please try again:'
            ]);
        }
        
        // Link Telegram account
        $user->update(['telegram_chat_id' => $chatId]);
        
        $conversation->update([
            'state' => 'authenticated',
            'data' => null
        ]);
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Welcome, {$user->name}! Your Telegram account has been successfully linked. You can now use this bot to access your account."
        ]);
    }
}