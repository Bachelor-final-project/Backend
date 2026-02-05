<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntityListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'donating_form_path' => $this->donating_form_path,
            'supervisor_id' => $this->supervisor_id,
            'country_id' => $this->country_id,
            'whatsapp_number' => $this->whatsapp_number,
            'initial_completed_projects' => $this->initial_completed_projects,
            
            // Computed attributes
            'supervisor_name' => $this->supervisor?->name,
            'country_name' => $this->country?->name,
            'donating_form_link' => url("/donating-form/{$this->donating_form_path}"),
            'home_page_link' => url("/home/{$this->donating_form_path}"),
            
            // Expensive counts (only when loaded)
            'completed_projects_count' => $this->when(isset($this->completed_projects_count), $this->completed_projects_count),
            'number_of_public_proposal' => $this->when(isset($this->number_of_public_proposal), $this->number_of_public_proposal),
            'donations_count' => $this->when(isset($this->donations_count), $this->donations_count),
        ];
    }
}
