<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
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
            'proposal_id' => 'sometimes|exists:proposals,id',
            'donor_id' => 'sometimes|exists:donors,id',
            'amount' => 'sometimes|numeric|min:0',
            'currency_id' => 'sometimes|exists:currencies,id',
            'note' => 'sometimes|nullable|string|max:1024',
            'expected_date' => 'sometimes|nullable|date_format:Y-m-d|after_or_equal:today',

        ];
    }
}
