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
        
        // Handle transaction amount input
        $context = cache()->get('transaction_context_' . $chatId);
        if ($context && is_numeric($text)) {
            $warehouseCommand = new WarehouseCommand();
            return $warehouseCommand->handle($chatId, $user, [
                'action' => 'store_transaction',
                'amount' => $text
            ]);
        }
        
        // Handle callback data from inline keyboards
        if (str_starts_with($text, 'warehouse_')) {
            return $this->handleWarehouseCallback($chatId, $user, $text);
        }
        
        // Handle search queries
        if (str_starts_with($text, 'بحث ')) {
            $searchQuery = trim(mb_substr($text, 4));
            return $this->executeCommand('search', $chatId, $user, ['query' => $searchQuery]);
        }
        
        // Handle item transaction queries
        if (str_starts_with($text, '/item_')) {
            $parts = explode('_', $text);
            $warehouseId = $parts[1] ?? null;
            $itemId = $parts[2] ?? null;
            return $this->executeCommand('/warehouses', $chatId, $user, ['warehouse_id' => $warehouseId, 'item_id' => $itemId]);
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
    
    private function handleWarehouseCallback($chatId, $user, $callbackData)
    {
        $parts = explode('_', $callbackData);
        
        if (count($parts) < 2) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.unknown_command')
            ]);
        }
        
        $warehouseId = $parts[1] ?? null;
        $action = $parts[2] ?? null;
        
        $params = ['warehouse_id' => $warehouseId];
        
        if ($action === 'action') {
            $params['action'] = $parts[3] ?? null;
            $params['warehouse_id'] = $parts[4] ?? null;
            $params['item_id'] = $parts[5] ?? null;
            $params['transaction_type'] = $parts[6] ?? null;
            $params['stakeholder_id'] = $parts[7] ?? null;
        }
        
        return $this->executeCommand('/warehouses', $chatId, $user, $params);
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