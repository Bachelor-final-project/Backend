<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;

class ProfileCommand extends BaseCommand
{
    public function handle($chatId, $user, $params = [])
    {
        return $this->sendMessage($chatId, __('telegram.profile_info', [
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->type_str,
            'status' => $user->status_str
        ]));
    }
}