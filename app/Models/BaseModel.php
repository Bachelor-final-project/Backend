<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    }
    public function scopeSort($query, $request)
    {
    }

    public function files()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
