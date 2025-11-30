<?php

namespace App\Services\TelegramHandlers;

use App\Models\TelegramConversation;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Item;
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
        
        // Handle search queries
        if (str_starts_with($text, 'بحث ')) {
            $searchQuery = trim(substr($text, 4)); // Remove 'بحث ' prefix
            return $this->searchItems($chatId, $searchQuery);
        }
        
        // Handle callback queries (warehouse selection)
        if (str_starts_with($text, 'warehouse_')) {
            if (!$this->canAccessWarehouses($user)) {
                return $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => __('telegram.unauthorized')
                ]);
            }
            $warehouseId = str_replace('warehouse_', '', $text);
            return $this->showWarehouseItems($chatId, $warehouseId);
        }
        
        // Handle authenticated user commands/messages
        return match(strtolower($text)) {
            '/profile' => $this->showProfile($chatId, $user),
            '/help' => $this->showHelp($chatId),
            '/warehouses' => $this->canAccessWarehouses($user) ? $this->showWarehouses($chatId) : $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.unauthorized')
            ]),
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
            if($item->item_quantity <= 0) continue;
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
    
    protected function searchItems($chatId, $query)
    {
        if (empty($query)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.no_search_results', ['query' => $query])
            ]);
        }
        
        $items = Item::where('name', 'LIKE', '%' . $query . '%')->get();
        
        if ($items->isEmpty()) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.no_search_results', ['query' => $query])
            ]);
        }
        
        $resultsText = '';
        foreach ($items as $item) {
            $warehousesText = '';
            foreach ($item->available_quantities as $warehouse) {
                $warehousesText .= __('telegram.warehouse_quantity', [
                    'warehouse' => $warehouse['warehouse_name'],
                    'quantity' => $warehouse['quantity']
                ]);
            }
            
            if (!empty($warehousesText)) {
                $resultsText .= __('telegram.item_search_format', [
                    'name' => $item->name,
                    'unit' => $item->unit_name,
                    'warehouses' => $warehousesText
                ]);
            }
        }
        
        if (empty($resultsText)) {
            return $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.no_search_results', ['query' => $query])
            ]);
        }
        
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.search_results', [
                'query' => $query,
                'results' => $resultsText
            ])
        ]);
    }
    
    protected function canAccessWarehouses(User $user)
    {
        return in_array($user->type, [1, 4]); // 1 = admin/proposal_director, 4 = warehouses_director
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