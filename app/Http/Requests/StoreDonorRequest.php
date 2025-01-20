<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonorRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'gender' => 'required|integer|between:1,2',
            'phone' => 'required|string|max:15|unique:donors,phone',
            'country_id' => 'nullable|integer|exists:countries,id',
            'donations' => 'nullable|array', // donations can be empty
            'donations.*.proposal_id' => 'required_with:donations|integer|exists:proposals,id',
            'donations.*.currency_id' => 'required_with:donations|integer|exists:currencies,id',
            'donations.*.amount' => 'required_with:donations|numeric|min:1',
        ];
    }
}
