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

    protected $appends = ['type_str', 'status_str'];
    public static $controllable = true;
    public const DEFAULTPASSWD = '123456';
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $guarded = [];

    public static $datatableHeaders = ['id', 'name', 'type', 'actions'];
    public function scopeSearch($query, $request)
    {
    }
    public function scopeSort($query, $request)
    {
    }
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

    public function getTypeStrAttribute()
    {
        return [1 => 'individual', 2 => 'organisation'][$this->type] ?? "";
    }

    public function getStatusStrAttribute()
    {
        return [1 => 'Open', 2 => 'Closed', 3 => 'Blocked'][$this->status];
    }
    public static function headers()
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'email', 'key' => 'email'],
            ['sortable' => true, 'value' => 'phone', 'key' => 'phone'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str'],
            ['sortable' => true, 'value' => 'type', 'key' => 'type_str'],
            ['sortable' => true, 'value' => 'job title', 'key' => 'job_title'],
            ['sortable' => true, 'value' => 'is active', 'key' => 'is_active'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
