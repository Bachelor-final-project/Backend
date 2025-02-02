<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasFactory;

    public static $controllable = true;

    protected $guarded = [];
    protected $hidden = [
        'updated_at'
    ];
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    public function scopeSearch($query, $request)
    {
         // Filter the parameters ending with "_filter" and transform their keys
         $filterColumns = collect($request->all())
         ->filter(function ($value, $key) {
             return str_ends_with($key, '_filter');
         })
         ->mapWithKeys(function ($value, $key) {
             // Remove "_filter" from the key
             $newKey = Str::replaceLast('_filter', '', $key);
             return [$newKey => $value];
         })
         ->toArray();
         
         foreach ($filterColumns as $column => $value) {
             if(empty($value) ||  $value == 0) continue;
 
             if(is_numeric($value)) $query->where($column, $value);
             
             else if(is_string($value)) $query->where($column,'like', "%$value%");
         }
    }
    public function scopeSort($query, $request)
    {
    }

    public function files()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }


}
