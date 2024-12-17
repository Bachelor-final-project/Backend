<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseDetailRequest extends FormRequest
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
            'warehouse_id' => 'sometimes|exists:warehouses,id',
            'unit_id' => 'sometimes|exists:units,id',
            'amount' => 'sometimes|integer|min:0',
        ];
    }
}
