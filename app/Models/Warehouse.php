<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends BaseModel
{
    use HasFactory;

    // protected $appends = ['status_str'];
    protected $appends = ['warehouse_details', 'total', 'status_str'];
    public static $controllable = true;

    public function getStatusStrAttribute()
    {
        return [1 => 'Open', 2 => 'Under Repairing', 3 => 'Under Construction', 4 => 'Close'][$this->status] ?? '';
    }

    public function getWarehouseDetailsAttribute()
    {
        return $this->hasMany(WarehouseDetail::class, 'warehouse_id');
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
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
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
