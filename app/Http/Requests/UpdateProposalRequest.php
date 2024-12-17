<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
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
            'donor_id' => 'sometimes|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'status' => 'sometimes|integer',
            'notes' => 'sometimes|string',
        ];
    }
}
