<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
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
            'phone' => 'required|exists:donors,phone',
            'project_id' => 'required|exists:proposals,id',
            'currency_id' => 'required|exists:currencies,id',
            'amount' => 'required|numberic|min:0',
            'status' => 'required|integer|between:0,3',
        ];
    }
}
