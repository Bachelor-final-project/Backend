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
        $chatId = $request->input('message.chat.id') ?? $request->input('callback_query.message.chat.id');
        
        if ($chatId) {
            $user = User::where('telegram_chat_id', $chatId)->first();
            if ($user) {
                Auth::login($user);
                $request->merge(['authenticated_user' => $user]);
            }
        }
        
        return $next($request);
    }
}