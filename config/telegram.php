<?php

return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'webhook_url' => env('TELEGRAM_WEBHOOK_URL', env('APP_URL') . '/telegram/webhook'),
];