<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogRequest extends FormRequest
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
            'entity_id' => 'required|string|max:255',
            'record_id' => 'required|integer',
            'type' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
