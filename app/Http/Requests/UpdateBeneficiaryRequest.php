<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBeneficiaryRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'national_id' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('beneficiaries', 'national_id')->ignore($this->route('beneficiary')), // ignore the current record value from the unique constrain
            ],
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:255',
            'dob' => 'sometimes|date',
            'father_id' => 'nullable|integer|exists:beneficiaries,id',
        ];
    }
}
