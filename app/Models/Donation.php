<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Donation extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $appends = [ 'donor_name','currency_name', 'status_str', 'donor_phone', 'proposal_title'];
    public static $controllable = true;

    public const STATUSES = [
        'pending' => 0,
        'approved' => 2,
        'rejected' => 3,
        0 => ('Pending'),
        2 => ('Approved'),
        3 => ('Rejected'),

    ];
    public function proposal(){
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
    public function donor() {
        return $this->belongsTo(Donor::class, 'donor_id');
    }
    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function getDonorNameAttribute() {
        return $this->donor()->first()->name ?? '';
    }
    public function getCurrencyNameAttribute() {
        return $this->currency()->first()->name ?? '';
    }
    public function getDonorPhoneAttribute() {
        return $this->donor()->first()->phone ?? '';
    }

    public function getStatusStrAttribute() {
        return self::STATUSES[$this->status] ?? '';
    }
    public function getProposalTitleAttribute()
    {
        return $this->proposal->title ?? null;
    }
    public static function statuses() {
        return [
            ['id' => 0, 'name' => __('Pending')],
            ['id' => 2, 'name' => __('Approved')],
            ['id' => 3, 'name' => __('Rejected')],
        ];
    } 

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'Proposal Title', 'key' => 'proposal_title'],
            ['sortable' => true, 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'donor phone', 'key' => 'donor_phone'],
            ['sortable' => true, 'value' => 'currency name', 'key' => 'currency_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str', 'class_value_name' => 'status', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
