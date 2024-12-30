<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends BaseModel
{
    use HasFactory;

    protected $appends = ['donor_name', 'donor', 'status_str', 'beneficiaries', 'propsal_details', 'total', 'headers'];
    public static $controllable = true;

    public function getHeadersAttribute()
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'national id', 'key' => 'national_id'],
            ['sortable' => true, 'value' => 'phone', 'key' => 'phone'],
            ['sortable' => true, 'value' => 'email', 'key' => 'email'],
            ['sortable' => true, 'value' => 'father name', 'key' => 'father_name'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }

    public function getDonorNameAttribute()
    {
        return $this->donor?->name;
    }

    public function getDonorAttribute()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function getStatusStrAttribute()
    {
        return [1 => 'accepted', 2 => 'unaccepted', 3 => 'pending', 4 => 'preparing', 8 => 'done'][$this->status] ?? '';
    }

    public function getBeneficiariesAttribute()
    {
        return $this->belongsToMany(Beneficiary::class, 'proposal_beneficiaries', 'proposal_id', 'beneficiary_id');
    }

    public function getPropsalDetailsAttribute()
    {
        return $this->hasMany(ProposalDetail::class, 'proposal_id');
    }

    public function getTotalAttribute()
    {
        return $this->propsal_details->sum('total');
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'title', 'key' => 'title'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'notes'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
