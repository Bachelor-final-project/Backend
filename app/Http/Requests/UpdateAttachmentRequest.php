<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachmentRequest extends FormRequest
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
            'attachable_type' => 'required|string|max:255', // Validate allowed model names
            'attachment_type' => 'required|integer', 
            'attachable_id' => 'required|integer', // Validate record ID format
            'files' => 'required', // Validate file type and size        
        ];
    }
}
