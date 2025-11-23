<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;
use Illuminate\Support\Facades\DB;

class Warehouse extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;

    // protected $appends = ['status_str'];
    protected $appends = ['status_str'];
    public static $controllable = true;

    public function getStatusStrAttribute()
    {
        return [1 => 'Open', 2 => 'Under Repairing', 3 => 'Under Construction', 4 => 'Close'][$this->status] ?? '';
    }

    public function getWarehouseDetailsAttribute()
    {
        return $this->hasMany(WarehouseTransaction::class, 'warehouse_id');
    }

    public function getTotalAttribute()
    {
        return array_sum(array_column($this->getWarehouseDetailsAttribute->toArray(), 'total'));
    }
    

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'bio', 'key' => 'bio'],
            ['sortable' => true, 'value' => 'location', 'key' => 'location'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str', 'class_value_name' => 'status', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }

    public static function availableItemsHeaders() {
        return [
            ['value' => 'warehouse name', 'key' => 'warehouse_name'],
            ['value' => 'item name', 'key' => 'item_name'],
            ['value' => 'unit name', 'key' => 'unit_name'],
            ['sortable' => true, 'value' => 'status', 'key' => 'item_status_str', 'class_value_name' => 'item_status', 'has_class' => true],
            ['value' => 'quantity', 'key' => 'item_quantity'],
            // ['value' => 'actions', 'key' => 'actions'],
        ];
    }

    public static function statuses() {
        return [
            ['name' => 'Open', 'id' => '1'],
            ['name' => 'Under Repairing', 'id' => '2'],
            ['name' => 'Under Construction', 'id' => '3'],
            ['name' => 'Close', 'id' => '4'],
        ];
    }

    public static function getItemStatusStr($status) {
        return [4 => __('Out of Stock'), 2 => __('Low Stock'), 1 => __('In Stock')][$status] ?? '';
    }

    public static function getWarehouseItems($warehouse_id, $item_id = null) {
        $query = DB::table('warehouse_transactions as wt')
            ->select([
                'w.name as warehouse_name',
                'i.name as item_name',
                'i.id as item_id',
                'u.name as unit_name',
                'i.quantity_limit',
                DB::raw('SUM(CASE WHEN wt.transaction_type = 1 THEN wt.amount ELSE -wt.amount END) as item_quantity'),
                DB::raw('CASE 
                    WHEN SUM(CASE WHEN wt.transaction_type = 1 THEN wt.amount ELSE -wt.amount END) <= 0 THEN 4
                    WHEN i.quantity_limit IS NOT NULL AND SUM(CASE WHEN wt.transaction_type = 1 THEN wt.amount ELSE -wt.amount END) <= i.quantity_limit THEN 2
                    ELSE 1
                END as item_status'),
                'w.id as warehouse_id'
            ])
            ->leftJoin('items as i', 'wt.item_id', '=', 'i.id')
            ->leftJoin('units as u', 'i.unit_id', '=', 'u.id')
            ->leftJoin('warehouses as w', 'wt.warehouse_id', '=', 'w.id')
            ->groupBy('wt.item_id', 'w.id', 'w.name', 'i.quantity_limit');

        if ($warehouse_id > 0) {
            $query->where('w.id', '=', $warehouse_id);
        }
        if ($item_id > 0) {
            $query->where('i.id', '=', $item_id);
        }

        $results = $query->get();

        foreach ($results as $result) {
            $result->item_status_str = self::getItemStatusStr($result->item_status);
        }

        return $results;
    }
}
