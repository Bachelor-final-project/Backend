<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseTransaction;

class WarehouseCommand extends BaseCommand
{
    public function canExecute(User $user): bool
    {
        return in_array($user->type, [1, 4]);
    }
    
    public function handle($chatId, $user, $params = [])
    {
        if (isset($params['item_id']) && isset($params['warehouse_id'])) {
            return $this->showItemTransactions($chatId, $params['warehouse_id'], $params['item_id']);
        }
        
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
        $keyboard = [];
        foreach ($items as $item) {
            if($item->item_quantity <= 0) continue;
            $itemsText .= __('telegram.item_format', [
                'name' => $item->item_name,
                'unit' => $item->unit_name,
                'quantity' => $item->item_quantity,
                'status' => $item->item_status_str
            ]);
            
            $keyboard[] = [[
                'text' => $item->item_name . ' (' . $item->unit_name . ')',
                'callback_data' => 'item_' . $warehouseId . '_' . $item->item_id
            ]];
        }
        
        return $this->sendMessage($chatId, __('telegram.warehouse_items', [
            'name' => $warehouse->name,
            'items' => $itemsText
        ]), [
            'inline_keyboard' => $keyboard
        ]);
    }
    
    private function showItemTransactions($chatId, $warehouseId, $itemId)
    {
        $warehouse = Warehouse::find($warehouseId);
        $transactions = WarehouseTransaction::where('warehouse_id', $warehouseId)
            ->where('item_id', $itemId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        if ($transactions->isEmpty()) {
            return $this->sendMessage($chatId, __('telegram.no_transactions'));
        }
        
        $transactionsText = '';
        foreach ($transactions as $transaction) {
            $typeIcon = $transaction->transaction_type == 1 ? '📥' : '📤';
            $transactionsText .= __('telegram.transaction_format', [
                'type_icon' => $typeIcon,
                'type' => $transaction->transaction_type_str,
                'amount' => $transaction->amount,
                'unit' => $transaction->unit_name,
                'date' => $transaction->created_at->format('Y-m-d H:i'),
                'stakeholder' => $transaction->stakeholder_name ?: 'N/A'
            ]);
        }
        
        return $this->sendMessage($chatId, __('telegram.item_transactions', [
            'item' => $transactions->first()->item_name,
            'warehouse' => $warehouse->name,
            'transactions' => $transactionsText
        ]));
    }
}