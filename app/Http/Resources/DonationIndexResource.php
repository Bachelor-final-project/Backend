<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationIndexResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'donor_name' => $this->donor_name,
            'donor_phone' => $this->donor_phone,
            'document_nickname' => $this->document_nickname,
            'proposal_title' => $this->proposal_title,
            'amount' => $this->amount,
            'created_at_date_time' => $this->created_at_date_time,
            'currency_name' => $this->currency_name,
            'payment_method_name' => $this->payment_method_name,
            'status_str' => $this->status_str,
            'status' => $this->status,
        ];
    }
}
