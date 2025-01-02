<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $appends = [ 'unit_name'];
    public static $controllable = true;

    public function getUnitAttribute()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

   

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'price', 'key' => 'estimated_price'],
            ['sortable' => true, 'value' => 'description', 'key' => 'description'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
