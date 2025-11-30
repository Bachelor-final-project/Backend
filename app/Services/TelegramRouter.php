<?php

namespace App\Services;

use App\Models\TelegramConversation;
use App\Services\TelegramHandlers\EmailHandler;
use App\Services\TelegramHandlers\PasswordHandler;
use App\Services\TelegramHandlers\AuthenticatedHandler;
use Telegram\Bot\Api;

class TelegramRouter
{
    protected $telegram;
    
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }
    
    public function dispatch($update)
    {
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'] ?? null;
        $text = $update['message']['text'] ?? $update['callback_query']['data'] ?? '';
        
        if (!$chatId) return;
        
        $conversation = TelegramConversation::firstOrCreate(
            ['chat_id' => $chatId],
            ['state' => 'awaiting_email']
        );
        
        // Handle commands
        if (str_starts_with($text, '/')) {
            return $this->handleCommand($text, $chatId, $conversation);
        }
        
        // Route based on conversation state
        return match($conversation->state) {
            'awaiting_email' => app(EmailHandler::class)->handle($chatId, $text, $conversation),
            'awaiting_password' => app(PasswordHandler::class)->handle($chatId, $text, $conversation),
            'authenticated' => app(AuthenticatedHandler::class)->handle($chatId, $text, $conversation),
            default => $this->sendMessage($chatId, 'Please start with /login')
        };
    }
    
    protected function handleCommand($command, $chatId, $conversation)
    {
        return match($command) {
            '/start', '/login' => app(EmailHandler::class)->start($chatId, $conversation),
            default => $this->sendMessage($chatId, 'Unknown command. Use /login to start.')
        };
    }
    
    public function sendMessage($chatId, $text)
    {
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }
}