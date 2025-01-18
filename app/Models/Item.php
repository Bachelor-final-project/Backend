<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends BaseModel
{
    use HasFactory;
    protected $appends = [ 'unit_name'];
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
