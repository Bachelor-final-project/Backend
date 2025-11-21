<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\WarehouseTransaction;

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

    public function messages()
    {
        return [
            'warehouse_id.exists' => __('Selected warehouse does not exist'),
            'item_id.required' => __('Item is required'),
            'item_id.exists' => __('Selected item does not exist'),
            'amount.integer' => __('Amount must be a number'),
            'amount.min' => __('Amount must be at least 0'),
            'transaction_type.integer' => __('Transaction type must be a number'),
            'transaction_type.between' => __('Transaction type must be between 1 and 3'),
            'warehouse_stakeholder_id.exists' => __('Selected stakeholder does not exist'),
            'recipient_name.string' => __('Recipient name must be text'),
            'recipient_name.max' => __('Recipient name cannot exceed 255 characters'),
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $transaction = $this->all();
            if (isset($transaction['transaction_type']) && $transaction['transaction_type'] == 2) {
                $currentTransaction = WarehouseTransaction::find($this->route('warehouse_transaction'));
                $availableQuantity = WarehouseTransaction::where('warehouse_id', $transaction['warehouse_id'] ?? $currentTransaction->warehouse_id)
                    ->where('item_id', $transaction['item_id'])
                    ->where('id', '!=', $currentTransaction->id)
                    ->selectRaw('SUM(CASE WHEN transaction_type = 1 THEN amount ELSE -amount END) as quantity')
                    ->value('quantity') ?? 0;
                
                if (isset($transaction['amount']) && $transaction['amount'] > $availableQuantity) {
                    $validator->errors()->add(
                        'amount',
                        __('Insufficient quantity. Available: :available, Requested: :requested', [
                            'available' => $availableQuantity,
                            'requested' => $transaction['amount']
                        ])
                    );
                }
            }
        });
    }
}
