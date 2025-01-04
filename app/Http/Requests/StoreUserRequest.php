<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'type' => 'required|integer|between:1,2',
            'status' => 'required|integer|between:1,3',
            'is_active' => 'required|boolean',
            'name' => 'required|string|max:255',
            'email' => 'sometimes|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'required|string|max:20|unique:users,phone,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:8',
            'job_title' => 'nullable|sometimes|string|max:255',
        ];
    }

    public function validated($key = null, $default = null)
    {
        // dd("Haello");
        // Force the status value to 1
        $validated['status'] = '1';

        $validated = parent::validated();

        return $validated;
    }
    protected function prepareForValidation()
    {
        // dd("sdsd");
        $this->merge([
            'status' => '1', // Always set status to 1
        ]);
    }
}
