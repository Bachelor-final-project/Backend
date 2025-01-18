<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
    use HasFactory;
    protected $appends = [];
    public static $controllable = true;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'countries';

    // Optional: Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Optional: Set timestamps to false if the table doesn't have created_at and updated_at
    public $timestamps = false;

}
