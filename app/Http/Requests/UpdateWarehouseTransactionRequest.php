<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseTransactionRequest extends FormRequest
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
            'item_id' => 'required|exists:items,id',
            'amount' => 'sometimes|integer|min:0',
            'transaction_type' => 'sometimes|integer|between:1,3',
            'warehouse_stakeholder_id' => 'nullable|exists:warehouse_stakeholders,id',
            'recipient_name' => 'nullable|string|max:255',
        ];
    }
}
