<?php

namespace App\Services\TelegramHandlers;

use App\Models\User;
use App\Models\Item;

class SearchCommand extends BaseCommand
{
    public function handle($chatId, $user, $params = [])
    {
        $query = $params['query'] ?? '';
        
        if (empty($query)) {
            return $this->sendMessage($chatId, __('telegram.no_search_results', ['query' => $query]));
        }
        
        $items = Item::where('name', 'LIKE', '%' . $query . '%')->get();
        
        if ($items->isEmpty()) {
            return $this->sendMessage($chatId, __('telegram.no_search_results', ['query' => $query]));
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
            return $this->sendMessage($chatId, __('telegram.no_search_results', ['query' => $query]));
        }
        
        return $this->sendMessage($chatId, __('telegram.search_results', [
            'query' => $query,
            'results' => $resultsText
        ]));
    }
}