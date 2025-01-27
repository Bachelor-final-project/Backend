<?php
namespace App\Traits;

use App\Scopes\TenantScope;
trait TenantScoped
{
    public static function bootTenantScoped()
    {
        static::addGlobalScope(new TenantScope);
    }
}