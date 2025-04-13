<?php

namespace App\Models;

use App\Traits\ForUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Document extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $appends = ['proposal_name', 'donor_name', 'donor_phone', 'currency_name', 'is_attached', 'document_file_url', 'document_file_name'];

    protected $with = ['proposal', 'donor', 'currency', 'attachments'];
    public static $controllable = true;

    public static function getDocumentsByStatuesChartData(){
        // Query to calculate the status of each document based on the morph relation
        $completed_documents_count = Attachment::where('attachable_type', 'document')->count(DB::raw('DISTINCT attachable_id'));
        $not_completed_documents_count = Document::doesntHave('files')->count();
        // Prepare the result
        $result = [
        "categories" => [__('completed'), __('not_completed')], // Extract the statuses
        "data" => [$completed_documents_count, $not_completed_documents_count]       // Extract the counts
        ];

        return $result;
    }
    public function getIsAttachedAttribute() {
        return count($this->files) > 0;
    }
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

    public function getDonorPhoneAttribute()
    {
        return $this->donor?->phone;
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency?->name;
    }
    public function getDocumentFileUrlAttribute()
    {
        return  $this->files()->where('attachment_type', 1)->first()?->url;
    }
    public function getDocumentFileNameAttribute()
    {
        return  $this->files()->where('attachment_type', 1)->first()?->filename;
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
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
    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'id', 'key' => 'id'],
            ['sortable' => true, 'value' => 'proposal', 'key' => 'proposal_name'],
            ['sortable' => true, 'sortBy' => 'donor.name', 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'sortBy' => 'donor.phone', 'value' => 'donor phone', 'key' => 'donor_phone'],
            ['sortable' => true, 'value' => 'document_nickname', 'key' => 'document_nickname'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'currency', 'key' => 'currency_name'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'note'],
            ['sortable' => true, 'value' => 'expected date', 'key' => 'expected_date'],
            ['sortable' => true, 'value' => 'document file', 'key' => 'document_file_url', 'type' => 'link', 'link_text' => 'document_file_name'],
            // ['sortable' => true, 'value' => 'currency', 'key' => 'status_str', 'class_value_name' => 'status', 'has_class' => true],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
    public static function exportHeaders($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'id', 'key' => 'id'],
            ['sortable' => true, 'value' => 'proposal', 'key' => 'proposal_name'],
            ['sortable' => true, 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'document_nickname', 'key' => 'document_nickname'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'currency', 'key' => 'currency_name'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'note'],
            ['sortable' => true, 'value' => 'expected date', 'key' => 'expected_date'],
         ];
    }
    public static function headersForProposal($user = null)
    {
        return [
            // ['sortable' => true, 'value' => 'proposal', 'key' => 'proposal_name'],
            ['sortable' => true, 'value' => 'donor name', 'key' => 'donor_name'],
            ['sortable' => true, 'value' => 'amount', 'key' => 'amount'],
            ['sortable' => true, 'value' => 'currency', 'key' => 'currency_name'],
            ['sortable' => true, 'value' => 'notes', 'key' => 'note'],
            ['sortable' => true, 'value' => 'expected date', 'key' => 'expected_date'],
            ['sortable' => true, 'value' => 'document file', 'key' => 'document_file_url', 'type' => 'link', 'link_text' => 'document_file_name'],

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
    public static function updateOrCreateDocumentForDonation(Donation $donation, $old_proposal_id = null, $old_donor_id = null, $old_document_nickname = null){

        // check if the proposal, donor or document_nickname have been changed
        if(
            $old_proposal_id != null && $old_proposal_id != $donation->proposal_id ||
            $old_donor_id != null && $old_donor_id != $donation->donor_id ||
            $old_document_nickname != null && $old_document_nickname != $donation->document_nickname 
            ){
            self::updateOrCreateDocumentForDonationOldProposalAndDonor($donation, $old_proposal_id, $old_donor_id, $old_document_nickname);
        }

        $proposal = $donation->proposal;


        $total_paid = Donation::where('proposal_id', $proposal->id)
        ->where('donor_id', $donation->donor_id)
        ->where('currency_id', $donation->currency_id)
        ->where('document_nickname', $donation->document_nickname)
        ->where('status', 2)
        ->sum('amount');
        

        if($total_paid < $proposal->min_documenting_amount){
            
            Document::where('proposal_id', $proposal->id)
            ->where('donor_id', $donation->donor_id)
            ->where('currency_id', $donation->currency_id)
            ->where('document_nickname', $donation->document_nickname)
            ->delete();
            return true;
        }




         Document::updateOrCreate(
            ['proposal_id' =>  $proposal->id,'donor_id' =>  $donation->donor_id, 'document_nickname' => $donation->document_nickname, 'currency_id' =>  $donation->currency_id],
            [
                'amount' => $total_paid,
            ]
            );
        return true;
        
    }
    // public static function updateOrCreateDocumentForDonationOldPropsal(Donation $donation, $old_proposal_id = null){

    //     $proposal = Proposal::where('id', $old_proposal_id)->first();

    //     $total_paid = Donation::where('proposal_id', $proposal->id)
    //     ->where('donor_id', $donation->donor_id)
    //     ->where('currency_id', $donation->currency_id)
    //     ->where('status', 2)
    //     ->sum('amount');
        

    //     if($total_paid < $proposal->min_documenting_amount){
            
    //         Document::where('proposal_id', $proposal->id)
    //         ->where('donor_id', $donation->donor_id)
    //         ->where('currency_id', $donation->currency_id)
    //         ->delete();
    //         return true;
    //     }

    //      Document::updateOrCreate(
    //         ['proposal_id' =>  $proposal->id,'donor_id' =>  $donation->donor_id,'currency_id' =>  $donation->currency_id],
    //         [
    //             'amount' => $total_paid,
    //             'document_nickname' => $donation->document_nickname,
    //         ]
    //         );
    //     return true;
        
    // }
    public static function updateOrCreateDocumentForDonationOldProposalAndDonor(Donation $donation, $old_proposal_id = null, $old_donor_id = null, $old_document_nickname = null){


        $donor = ($old_donor_id == null)? $donation->donor : Donor::where('id', $old_donor_id)->first();
        $proposal = ($old_proposal_id == null)? $donation->proposal : Proposal::where('id', $old_proposal_id)->first();
        $document_nickname = ($old_document_nickname == null)? $donation->document_nickname : $old_document_nickname;

        $total_paid = Donation::where('proposal_id', $proposal->id)
        ->where('donor_id', $donor->id)
        ->where('currency_id', $donation->currency_id)
        ->where('document_nickname', $document_nickname)
        ->where('status', 2)
        ->sum('amount');
        

        if($total_paid < $proposal->min_documenting_amount){
            
            Document::where('proposal_id', $proposal->id)
            ->where('donor_id', $donor->id)
            ->where('document_nickname', $document_nickname)
            ->where('currency_id', $donation->currency_id)
            ->delete();
            return true;
        }

         Document::updateOrCreate(
            ['proposal_id' =>  $proposal->id,'donor_id' =>  $donor->id,'document_nickname' => $document_nickname,'currency_id' =>  $donation->currency_id],
            [
                'amount' => $total_paid,
            ]
            );
        return true;
        
    }

}
