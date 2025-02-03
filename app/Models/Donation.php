<?php

namespace App\Models;

use App\Traits\ForUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Donation extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped, ForUserTrait;
    protected $appends = [ 'donor_name','currency_name', 'status_str', 'donor_phone', 'proposal_title', 'created_at_date_time'];
    protected $with = ['donor'];
    public static $controllable = true;

    public const STATUSES = [
        'pending' => 0,
        'approved' => 2,
        'rejected' => 3,
        0 => ('Pending'),
        2 => ('Approved'),
        3 => ('Rejected'),

    ];
    public static function getDonationsByStatuesChartData(){
        $donations = Donation::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');
            $statusMapping = [
                0 => __('Pending'),
                2 => __('Approved'),
                3 => __('Rejected'),
            ];
            $result = [
                "categories" => array_values($statusMapping),
                "data" => array_map(function ($statusId) use ($donations) {
                    return $donations[$statusId]->count ?? 0; // Default to 0 if status is missing
                }, array_keys($statusMapping))
            ];

        return $result;
    }
    public static function getApprovedDonationLast30DaysChartData(){
        $donations = Donation::selectRaw('status, COUNT(*) as count, date(created_at) as date')
        ->where('created_at', '>', now()->subDays(30)->endOfDay())
        ->where('status', 2)
        ->groupByRaw('status, date(created_at)')
        ->get();

            $result = [
                "data" => $donations->pluck('count')->toArray()
            ];

        return $result;
    }
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
        return $this->donor->phone ?? '';
    }

    public function getStatusStrAttribute() {
        return self::STATUSES[$this->status] ?? '';
    }
    public function getProposalTitleAttribute()
    {
        return $this->proposal->title ?? null;
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
                $query->has('proposal');
                break;
            default:
                $query->whereRaw('1=0');
                break;
        }
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
            ['sortable' => true, 'value' => 'id', 'key' => 'id'],
            // ['sortable' => true, 'value' => 'Proposal id', 'key' => 'proposal_id'],
            ['sortable' => true, 'value' => 'Proposal Title', 'key' => 'proposal_title'],
            ['sortable' => true, 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'donor phone', 'key' => 'donor_phone'],
            ['sortable' => true, 'value' => 'currency name', 'key' => 'currency_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'sortBy' => 'created_at', 'value' => 'created at', 'key' => 'created_at_date_time'],
            ['sortable' => true, 'sortBy' => 'status', 'value' => 'status', 'key' => 'status_str', 'class_value_name' => 'status', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
    public static function headersForProposal($user = null)
    {
        return [
            // ['sortable' => true, 'value' => 'Proposal id', 'key' => 'proposal_id'],
            // ['sortable' => true, 'value' => 'Proposal Title', 'key' => 'proposal_title'],
            ['sortable' => true, 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'donor phone', 'key' => 'donor_phone'],
            ['sortable' => true, 'value' => 'currency name', 'key' => 'currency_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str', 'class_value_name' => 'status', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
