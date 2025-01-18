<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDonorRequest extends FormRequest
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
            'gender' => 'required|integer|between:1,2',
            'phone' => 'required|string|msx:15|unique:donors,phone,'.$this->donor->id,
            'country_id' => 'nullable|integer|exists:countries,id'
        ];
    }
}
