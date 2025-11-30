<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, TenantAttributeTrait;

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

    public const STATUSES = [
        "open" => 1,
        "closed" => 2,
        "blocked" => 3
    ];

    public const TYPES = [
        1 => 'proposal_director',
        2 => 'entity_director',
        3 => 'donations_director',
        4 => 'warehouses_director',
        5 => 'media_director',
    ];

    protected $fillable = [
        'name', 'email', 'password', 'telegram_chat_id', 'type', 'status', 'is_active',
        'phone', 'job_title', 'country_id'
    ];

    public static $datatableHeaders = ['id', 'name', 'type', 'actions'];

    public function country(){
        return $this->belongsTo(Country::class);
    }
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
        return self::TYPES[$this->type] ?? "";
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
            ['sortable' => true, 'value' => 'type', 'key' => 'type_str'],
            ['sortable' => true, 'value' => 'job title', 'key' => 'job_title'],
            ['sortable' => true, 'value' => 'active', 'key' => 'is_active'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str', 'class_value_name' => 'status', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
    public static function types()
    {
        return [
            ['name' => __('Proposals Director'), 'id' => '1'],
            ['name' => __('Entity Director'), 'id' => '2'],
            ['name' => __('Donations Director'), 'id' => '3'],
            ['name' => __('Warehouses Director'), 'id' => '4'],
            ['name' => __('Media Director'), 'id' => '5'],
        ];
    }
    public static function statuses()
    {
        return [
            ['name' => __('Open'), 'id' => '1'],
            ['name' => __('Closed'), 'id' => '2'],
            ['name' => __('Blocked'), 'id' => '3'],
        ];
    }
}
