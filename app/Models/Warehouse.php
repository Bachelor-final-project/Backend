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
            ['value' => 'quantity', 'key' => 'item_quantity'],
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

    public static function getWarehouseItems($warehouse_id) {
        $sql = "
            WITH warehouses_items AS (
                SELECT 
                    w.name AS warehouse_name, 
                    i.name AS item_name, 
                    u.name AS unit_name, 
                    SUM(
                        CASE 
                            WHEN wt.transaction_type = 1 THEN wt.amount 
                            ELSE -wt.amount 
                        END
                    ) AS item_quantity, 
                    w.id AS warehouse_id
                FROM 
                    warehouse_transactions wt
                LEFT JOIN 
                    items i ON wt.item_id = i.id
                LEFT JOIN 
                    units u ON i.unit_id = u.id
                LEFT JOIN 
                    warehouses w ON wt.warehouse_id = w.id
                GROUP BY 
                    wt.item_id, w.id, w.name
            )
            SELECT * 
            FROM warehouses_items
            WHERE item_quantity > 0
        ";

        // Add conditional WHERE clause for warehouse_id
        if ($warehouse_id > 0) {
            $sql .= " AND warehouse_id = ?";
            $bindings = [$warehouse_id];
        } else {
            $bindings = [];
        }
        $results = DB::select($sql, $bindings);
        return $results;
    }
}
