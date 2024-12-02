<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
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
            'donor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'sometimes|string',
            'status' => 'required|integer',
            'notes' => 'nullable|string',
        ];
    }
}
