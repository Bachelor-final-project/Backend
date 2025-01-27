<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;
use Illuminate\Support\Facades\App;

class Currency extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $appends = ['name'];
    public static $controllable = true;

    public function getNameAttribute(){
        return App::currentLocale() == 'en'? $this->name_en: $this->name_ar;
    }
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
