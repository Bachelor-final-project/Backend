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
                'text' => 'Account not linked. Please enter your email address:'
            ]);
        }
        
        // Handle authenticated user commands/messages
        return match(strtolower($text)) {
            '/profile' => $this->showProfile($chatId, $user),
            '/help' => $this->showHelp($chatId),
            '/logout' => $this->logout($chatId, $user, $conversation),
            default => $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Hello {$user->name}! Use /help to see available commands."
            ])
        };
    }
    
    protected function showProfile($chatId, User $user)
    {
        $profile = "👤 Profile Information:\n\n";
        $profile .= "Name: {$user->name}\n";
        $profile .= "Email: {$user->email}\n";
        $profile .= "Type: {$user->type_str}\n";
        $profile .= "Status: {$user->status_str}";
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $profile
        ]);
    }
    
    protected function showHelp($chatId)
    {
        $help = "🤖 Available Commands:\n\n";
        $help .= "/profile - View your profile\n";
        $help .= "/help - Show this help message\n";
        $help .= "/logout - Unlink your Telegram account";
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $help
        ]);
    }
    
    protected function logout($chatId, User $user, TelegramConversation $conversation)
    {
        $user->update(['telegram_chat_id' => null]);
        $conversation->update(['state' => 'awaiting_email', 'data' => null]);
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Your Telegram account has been unlinked. Use /login to link again.'
        ]);
    }
}