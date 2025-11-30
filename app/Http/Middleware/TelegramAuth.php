<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TelegramAuth
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('TelegramAuth middleware processing', ['request_data' => $request->all()]);
        
        $chatId = $request->input('message.chat.id') ?? $request->input('callback_query.message.chat.id');
        
        \Log::info('TelegramAuth extracted chat_id', ['chat_id' => $chatId]);
        
        if ($chatId) {
            $user = User::where('telegram_chat_id', $chatId)->first();
            if ($user) {
                \Log::info('TelegramAuth user found and logged in', ['user_id' => $user->id]);
                Auth::login($user);
                $request->merge(['authenticated_user' => $user]);
            } else {
                \Log::info('TelegramAuth no user found for chat_id', ['chat_id' => $chatId]);
            }
        }
        
        return $next($request);
    }
}