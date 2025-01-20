<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
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
            // 'donor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'title' => 'required|string',
            'body' => 'nullable|string',
            // 'status' => '', //
            'notes' => 'nullable|string',
            'currency_id' => 'required|integer|exists:currencies,id', 
            'proposal_effects' => 'nullable|string',
            'cost' => 'required|numeric',
            'share_cost' => 'required|numeric',
            'expected_benificiaries_count' => 'required|integer|min:0',
            'min_documenting_amount' => 'required|integer|min:0',
            'publishing_date' => 'required|date',
            'execution_date' => 'required|date|after_or_equal:publishing_date',
            'entity_id' => 'required|integer|exists:entities,id', 
            'proposal_type_id' => 'required|integer|exists:proposal_types,id', 
            'area_id' => 'required|integer|exists:areas,id', 
        ];
    }
}
