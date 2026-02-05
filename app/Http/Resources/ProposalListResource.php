<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ProposalListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'notes' => $this->notes,
            'cost' => $this->cost,
            'share_cost' => $this->share_cost,
            'expected_benificiaries_count' => $this->expected_benificiaries_count,
            'execution_date' => $this->execution_date,
            'publishing_date' => $this->publishing_date,
            'status' => $this->status,
            'status_str_ar' => $this->status_str_ar,
            'one_unit_price' => $this->one_unit_price,
            'isPayableOnline' => $this->isPayableOnline,
            'min_documenting_amount' => $this->min_documenting_amount,
            
            // Relationships
            'entity_name' => $this->entity->name,
            'area_name' => $this->area->name,
            'proposal_type_type_ar' => $this->proposalType->type_ar,
            'currency_id' => $this->currency_id,
            'currency_name' => $this->currency->name,
            'currency_code' => $this->currency->code,
            'documents' => DocumentResource::collection($this->whenLoaded('documents')),
            'cover_image' => $this->cover_image,
            
            // Computed attributes (from database)
            'paid_amount' => $this->when(array_key_exists('paid_amount', $this->resource->getAttributes()), round($this->paid_amount ?? 0, 2)),
            'remaining_amount' => $this->when(array_key_exists('remaining_amount', $this->resource->getAttributes()), round($this->remaining_amount ?? 0, 2)),
            'complete_donating_status_date' => $this->complete_donating_status_date,
            
            // Authorization
            'can_complete_donating_status' => Gate::allows('completeDonatingStatus', $this->resource),
            'can_complete_execution_status' => Gate::allows('completeExecutionStatus', $this->resource),
            'can_complete_archiving_status' => Gate::allows('completeArchivingStatus', $this->resource),
            'can_clone' => Gate::allows('canClone', $this->resource),
        ];
    }
}
