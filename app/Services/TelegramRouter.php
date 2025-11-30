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
        \Log::info('TelegramRouter dispatching', ['update' => $update]);
        
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'] ?? null;
        $text = $update['message']['text'] ?? $update['callback_query']['data'] ?? '';
        
        \Log::info('Extracted data', ['chat_id' => $chatId, 'text' => $text]);
        
        if (!$chatId) {
            \Log::warning('No chat ID found in update');
            return;
        }
        
        $conversation = TelegramConversation::firstOrCreate(
            ['chat_id' => $chatId],
            ['state' => 'awaiting_email']
        );
        
        \Log::info('Conversation state', ['state' => $conversation->state, 'chat_id' => $chatId]);
        
        // Handle commands
        if (str_starts_with($text, '/')) {
            \Log::info('Handling command', ['command' => $text]);
            return $this->handleCommand($text, $chatId, $conversation);
        }
        
        // Route based on conversation state
        \Log::info('Routing to handler', ['state' => $conversation->state]);
        return match($conversation->state) {
            'awaiting_email' => app(EmailHandler::class)->handle($chatId, $text, $conversation),
            'awaiting_password' => app(PasswordHandler::class)->handle($chatId, $text, $conversation),
            'authenticated' => app(AuthenticatedHandler::class)->handle($chatId, $text, $conversation),
            default => $this->sendMessage($chatId, __('telegram.start_with_login'))
        };
    }
    
    protected function handleCommand($command, $chatId, $conversation)
    {
        return match($command) {
            '/start', '/login' => app(EmailHandler::class)->start($chatId, $conversation),
            '/profile', '/help', '/logout', '/warehouses' => app(AuthenticatedHandler::class)->handle($chatId, $command, $conversation),
            default => $this->sendMessage($chatId, __('telegram.unknown_command'))
        };
    }
    
    public function sendMessage($chatId, $text)
    {
        try {
            \Log::info('Sending Telegram message', ['chat_id' => $chatId, 'text' => $text]);
            
            $response = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $text
            ]);
            
            \Log::info('Message sent successfully', ['response' => $response]);
            return $response;
        } catch (\Exception $e) {
            \Log::error('Failed to send Telegram message', [
                'chat_id' => $chatId,
                'text' => $text,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}