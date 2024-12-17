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
            'type' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|max:255',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email',
            'phone' => 'sometimes|string|max:20',
            'job_title' => 'sometimes|string|max:255',
        ];
    }
}
