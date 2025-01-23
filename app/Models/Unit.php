<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Unit extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    public static $controllable = true;

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'description', 'key' => 'description'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
