<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

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
}
