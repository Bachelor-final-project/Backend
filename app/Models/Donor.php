<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends BaseModel
{
    use HasFactory;
    protected $appends = ['gender_str', 'country_name'];
    protected $with = ['country'];
    public static $controllable = true;

    public const STATUSES = [
        'pending' => 0,
        'approved' => 2,
        'rejected' => 3,
        0 => ('Pending'),
        2 => ('Approved'),
        3 => ('Rejected'),

    ];

    public static function genders() {
        return [
            ['id' => 1, 'name' => __('Male')],
            ['id' => 2, 'name' => __('Female')],
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function getCountryNameAttribute()
    {
        return $this->country->name ?? null;
    }

    public function getGenderStrAttribute() {
        // dd($this);
        return [1 => __('Male'), 2 => __('Female'), 3 => ''][$this->gender ?? 3];
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'Donor Name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'Donor phone', 'key' => 'phone'],
            ['sortable' => true, 'value' => 'Gender', 'key' => 'gender_str'],
            ['sortable' => true, 'value' => 'Country', 'key' => 'country_name'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }

}