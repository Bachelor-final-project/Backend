<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Beneficiary;

class StoreBeneficiaryRequest extends FormRequest
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
            'national_id' => 'required|string|min:9|max:9|unique:beneficiaries,national_id,NULL,id,deleted_at,NULL',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date',
            'num_of_family_members' => 'nullable|integer',
            'social_status' => 'nullable|integer',
            'address' => 'nullable|string|max:512',
            // 'father_national_id' => 'nullable|sometimes|string|min:9|max:9',
            'father_national_id' => [
            'nullable',
            'integer',
            'digits:9',
            function ($attribute, $value, $fail) {
                if ($value) {
                    $result = Beneficiary::isValidFatherNationalId($value);
                    dd($result);
                    if (!$result) {
                        $fail('The father_national_id creates a circular dependency.');
                    }
                }
            },
        ],
            // 'warehouse_id' => 'required|integer|exists:warehouses,id',
        ];
    }
}
