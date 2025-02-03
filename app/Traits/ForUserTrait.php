<?php
namespace App\Traits;

use App\Scopes\ForUserScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait ForUserTrait
{
    /**
     * Boot the trait and register a global scope.
     */
    protected static function bootForUserTrait()
    {
        static::addGlobalScope(new ForUserScope());
    }
}