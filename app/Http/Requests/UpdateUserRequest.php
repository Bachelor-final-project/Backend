<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'type' => 'sometimes|integer|between:1,2',
            'status' => 'sometimes|integer|between:1,3',
            'is_active' => 'sometimes|boolean',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->user->id,
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $this->user->id,
            'job_title' => 'nullable|sometimes|string|max:255',
            'password' => 'nullable|sometimes|confirmed',
        ];
    }
}
