<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalDetailRequest extends FormRequest
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
            'unit_id' => 'required|exists:units,id',
            'value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
