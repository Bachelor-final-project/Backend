<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;

class HelpCommand extends BaseCommand
{
    public function handle($chatId, $user, $params = [])
    {
        return $this->sendMessage($chatId, __('telegram.available_commands'));
    }
}