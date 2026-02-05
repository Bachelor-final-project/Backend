<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
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
            'phone' => 'required|exists:donors,phone',
            'proposal_id' => 'required|exists:proposals,id',
            'currency_id' => 'required|exists:currencies,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'document_nickname' => 'sometimes',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|integer|between:0,3',
        ];
    }
}
