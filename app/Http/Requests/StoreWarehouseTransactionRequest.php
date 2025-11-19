<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseTransactionRequest extends FormRequest
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
            'transactions' => 'required|array|min:1',
            'transactions.*.warehouse_id' => 'required|exists:warehouses,id',
            'transactions.*.item_id' => 'required|exists:items,id',
            'transactions.*.amount' => 'required|integer|min:0',
            'transactions.*.transaction_type' => 'required|integer|between:1,3',
            'transactions.*.warehouse_stakeholder_id' => 'nullable|exists:warehouse_stakeholders,id',
            'transactions.*.recipient_name' => 'nullable|string|max:255',
        ];
    }
}
