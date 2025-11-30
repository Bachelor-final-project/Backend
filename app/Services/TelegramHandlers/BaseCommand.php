<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;
use Telegram\Bot\Api;

abstract class BaseCommand
{
    protected $telegram;
    
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }
    
    abstract public function handle($chatId, $user, $params = []);
    
    public function canExecute(User $user): bool
    {
        return true;
    }
    
    protected function sendMessage($chatId, $text, $replyMarkup = null)
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text
        ];
        
        if ($replyMarkup) {
            $params['reply_markup'] = json_encode($replyMarkup);
        }
        
        return $this->telegram->sendMessage($params);
    }
}