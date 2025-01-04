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
        // dd($this->beneficiary);
        return [
            'name' => 'sometimes|string|max:255',
            'national_id' => [
                'sometimes',
                'string',
                'min:9',
                'max:9',
                'unique:beneficiaries,national_id,' . $this->beneficiary->id,
                // Rule::unique('beneficiaries', 'national_id')->ignore($this->beneficiary->id), // ignore the current record value from the unique constrain
            ],
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:255',
            'dob' => 'sometimes|date',
            'father_national_id' => 'nullable|sometimes|string|min:9|max:9',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ];
    }
}
