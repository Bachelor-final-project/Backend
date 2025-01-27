<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // dd(auth()->guard('web')->user());
        // dd(auth()->user());
        if(auth()->user()){
            $builder->where('tenant_id', auth()->user()->tenant_id);
        }
    }
}