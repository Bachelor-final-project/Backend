<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDonationRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => deterministicEncrypt($this->phone),
            ]);
        }
    }
    
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
            'phone' => 'sometimes|exists:donors,phone',
            'proposal_id' => 'sometimes|exists:proposals,id',
            'currency_id' => 'sometimes|exists:currencies,id',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|integer|between:0,3',
        ];
    }
}
