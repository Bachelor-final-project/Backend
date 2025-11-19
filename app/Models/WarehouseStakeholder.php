<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;
use App\Enums\StakeholderType;

class WarehouseStakeholder extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;

    public static $controllable = true;

    protected $casts = [
        'type' => StakeholderType::class,
    ];
    protected $appends = ['type_str'];
    public function getTypeStrAttribute(){
        return $this->type->label();
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'national_id', 'key' => 'national_id'],
            ['sortable' => true, 'value' => 'phone', 'key' => 'phone'],
            ['sortable' => true, 'value' => 'type', 'key' => 'type_str', 'class_value_name' => 'warehouse_stakeholder_type', 'has_class' => true],

        ];
    }
}