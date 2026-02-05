<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'proposal_id' => $this->proposal_id,
            'donor_id' => $this->donor_id,
            'amount' => $this->amount,
            'currency_id' => $this->currency_id,
            'payment_method_id' => $this->payment_method_id,
            'document_nickname' => $this->document_nickname,
            'status' => $this->status,
            'status_str' => $this->status_str,
            'created_at_date_time' => $this->created_at_date_time,
            
            // Relationships
            'donor_name' => $this->donor->name,
            'donor_phone' => $this->donor->phone,
            'payment_method_name_ar' => $this->payment_method->name_ar,
            'payment_method_name_en' => $this->payment_method->name_en,
            'proposal_title' => $this->proposal->title,
            'currency_name' => $this->currency->name,
            'proposal' => (new ProposalResource($this->whenLoaded('proposal')))->toArray($request)
        ];
    }
}
