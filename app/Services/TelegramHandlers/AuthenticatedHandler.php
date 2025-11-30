<?php

namespace App\Services\TelegramHandlers;

use App\Models\TelegramConversation;
use App\Models\User;
use Telegram\Bot\Api;

class AuthenticatedHandler
{
    protected $telegram;
    protected $commands = [];
    
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
        $this->registerCommands();
    }
    
    private function registerCommands()
    {
        $this->commands = [
            '/profile' => ProfileCommand::class,
            '/help' => HelpCommand::class,
            '/warehouses' => WarehouseCommand::class,
            '/logout' => LogoutCommand::class,
            'search' => SearchCommand::class,
        ];
    }
    
    public function handle($chatId, $text, TelegramConversation $conversation)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            $conversation->update(['state' => 'awaiting_email']);
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.account_not_linked')
            ]);
        }
        
        // Handle search queries
        if (str_starts_with($text, 'بحث ')) {
            $searchQuery = trim(mb_substr($text, 4));
            return $this->executeCommand('search', $chatId, $user, ['query' => $searchQuery]);
        }
        
        // Handle callback queries (warehouse selection)
        if (str_starts_with($text, 'warehouse_')) {
            $warehouseId = str_replace('warehouse_', '', $text);
            return $this->executeCommand('/warehouses', $chatId, $user, ['warehouse_id' => $warehouseId]);
        }
        
        // Handle commands
        $command = strtolower($text);
        if (isset($this->commands[$command])) {
            $params = $command === '/logout' ? ['conversation' => $conversation] : [];
            return $this->executeCommand($command, $chatId, $user, $params);
        }
        
        // Default response
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.hello_user', ['name' => $user->name])
        ]);
    }
    
    private function executeCommand($commandKey, $chatId, $user, $params = [])
    {
        if (!isset($this->commands[$commandKey])) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.unknown_command')
            ]);
        }
        
        $commandClass = $this->commands[$commandKey];
        $command = new $commandClass();
        
        if (!$command->canExecute($user)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.unauthorized')
            ]);
        }
        
        return $command->handle($chatId, $user, $params);
    }
}