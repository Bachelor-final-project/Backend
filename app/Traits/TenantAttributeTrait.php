<?php
namespace App\Traits;

trait TenantAttributeTrait
{
    public static function bootTenantAttributeTrait()
    {
        static::creating(function ($model) {
            $model->tenant_id = auth()->user()->tenant_id;
        });
        // static::retrieved(function ($model) {
        //     $model->where('tenant_id', auth()->user()->tenant_id);
        // });
    }
}