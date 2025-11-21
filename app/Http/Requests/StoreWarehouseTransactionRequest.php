<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\WarehouseTransaction;

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

    public function messages()
    {
        return [
            'transactions.required' => __('Transactions are required'),
            'transactions.array' => __('Transactions must be an array'),
            'transactions.min' => __('At least one transaction is required'),
            'transactions.*.warehouse_id.required' => __('Warehouse is required'),
            'transactions.*.warehouse_id.exists' => __('Selected warehouse does not exist'),
            'transactions.*.item_id.required' => __('Item is required'),
            'transactions.*.item_id.exists' => __('Selected item does not exist'),
            'transactions.*.amount.required' => __('Amount is required'),
            'transactions.*.amount.integer' => __('Amount must be a number'),
            'transactions.*.amount.min' => __('Amount must be at least 0'),
            'transactions.*.transaction_type.required' => __('Transaction type is required'),
            'transactions.*.transaction_type.integer' => __('Transaction type must be a number'),
            'transactions.*.transaction_type.between' => __('Transaction type must be between 1 and 3'),
            'transactions.*.warehouse_stakeholder_id.exists' => __('Selected stakeholder does not exist'),
            'transactions.*.recipient_name.string' => __('Recipient name must be text'),
            'transactions.*.recipient_name.max' => __('Recipient name cannot exceed 255 characters'),
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('transactions', []) as $index => $transaction) {
                if ($transaction['transaction_type'] == 2) { // Outbound transaction
                    $availableQuantity = WarehouseTransaction::where('warehouse_id', $transaction['warehouse_id'])
                        ->where('item_id', $transaction['item_id'])
                        ->selectRaw('SUM(CASE WHEN transaction_type = 1 THEN amount ELSE -amount END) as quantity')
                        ->value('quantity') ?? 0;
                    
                    if ($transaction['amount'] > $availableQuantity) {
                        $validator->errors()->add(
                            "transactions.{$index}.amount",
                            __('Insufficient quantity. Available: :available, Requested: :requested', [
                                'available' => $availableQuantity,
                                'requested' => $transaction['amount']
                            ])
                        );
                    }
                }
            }
        });
    }
}
