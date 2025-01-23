<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Document extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $appends = ['proposal_name', 'donor_name', 'currency_name'];
    protected $with = ['proposal', 'donor', 'currency'];
    public static $controllable = true;

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getProposalNameAttribute()
    {
        return $this->proposal?->title;
    }

    public function getDonorNameAttribute()
    {
        return $this->donor?->name;
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency?->name;
    }
    

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'proposal', 'key' => 'proposal_name'],
            ['sortable' => true, 'value' => 'donor', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'currency', 'key' => 'currency_name'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'notes'],
            ['sortable' => true, 'value' => 'expected date', 'key' => 'expected_date'],
            // ['sortable' => true, 'value' => 'currency', 'key' => 'status_str', 'class_value_name' => 'status', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }

    public static function statuses() {
        return [
            ['name' => 'Open', 'id' => '1'],
            ['name' => 'Under Repairing', 'id' => '2'],
            ['name' => 'Under Construction', 'id' => '3'],
            ['name' => 'Close', 'id' => '4'],
        ];
    }

}
