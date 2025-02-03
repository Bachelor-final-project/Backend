<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ForUserScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (method_exists($model, 'scopeForUser') ) {
            if(auth()->user())
                $builder->forUser(Auth::user());
            
        } else {
            throw new \Exception(class_basename($model) . ' must implement scopeForUser.');
        }
    }
}