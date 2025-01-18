<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class Proposal extends BaseModel
{
    use HasFactory;

    protected $appends = ['status_str', 'beneficiaries', 'currency_name', 'entity_name', 'proposal_type_type_ar', 'area_name'];
    protected $with = ['entity', 'area', 'proposalType', 'currency'];
    public static $controllable = true;

    public const STATUSES = [
        'donatable' => 1,
        'darft' => 9,
        'completed' => 2,
        'processing' => 8,
    ];

    // public function getDonorNameAttribute()
    // {
    //     return $this->donor?->name;
    // }

    // public function getDonorAttribute()
    // {
    //     return $this->belongsTo(User::class, 'donor_id');
    // }

    public function getStatusStrAttribute()
    {
        return [1 => 'accepted', 2 => 'unaccepted', 3 => 'pending', 4 => 'preparing', 8 => 'done'][$this->status] ?? '';
    }

    public function getBeneficiariesAttribute()
    {
        return $this->belongsToMany(Beneficiary::class, 'proposal_beneficiaries', 'proposal_id', 'beneficiary_id');
    }
    public function getEntityNameAttribute(){
        return $this->entity->name;   
    }
    public function getAreaNameAttribute(){
        return $this->area->name;   
    }
    public function getProposalTypeTypeArAttribute(){
        return $this->proposalType->type_ar;   
    }
    public function getCurrencyNameAttribute(){
        return $this->currency->name;   
    }

    // public function getPropsalDetailsAttribute()
    // {
    //     return $this->hasMany(ProposalDetail::class, 'proposal_id');
    // }

    // public function getTotalAttribute()
    // {
    //     return $this->propsal_details->sum('total');
    // }

    // relations
    public function entity(){
        return $this->belongsTo(Entity::class, 'entity_id');
    }
    public function area(){
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function proposalType(){
        return $this->belongsTo(ProposalType::class, 'proposal_type_id');
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'title', 'key' => 'title'],
            ['sortable' => true, 'value' => 'body', 'key' => 'body'],
            // ['sortable' => true, 'value' => 'notes', 'key' => 'notes'],
            ['sortable' => true, 'value' => 'currency name', 'key' => 'currency_name'],
            // ['sortable' => true, 'value' => 'proposal_effects', 'key' => 'proposal_effects'],
            ['sortable' => true, 'value' => 'cost', 'key' => 'cost'],
            ['sortable' => true, 'value' => 'share cost', 'key' => 'share_cost'],
            ['sortable' => true, 'value' => 'expected benificiaries count', 'key' => 'expected_benificiaries_count'],
            ['sortable' => true, 'value' => 'execution date', 'key' => 'execution_date'],
            // ['sortable' => true, 'value' => 'publishing date', 'key' => 'publishing_date'],
            ['sortable' => true, 'value' => 'entity name', 'key' => 'entity_name'],
            ['sortable' => true, 'value' => 'proposal type', 'key' => 'proposal_type_type_ar'],
            ['sortable' => true, 'value' => 'area name', 'key' => 'area_name'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }

    // headers for guest proposals table
    public static function guestHeaders($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'title', 'key' => 'title'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'notes'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
    public static function statuses() {
        return [
            ['name' => 'accepted', 'id' => '1'],
            ['name' => 'unaccepted', 'id' => '2'],
            ['name' => 'pending', 'id' => '3'],
            ['name' => 'preparing', 'id' => '4'],
            ['name' => 'done', 'id' => '8'],
        ];
    }
}
