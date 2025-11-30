<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;
use App\Models\Warehouse;

class WarehouseCommand extends BaseCommand
{
    public function canExecute(User $user): bool
    {
        return in_array($user->type, [1, 4]);
    }
    
    public function handle($chatId, $user, $params = [])
    {
        if (isset($params['warehouse_id'])) {
            return $this->showWarehouseItems($chatId, $params['warehouse_id']);
        }
        
        return $this->showWarehouses($chatId);
    }
    
    private function showWarehouses($chatId)
    {
        $warehouses = Warehouse::where('status', 1)->get();
        
        if ($warehouses->isEmpty()) {
            return $this->sendMessage($chatId, __('telegram.no_warehouses'));
        }
        
        $keyboard = [];
        foreach ($warehouses as $warehouse) {
            $keyboard[] = [[
                'text' => $warehouse->name,
                'callback_data' => 'warehouse_' . $warehouse->id
            ]];
        }
        
        return $this->sendMessage($chatId, __('telegram.warehouses_list'), [
            'inline_keyboard' => $keyboard
        ]);
    }
    
    private function showWarehouseItems($chatId, $warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        if (!$warehouse) {
            return $this->sendMessage($chatId, __('telegram.no_warehouses'));
        }
        
        $items = Warehouse::getWarehouseItems($warehouseId);
        
        if (empty($items)) {
            return $this->sendMessage($chatId, __('telegram.no_items'));
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
        
        return $this->sendMessage($chatId, __('telegram.warehouse_items', [
            'name' => $warehouse->name,
            'items' => $itemsText
        ]));
    }
}