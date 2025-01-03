<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends BaseModel
{
    use HasFactory;

    // protected $appends = ['status_str'];
    protected $appends = ['warehouse_details', 'total'];
    public static $controllable = true;

    public function getStatusStrAttribute()
    {
        return [1 => 'accepted', 2 => 'unaccepted', 3 => 'pending', 4 => 'preparing', 8 => 'done'][$this->status] ?? '';
    }

    public function getWarehouseDetailsAttribute()
    {
        return $this->hasMany(WarehouseDetail::class, 'warehouse_id');
    }

    public function getTotalAttribute()
    {
        return array_sum(array_column($this->warehouse_details, 'total'));
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'bio', 'key' => 'bio'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status'],
            ['sortable' => true, 'value' => 'location', 'key' => 'location'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
