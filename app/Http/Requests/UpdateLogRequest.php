<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLogRequest extends FormRequest
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
            'entity_id' => 'sometimes|string|max:255',
            'record_id' => 'sometimes|integer',
            'type' => 'sometimes|string|max:255',
            'notes' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
        ];
    }
}
