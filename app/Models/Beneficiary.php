<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiary extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped, SoftDeletes;

    protected $appends = ['father_name', /*'warehouse', 'warehouse_name'*/];
    public static $controllable = true;


    public function getFatherNameAttribute()
    {
        return $this->father()->first()->name ?? '';
    }

    // public function warehouse()
    // {
    //     return $this->belongsTo(Warehouse::class);
    // }
    public function father()
    {
        return $this->belongsTo(Beneficiary::class, 'father_national_id', 'national_id');
    }

    // public function getWarehouseAttribute() {
    //     return $this->warehouse()->first();
    // }
    
    // public function getWarehouseNameAttribute() {
    //     return $this->warehouse()->first()->name ?? '';
    // }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'national id', 'key' => 'national_id'],
            ['sortable' => true, 'value' => 'phone', 'key' => 'phone'],
            ['sortable' => true, 'value' => 'email', 'key' => 'email'],
            ['sortable' => true, 'value' => 'date of birth', 'key' => 'dob'],
            ['sortable' => true, 'value' => 'father name', 'key' => 'father_name'],
            // ['sortable' => true, 'value' => 'warehouse name', 'key' => 'warehouse_name'],
        ];
    }


    public static function isValidFatherNationalId($nationalId, $fatherNationalId) {
        // $cycleExists = DB::select(
        //     "
        //     WITH RECURSIVE ancestry AS (
        //         SELECT 
        //             national_id, 
        //             father_national_id, 
        //             CAST(national_id AS CHAR(200)) AS path, 
        //             1 AS depth
        //         FROM beneficiaries
        //         WHERE national_id = ?

        //         UNION ALL

        //         SELECT 
        //             u.national_id, 
        //             u.father_national_id, 
        //             CONCAT(a.path, ',', u.national_id) AS path,
        //             a.depth + 1 AS depth
        //         FROM beneficiaries u
        //         INNER JOIN ancestry a ON u.national_id = a.father_national_id
        //         WHERE FIND_IN_SET(u.national_id, a.path) = 0
        //     )
        //     SELECT COUNT(*) AS is_invalid
        //     FROM ancestry
        //     WHERE father_national_id = ?
        //     ",
        //     [$fatherNationalId, $fatherNationalId]
        // );

        // return $cycleExists[0]->is_invalid == 0; // Valid if no cycle exists
        
        $childrenAry = [$nationalId, $fatherNationalId];
        $ben = self::where('national_id', '=', $fatherNationalId)->first();
        while($ben) {
            if(in_array($ben->father_national_id, $childrenAry)) {
                return false;
            }
            $childrenAry[] = $ben->father_national_id;
            $ben = self::where('national_id', '=', $ben->father_national_id)->first();
        }
        return true;
    }

}
