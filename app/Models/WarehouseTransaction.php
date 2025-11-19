<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class WarehouseTransaction extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;

    protected $fillable = ['warehouse_id', 'item_id', 'amount', 'transaction_type', 'warehouse_stakeholder_id'];
    protected $appends = ['item_name', 'unit_name', 'transaction_type_str', 'warehouse_name', 'stakeholder_name'];
    protected $with = ['item', 'warehouse', 'warehouseStakeholder'];
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

    public function warehouseStakeholder()
    {
        return $this->belongsTo(WarehouseStakeholder::class, 'warehouse_stakeholder_id');
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

    public function getStakeholderNameAttribute()
    {
        return $this->warehouseStakeholder?->name;
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
        })->when($request->item_id, function ($q, $item_id) {
            if($item_id == 0) return;
            $q->where('item_id', $item_id);
        });
    }

    public static function getAllTransactionsLast30DaysChartData()
    {
        $transactions = self::selectRaw('COUNT(*) as count, date(created_at) as date')
            ->where('created_at', '>', now()->subDays(30)->endOfDay())
            ->groupByRaw('date(created_at)')
            ->get();

        return [
            "data" => $transactions->pluck('count')->toArray()
        ];
    }

    public static function getInboundTransactionsLast30DaysChartData()
    {
        $transactions = self::selectRaw('COUNT(*) as count, date(created_at) as date')
            ->where('created_at', '>', now()->subDays(30)->endOfDay())
            ->where('transaction_type', 1)
            ->groupByRaw('date(created_at)')
            ->get();

        return [
            "data" => $transactions->pluck('count')->toArray()
        ];
    }

    public static function getOutboundTransactionsLast30DaysChartData()
    {
        $transactions = self::selectRaw('COUNT(*) as count, date(created_at) as date')
            ->where('created_at', '>', now()->subDays(30)->endOfDay())
            ->where('transaction_type', 2)
            ->groupByRaw('date(created_at)')
            ->get();

        return [
            "data" => $transactions->pluck('count')->toArray()
        ];
    }

    public static function getTransactionsByTypeChartData()
    {
        $transactions = self::selectRaw('transaction_type, COUNT(*) as count')
            ->groupBy('transaction_type')
            ->get()
            ->keyBy('transaction_type');
            
        $typeMapping = [
            1 => __('Inbound'),
            2 => __('Outbound'),
            3 => __('Deprecated'),
        ];
        
        return [
            "categories" => array_values($typeMapping),
            "data" => array_map(function ($typeId) use ($transactions) {
                return $transactions[$typeId]->count ?? 0;
            }, array_keys($typeMapping))
        ];
    }

    public static function getTransactionsByWarehouseChartData()
    {
        $transactions = self::with('warehouse')
            ->selectRaw('warehouse_id, COUNT(*) as count')
            ->groupBy('warehouse_id')
            ->get();
            
        return [
            "categories" => $transactions->pluck('warehouse.name')->toArray(),
            "data" => $transactions->pluck('count')->toArray()
        ];
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'warehouse name', 'key' => 'warehouse_name'],
            ['sortable' => true, 'value' => 'item name', 'key' => 'item_name'],
            ['sortable' => true, 'value' => 'unit name', 'key' => 'unit_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'transaction type', 'key' => 'transaction_type_str', 'class_value_name' => 'transaction_type', 'has_class' => true],
            ['sortable' => true, 'value' => 'stakeholder', 'key' => 'stakeholder_name'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
