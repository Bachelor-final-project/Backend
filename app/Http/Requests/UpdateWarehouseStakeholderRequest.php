<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseStakeholderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'type' => 'required|integer|in:1,2',
        ];
    }
}