<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseDetail extends BaseModel
{
    use HasFactory;

    protected $appends = ['item', 'total', 'item_name', 'unit_name'];
    public static $controllable = true;

    public function getItemAttribute()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function getTotalAttribute()
    {
        return $this->amount * $this->item->estimated_price;
    }

    public function getUnitNameAttribute()
    {
        return $this->item->unit->name;
    }
    public function getItemNameAttribute()
    {
        return $this->item->name;
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'item name', 'key' => 'item_name'],
            ['sortable' => true, 'value' => 'unit name', 'key' => 'unit_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
