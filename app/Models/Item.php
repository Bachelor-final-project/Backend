<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Item extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $appends = [ 'unit_name', 'available_quantities'];
    protected $with = ['unit'];
    public static $controllable = true;

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function getUnitNameAttribute()
    {
        return $this->unit->name ?? '';
    }

    public function getAvailableQuantitiesAttribute()
    {
        return WarehouseTransaction::selectRaw('warehouse_id, SUM(CASE WHEN transaction_type = 1 THEN amount ELSE -amount END) as quantity')
            ->where('item_id', $this->id)
            ->groupBy('warehouse_id')
            ->havingRaw('quantity > 0')
            ->with('warehouse:id,name')
            ->get()
            ->map(function($item) {
                return [
                    'warehouse_id' => $item->warehouse_id,
                    'warehouse_name' => $item->warehouse->name,
                    'quantity' => (int)$item->quantity
                ];
            });
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'price', 'key' => 'estimated_price'],
            ['sortable' => true, 'value' => 'description', 'key' => 'description'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
