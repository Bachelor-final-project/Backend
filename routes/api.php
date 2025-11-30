<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook', [App\Http\Controllers\TelegramController::class, 'webhook'])
    ->name('telegram.webhook');