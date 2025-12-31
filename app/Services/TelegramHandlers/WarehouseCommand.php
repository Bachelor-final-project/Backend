<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseTransaction;
use App\Models\WarehouseStakeholder;
use App\Enums\StakeholderType;

class WarehouseCommand extends BaseCommand
{
    public function canExecute(User $user): bool
    {
        return in_array($user->type, [1, 4]);
    }
    
    public function handle($chatId, $user, $params = [])
    {
        if (isset($params['action'])) {
            switch ($params['action']) {
                case 'add_transaction':
                    return $this->showTransactionTypes($chatId, $params['warehouse_id'], $params['item_id']);
                case 'select_stakeholder':
                    return $this->showStakeholders($chatId, $params['warehouse_id'], $params['item_id'], $params['transaction_type']);
                case 'enter_amount':
                    return $this->requestAmount($chatId, $params['warehouse_id'], $params['item_id'], $params['transaction_type'], $params['stakeholder_id']);
                case 'store_transaction':
                    return $this->storeTransaction($chatId, $user, $params);
            }
        }
        
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
        foreach ($items as $item) {
            if($item->item_quantity <= 0) continue;
            $botUsername = config('telegram.bot_username', 'GazaReliefGeneral');
            $itemLink = "<a href=\"https://t.me/{$botUsername}?start=item_{$warehouseId}_{$item->item_id}\">{$item->item_name}</a>";
            
            $itemsText .= __('telegram.item_format', [
                'name' => $itemLink,
                'unit' => $item->unit_name,
                'quantity' => $item->item_quantity,
                'status' => $item->item_status_str
            ]);
        }
        
        $params = [
            'chat_id' => $chatId,
            'text' => __('telegram.warehouse_items', [
                'name' => $warehouse->name,
                'items' => $itemsText
            ]),
            'parse_mode' => 'HTML'
        ];
        
        return $this->telegram->sendMessage($params);
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
        
        $keyboard = [
            [[
                'text' => __('telegram.add_income'),
                'callback_data' => 'warehouse_action_add_transaction_' . $warehouseId . '_' . $itemId . '_1'
            ]],
            [[
                'text' => __('telegram.add_outcome'),
                'callback_data' => 'warehouse_action_add_transaction_' . $warehouseId . '_' . $itemId . '_2'
            ]]
        ];
        
        return $this->sendMessage($chatId, __('telegram.item_transactions', [
            'item' => $transactions->first()->item_name,
            'warehouse' => $warehouse->name,
            'transactions' => $transactionsText
        ]), ['inline_keyboard' => $keyboard]);
    }
    
    private function showTransactionTypes($chatId, $warehouseId, $itemId)
    {
        $keyboard = [
            [[
                'text' => __('telegram.add_income'),
                'callback_data' => 'warehouse_action_select_stakeholder_' . $warehouseId . '_' . $itemId . '_1'
            ]],
            [[
                'text' => __('telegram.add_outcome'),
                'callback_data' => 'warehouse_action_select_stakeholder_' . $warehouseId . '_' . $itemId . '_2'
            ]]
        ];
        
        return $this->sendMessage($chatId, __('telegram.select_transaction_type'), [
            'inline_keyboard' => $keyboard
        ]);
    }
    
    private function showStakeholders($chatId, $warehouseId, $itemId, $transactionType)
    {
        $stakeholderType = $transactionType == 1 ? StakeholderType::INBOUND : StakeholderType::OUTBOUND;
        $stakeholders = WarehouseStakeholder::where('type', $stakeholderType)->get();
        
        if ($stakeholders->isEmpty()) {
            return $this->sendMessage($chatId, __('telegram.no_stakeholders'));
        }
        
        $keyboard = [];
        foreach ($stakeholders as $stakeholder) {
            $keyboard[] = [[
                'text' => $stakeholder->name,
                'callback_data' => 'warehouse_action_enter_amount_' . $warehouseId . '_' . $itemId . '_' . $transactionType . '_' . $stakeholder->id
            ]];
        }
        
        return $this->sendMessage($chatId, __('telegram.select_stakeholder'), [
            'inline_keyboard' => $keyboard
        ]);
    }
    
    private function requestAmount($chatId, $warehouseId, $itemId, $transactionType, $stakeholderId)
    {
        $item = Warehouse::getWarehouseItems($warehouseId, $itemId)->first();
        
        if (!$item) {
            return $this->sendMessage($chatId, __('telegram.item_not_found'));
        }
        
        $message = __('telegram.enter_amount');
        if ($transactionType == 2) {
            $message .= "\n" . __('telegram.available_quantity', ['quantity' => $item->item_quantity, 'unit' => $item->unit_name]);
            $message .= "\n" . __('telegram.enter_recipient_optional');
        }
        
        cache()->put('transaction_context_' . $chatId, [
            'warehouse_id' => $warehouseId,
            'item_id' => $itemId,
            'transaction_type' => $transactionType,
            'stakeholder_id' => $stakeholderId,
            'max_quantity' => $item->item_quantity
        ], 300);
        
        return $this->sendMessage($chatId, $message);
    }
    
    private function storeTransaction($chatId, $user, $params)
    {
        $context = cache()->get('transaction_context_' . $chatId);
        
        if (!$context) {
            return $this->sendMessage($chatId, __('telegram.session_expired'));
        }
        
        $amount = floatval($params['amount']);
        
        if ($amount <= 0) {
            return $this->sendMessage($chatId, __('telegram.invalid_amount'));
        }
        
        if ($context['transaction_type'] == 2 && $amount > $context['max_quantity']) {
            return $this->sendMessage($chatId, __('telegram.insufficient_quantity'));
        }
        
        $transaction = new WarehouseTransaction();
        $transaction->warehouse_id = $context['warehouse_id'];
        $transaction->item_id = $context['item_id'];
        $transaction->transaction_type = $context['transaction_type'];
        $transaction->amount = $amount;
        $transaction->warehouse_stakeholder_id = $context['stakeholder_id'];
        $transaction->recipient_name = $params['recipient_name'] ?? null;
        $transaction->user_id = $user->id;
        $transaction->save();
        
        cache()->forget('transaction_context_' . $chatId);
        
        return $this->sendMessage($chatId, __('telegram.transaction_saved'));
    }
}