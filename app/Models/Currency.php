<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Currency extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $appends = [ ''];
    public static $controllable = true;

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'code', 'key' => 'code'],
            // ['sortable' => true, 'value' => 'price', 'key' => 'estimated_price'],
            // ['sortable' => true, 'value' => 'description', 'key' => 'description'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
