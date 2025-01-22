<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'body' => 'nullable|string',
            'title' => 'sometimes|string',
            'body' => 'nullable|string',
            'notes' => 'nullable|string',
            'currency_id' => 'sometimes|integer|exists:currencies,id', 
            'proposal_effects' => 'nullable|string',
            'cost' => 'sometimes|numeric',
            'share_cost' => 'sometimes|numeric',
            'expected_benificiaries_count' => 'sometimes|integer|min:0',
            'min_documenting_amount' => 'sometimes|integer|min:0',
            'publishing_date' => 'sometimes|date',
            'execution_date' => 'sometimes|date|after_or_equal:publishing_date',
            'entity_id' => 'sometimes|integer|exists:entities,id', 
            'proposal_type_id' => 'sometimes|integer|exists:proposal_types,id', 
            'area_id' => 'sometimes|integer|exists:areas,id', 
            'donated_amount' => 'sometimes|numeric', 
            'status' => 'sometimes|integer', 
        ];
    }
}
