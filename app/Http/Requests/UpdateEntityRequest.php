<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntityRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'donating_form_path' => 'required|string|max:255',
            'supervisor_id' => 'required|int|exists:users,id',
            'country_id' => 'nullable|int|exists:countries,id',
            'home_title' => 'nullable|array',
            'home_title.ar' => 'nullable|string|max:255',
            'home_title.en' => 'nullable|string|max:255',
            'home_description' => 'nullable|array',
            'home_description.ar' => 'nullable|string',
            'home_description.en' => 'nullable|string',
            'whatsapp_number' => 'nullable|string|max:20',
            'initial_completed_projects' => 'nullable|integer|min:0',
        ];
    }
}
