<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends BaseModel
{
    use HasFactory;
    public static $controllable = true;
}
