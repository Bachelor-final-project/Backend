<?php

return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'bot_username' => env('TELEGRAM_BOT_USERNAME'),
    'webhook_url' => env('TELEGRAM_WEBHOOK_URL', env('APP_URL') . '/telegram/webhook'),
];