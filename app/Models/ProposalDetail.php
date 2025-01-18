<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalDetail extends BaseModel
{
    use HasFactory;
    protected $appends = ['unit', 'unit_name', 'total'];
    public static $controllable = true;

    public function getUnitAttribute()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getUnitNameAttribute()
    {
        return $this->unit->name;
    }

    public function getTotalAttribute()
    {
        return $this->value * $this->unit->estimated_price;
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'unit name', 'key' => 'unit_name'],
            ['sortable' => true, 'value' => 'quantity', 'key' => 'value'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'notes'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
    public static function addItemHeaders($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'item name', 'key' => 'item_id', 'type' => 'select', 'options' => Item::all()],
            ['sortable' => true, 'value' => 'value', 'key' => 'value', 'type' => 'number'],
            ['sortable' => true, 'value' => 'estimated_price', 'key' => 'estimated_price', 'type' => 'number'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'notes', 'type' => 'text'],
        ];
    }
}
