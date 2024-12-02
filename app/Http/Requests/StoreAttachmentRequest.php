<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
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
            'entity_id' => 'required|integer',
            'record_id' => 'required|integer',
            'filename' => 'required|string|max:255',
            'path' => 'required|string',
            'file_extension' => 'required|string|max:10',
            'filesize' => 'required|integer|min:0',
        ];
    }
}
