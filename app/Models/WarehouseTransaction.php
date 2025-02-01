<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class WarehouseTransaction extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;

    protected $appends = ['item_name', 'unit_name', 'transaction_type_str', 'warehouse_name'];
    protected $with = ['item', 'warehouse'];
    public static $controllable = true;
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'warehouse_transactions';

    // Optional: Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
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
    public function getWarehouseNameAttribute()
    {
        return $this->warehouse->name;
    }
    public function getTransactionTypeStrAttribute()
    {
        return [1 => __('Inbound Trnasaction'), 2 => __('Outbound Transaction'), 3 => __('Deprecated')][$this->transaction_type];
    }

    public static function transactionTypes() {
        return [
            ['id' => 1, 'name' => __('Inbound')],
            ['id' => 2, 'name' => __('Outbound')],
            ['id' => 3, 'name' => __('Deprecated')],
        ];
    }

    public function scopeSearch($query, $request)
    {
        $query->when($request->warehouse_id, function ($q, $warehouse_id) {
            if($warehouse_id == 0) return;
            $q->where('warehouse_id', $warehouse_id);
        });
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'warehouse name', 'key' => 'warehouse_name'],
            ['sortable' => true, 'value' => 'item name', 'key' => 'item_name'],
            ['sortable' => true, 'value' => 'unit name', 'key' => 'unit_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'transaction type', 'key' => 'transaction_type_str', 'class_value_name' => 'transaction_type', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
