<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalDetail extends BaseModel
{
    use HasFactory;
    protected $appends = ['unit', 'unit_name', 'total', 'headers'];
    public static $controllable = true;

    public function getHeadersAttribute()
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'national id', 'key' => 'national_id'],
            ['sortable' => true, 'value' => 'phone', 'key' => 'phone'],
            ['sortable' => true, 'value' => 'email', 'key' => 'email'],
            ['sortable' => true, 'value' => 'father name', 'key' => 'father_name'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
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
}
