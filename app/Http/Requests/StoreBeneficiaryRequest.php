<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeneficiaryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:20|unique:beneficiaries,national_id',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date',
            'father_id' => 'nullable|string|max:255',
        ];
    }
}