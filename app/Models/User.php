<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Route;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;
    public static $controllable = true;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guarded = [];

    public function is_permitted_to($name, $class_name, $request)
    {
        $permitted = true;
        if (!$this->current_role)
            return true;
        if (isset($class_name::$controllable)) {
            if (isset($class_name::$field_to_check)) {
                $model = null;
                $field = $class_name::$field_to_check;
                $params = Route::current()->parameters();
                $model = reset($params);
                if ($request->id) {
                    $model = $class_name::find($request->id);
                }
                if (!$model) {
                    if (!$request->$field)
                        return false;
                    $p_name = $class_name::getSubName($request->$field);
                } else {
                    $p_name = $class_name::getSubName($model->$field);
                }
            } else {
                return true;
            }

            $permission = $this->current_role->permissions()->where('code', '=', $p_name)->first();
            if (!$permission)
                return true;
            $p = $name;
            return $permission->$p;
        }
        return true;
    }

}
