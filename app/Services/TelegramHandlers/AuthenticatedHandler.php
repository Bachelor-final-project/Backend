<?php

namespace App\Services\TelegramHandlers;

use App\Models\TelegramConversation;
use App\Models\User;
use App\Models\Warehouse;
use Telegram\Bot\Api;

class AuthenticatedHandler
{
    protected $telegram;
    
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
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
        
        // Handle callback queries (warehouse selection)
        if (str_starts_with($text, 'warehouse_')) {
            $warehouseId = str_replace('warehouse_', '', $text);
            return $this->showWarehouseItems($chatId, $warehouseId);
        }
        
        // Handle authenticated user commands/messages
        return match(strtolower($text)) {
            '/profile' => $this->showProfile($chatId, $user),
            '/help' => $this->showHelp($chatId),
            '/warehouses' => $this->showWarehouses($chatId),
            '/logout' => $this->logout($chatId, $user, $conversation),
            default => $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.hello_user', ['name' => $user->name])
            ])
        };
    }
    
    protected function showProfile($chatId, User $user)
    {
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.profile_info', [
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type_str,
                'status' => $user->status_str
            ])
        ]);
    }
    
    protected function showHelp($chatId)
    {
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.available_commands')
        ]);
    }
    
    protected function showWarehouses($chatId)
    {
        $warehouses = Warehouse::where('status', 1)->get();
        
        if ($warehouses->isEmpty()) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.no_warehouses')
            ]);
        }
        
        $keyboard = [];
        foreach ($warehouses as $warehouse) {
            $keyboard[] = [[
                'text' => $warehouse->name,
                'callback_data' => 'warehouse_' . $warehouse->id
            ]];
        }
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.warehouses_list'),
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard
            ])
        ]);
    }
    
    protected function showWarehouseItems($chatId, $warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        if (!$warehouse) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.no_warehouses')
            ]);
        }
        
        $items = Warehouse::getWarehouseItems($warehouseId);
        
        if (empty($items)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.no_items')
            ]);
        }
        
        $itemsText = '';
        foreach ($items as $item) {
            $itemsText .= __('telegram.item_format', [
                'name' => $item->item_name,
                'unit' => $item->unit_name,
                'quantity' => $item->item_quantity,
                'status' => $item->item_status_str
            ]);
        }
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.warehouse_items', [
                'name' => $warehouse->name,
                'items' => $itemsText
            ])
        ]);
    }
    
    protected function logout($chatId, User $user, TelegramConversation $conversation)
    {
        $user->update(['telegram_chat_id' => null]);
        $conversation->update(['state' => 'awaiting_email', 'data' => null]);
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.account_unlinked')
        ]);
    }
}