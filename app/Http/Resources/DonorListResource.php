<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonorListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'gender_str' => $this->gender_str,
            'country_id' => $this->country_id,
            'country_name' => $this->country?->name,
        ];
    }
}
