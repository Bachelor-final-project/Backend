<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;
use Illuminate\Support\Str;

class Proposal extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $guarded = ['donated_amount'];
    protected $appends = ['status_str_ar',  'currency_name', 'entity_name', 'proposal_type_type_ar', 'area_name', 'can_complete_donating_status', 'can_complete_execution_status', 'can_complete_archiving_status', 'status_details', 'cover_image'];
    protected $with = ['entity', 'area', 'proposalType', 'currency', 'files'];
    protected $casts = [
        'isPayableOnline' => 'boolean'
    ];
    public static $controllable = true;

    public const STATUSES = [
        'donatable' => 1,
        'darft' => 9,
        'completed' => 2,
        'processing' => 8,
        1 => 'donating_status',
        2 => 'execution_status',
        3 => 'ready_to_archive_status',
        8 => 'done_status'
    ];

    // public function getDonorNameAttribute()
    // {
    //     return $this->donor?->name;
    // }

    // public function getDonorAttribute()
    // {
    //     return $this->belongsTo(User::class, 'donor_id');
    // }
    public static function getDonatingStatusProposalsStackedGroup(){
        $proposals = Proposal::selectRaw('proposals.id, proposals.title as title, sum(donations.amount) as paid, ROUND(cost - sum(donations.amount), 2) as remaining')
        ->where('proposals.status', 1)
        ->join('donations', 'proposals.id', '=', 'donations.proposal_id')
        ->where('donations.status', 2)
        ->groupByRaw('proposals.id, proposals.title')
        ->get();
        $data = [
            [
                'name' => __('paid amount'),
                'data' => $proposals->pluck('paid')->toArray(),
            ],
            [
                'name' => __('remaining amount'),
                'data' => $proposals->pluck('remaining')->toArray(),
            ],
        ];
        
        return [
            "data" => $data,
            "categories" => $proposals->pluck('title')->toArray()
        ];
    }
    public static function getCompletedProposalsLast30DaysChartData(){
        $proposals = Proposal::selectRaw('status, COUNT(*) as count, date(created_at) as date')
        ->where('created_at', '>', now()->subDays(30)->endOfDay())
        ->where('status', 8)
        ->groupByRaw('status, date(created_at)')
        ->get();

            $result = [
                "data" => $proposals->pluck('count')->toArray()
            ];

        return $result;
    }
    public static function getProposalsByStatusChartData(){
        $proposals = Proposal::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');
            $statusMapping = [
                1 => __('donating_status'),
                2 => __('execution_status'),
                3 => __('ready_to_archive_status'),
                8 => __('done_status'),
            ];
            $result = [
                "categories" => array_values($statusMapping),
                "data" => array_map(function ($statusId) use ($proposals) {
                    return $proposals[$statusId]->count ?? 0; // Default to 0 if status is missing
                }, array_keys($statusMapping))
            ];

        return $result;
    }
    public static function getProposalsByTypesChartData(){
        $proposals = Proposal::selectRaw('proposal_types.type_ar as type, COUNT(*) as count')
            ->join('proposal_types', 'proposals.proposal_type_id', '=', 'proposal_types.id')
            ->groupBy('proposal_type_id')
            ->get();
            $result = [
                "categories" => $proposals->pluck('type')->toArray(),
                "data" => $proposals->pluck('count')->toArray(),
            ];

        return $result;
    }
    public function getStatusStrAttribute()
    {
        return [1 => 'donating_status', 2 => 'execution_status', 3 => 'ready_to_archive_status', 8 => 'done_status'][$this->status] ?? '';
    }
    public function getStatusStrArAttribute()
    {
        return [1 => 'مرحلة جمع التبرعات', 2 => 'مرحلة التنفيذ والتوثيق', 3 => 'بحاجة للأرشفة', 8 => 'مكتمل'][$this->status] ?? '';
    }
    public function getStatusDetailsAttribute(){
        return 'hi how are you';
    }
    public function getCoverImageAttribute(){
        return  $this->attachments()->where('attachment_type', 1)->first()?->url;
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

    public function getCanCompleteDonatingStatusAttribute(){
        return Gate::allows('completeDonatingStatus', $this);   
    }
    public function getCanCompleteExecutionStatusAttribute(){
        return Gate::allows('completeExecutionStatus', $this);   
    }
    public function getCanCompleteArchivingStatusAttribute(){
        return Gate::allows('completeArchivingStatus', $this);   
    }
    // public function getCanCompleteExecutionStatusAttribute(){
    //     return $this->currency->name;   
    // }

    // public function getPropsalDetailsAttribute()
    // {
    //     return $this->hasMany(ProposalDetail::class, 'proposal_id');
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
    public function donations(){
        return $this->hasMany(Donation::class, 'proposal_id');
    }
    public function documents(){
        return $this->hasMany(Document::class, 'proposal_id');
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'proposal_beneficiaries', 'proposal_id', 'beneficiary_id');
    }
    // Scopes

    public function scopePublic($query){
        $query->where('status', 1)->where('publishing_date', '<=', Carbon::now()->format('Y-m-d'));
    }
    public function scopeSearch($query, $request){

        // Filter the parameters ending with "_filter" and transform their keys
        $filterColumns = collect($request->all())
        ->filter(function ($value, $key) {
            return str_ends_with($key, '_filter');
        })
        ->mapWithKeys(function ($value, $key) {
            // Remove "_filter" from the key
            $newKey = Str::replaceLast('_filter', '', $key);
            return [$newKey => $value];
        })
        ->toArray();
        
        foreach ($filterColumns as $column => $value) {
            if(empty($value) ||  $value == 0) continue;

            if(is_numeric($value)) $query->where($column, $value);
            
            else if(is_string($value)) $query->where($column,'like', "%$value%");
        }
    }

    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'id', 'key' => 'id'],
            ['sortable' => true, 'value' => 'title', 'key' => 'title'],
            ['sortable' => true, 'value' => 'body', 'key' => 'body'],
            // ['sortable' => true, 'value' => 'notes', 'key' => 'notes'],
            ['sortable' => true, 'value' => 'currency', 'key' => 'currency_name'],
            // ['sortable' => true, 'value' => 'proposal_effects', 'key' => 'proposal_effects'],
            ['sortable' => true, 'value' => 'cost', 'key' => 'cost'],
            ['sortable' => true, 'value' => 'share cost', 'key' => 'share_cost'],
            ['sortable' => true, 'value' => 'expected benificiaries count', 'key' => 'expected_benificiaries_count'],
            ['sortable' => true, 'value' => 'execution date', 'key' => 'execution_date'],
            // ['sortable' => true, 'value' => 'publishing date', 'key' => 'publishing_date'],
            ['sortable' => true, 'value' => 'entity name', 'key' => 'entity_name'],
            ['sortable' => true, 'value' => 'proposal type', 'key' => 'proposal_type_type_ar'],
            ['sortable' => true, 'value' => 'area name', 'key' => 'area_name'],
            ['sortable' => true, 'value' => 'payable online', 'key' => 'isPayableOnline', 'translate' => 'true'],
            ['sortable' => true, 'value' => 'status', 'key' => 'status_str_ar', 'class_value_name' => 'status', 'has_class' => true, 'details_key' => 'status_details'],

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
            ['name' => __('donating_status'), 'id' => 1],
            ['name' => __('execution_status'), 'id' => 2],
            ['name' => __('ready_to_archive_status'), 'id' => 3],
            ['name' => __('done_status'), 'id' => 8],
        ];
    }
}
