<?php

namespace App\Services\TelegramHandlers;

use App\Models\TelegramConversation;
use App\Models\User;
use Telegram\Bot\Api;

class AuthenticatedHandler
{
    protected $telegram;
    
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }
    
    public function handle($chatId, $text, TelegramConversation $conversation)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            $conversation->update(['state' => 'awaiting_email']);
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.account_not_linked')
            ]);
        }
        
        // Handle authenticated user commands/messages
        return match(strtolower($text)) {
            '/profile' => $this->showProfile($chatId, $user),
            '/help' => $this->showHelp($chatId),
            '/logout' => $this->logout($chatId, $user, $conversation),
            default => $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.hello_user', ['name' => $user->name])
            ])
        };
    }
    
    protected function showProfile($chatId, User $user)
    {
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.profile_info', [
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type_str,
                'status' => $user->status_str
            ])
        ]);
    }
    
    protected function showHelp($chatId)
    {
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.available_commands')
        ]);
    }
    
    protected function logout($chatId, User $user, TelegramConversation $conversation)
    {
        $user->update(['telegram_chat_id' => null]);
        $conversation->update(['state' => 'awaiting_email', 'data' => null]);
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.account_unlinked')
        ]);
    }
}