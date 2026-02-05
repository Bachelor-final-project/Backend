<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'proposal_id' => $this->proposal_id,
            'donor_id' => $this->donor_id,
            'currency_id' => $this->currency_id,
            'document_nickname' => $this->document_nickname,
            'amount' => $this->amount,
            'note' => $this->note,
            'expected_date' => $this->expected_date,
            'proposal_name' => $this->whenLoaded('proposal', fn() => $this->proposal?->title),
            'donor_name' => $this->whenLoaded('donor', fn() => $this->donor?->name),
            'donor_phone' => $this->whenLoaded('donor', fn() => $this->donor?->phone),
            'currency_name' => $this->whenLoaded('currency', fn() => $this->currency?->name),
            'is_attached' => $this->whenLoaded('files', fn() => $this->files->isNotEmpty()),
            'document_file_url' => $this->whenLoaded('files', fn() => $this->files->where('attachment_type', 1)->first()?->url),
            'document_file_name' => $this->whenLoaded('files', fn() => $this->files->where('attachment_type', 1)->first()?->filename),
        ];
    }
}
