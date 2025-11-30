<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;
use App\Models\TelegramConversation;

class LogoutCommand extends BaseCommand
{
    public function handle($chatId, $user, $params = [])
    {
        $conversation = $params['conversation'] ?? null;
        
        $user->update(['telegram_chat_id' => null]);
        
        if ($conversation) {
            $conversation->update(['state' => 'awaiting_email', 'data' => null]);
        }
        
        return $this->sendMessage($chatId, __('telegram.account_unlinked'));
    }
}