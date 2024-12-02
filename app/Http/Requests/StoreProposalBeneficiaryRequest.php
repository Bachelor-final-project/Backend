<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalBeneficiaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'proposal_id' => 'required|exists:proposals,id',
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'status' => 'sometimes|integer',
            'notes' => 'nullable|string',
        ];
    }
}
