<?php

namespace App\Models;

use App\Traits\ForUserTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Donor extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped, ForUserTrait;
    protected $appends = ['gender_str', 'country_name'];
    protected $guarded = ['donations'];
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
    public const PHONE_ALLOWED_USER_TYPES = [
       1, 2
    ];
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => deterministicEncrypt($value),
            get: function (string $value) {
                if(auth()->user() && in_array(auth()->user()->type, self::PHONE_ALLOWED_USER_TYPES))
                    return deterministicDecrypt($value);
                return str_repeat("●", 15);
            },
        );
    }

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
    public function proposals()
    {
        return $this->hasManyThrough(
            Proposal::class,     // Final related model
            Donation::class,     // Intermediate model (pivot)
            'donor_id',          // Foreign key on donations (to donors)
            'id',                // Primary key on proposals
            'id',                // Primary key on donors
            'proposal_id'        // Foreign key on donations (to proposals)
        );
    }


    
    // public function getPhoneAttribute($phone)
    // {
    //     if(in_array(auth()->user()->type, self::PHONE_ALLOWED_USER_TYPES))
    //          return decrypt($phone, false);
    //     return str_repeat("●", 15);
    // }
    public function getCountryNameAttribute()
    {
        return $this->country->name ?? null;
    }

    public function getGenderStrAttribute() {
        // dd($this);
        return [1 => __('Male'), 2 => __('Female'), 3 => ''][$this->gender ?? 3];
    }

    //scopes
    public function scopeForUser($query, $user)
    {
        switch ($user->type) {
            case 1: // proposal_director
            case 3: // donations_director
            case 4: // warehouses_director
            case 5: // media_director
                // access to all records
                break;
                
            case 2: // entity_director
                $query->has('proposals');
                break;
            default:
                $query->whereRaw('1=0');
                break;
        }
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
