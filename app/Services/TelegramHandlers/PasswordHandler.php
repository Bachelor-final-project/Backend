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
                'text' => __('telegram.session_expired')
            ]);
        }
        
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.invalid_password')
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
            'text' => __('telegram.welcome_linked', ['name' => $user->name])
        ]);
    }
}