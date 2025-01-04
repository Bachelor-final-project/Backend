<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends BaseModel
{
    use HasFactory;

    protected $appends = ['father_name', 'warehouse', 'warehouse_bio'];
    public static $controllable = true;


    public function getFatherNameAttribute()
    {
        return $this->father()->first()->name ?? '';
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function father()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getWarehouseAttribute() {
        return $this->warehouse()->first();
    }
    
    public function getWarehouseBioAttribute() {
        return $this->warehouse()->first()->bio ?? '';
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'national id', 'key' => 'national_id'],
            ['sortable' => true, 'value' => 'phone', 'key' => 'phone'],
            ['sortable' => true, 'value' => 'email', 'key' => 'email'],
            ['sortable' => true, 'value' => 'dob', 'key' => 'dob'],
            ['sortable' => true, 'value' => 'father name', 'key' => 'father_name'],
            ['sortable' => true, 'value' => 'warehouse bio', 'key' => 'warehouse_bio'],
        ];
    }
}
