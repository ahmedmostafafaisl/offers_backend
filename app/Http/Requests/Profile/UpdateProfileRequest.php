<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            // Usually you DON'T allow changing type; if you want, add rules for it.
            // 'type' => ['sometimes', 'in:employee,provider,customer'],

            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'photo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],

            'whats_app_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'store_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'store_establish_date' => ['sometimes', 'nullable', 'date'],
            'tax_number' => ['sometimes', 'nullable', 'string', 'max:100'],
            'commercial_registration' => ['sometimes', 'nullable', 'string', 'max:100'],
        ];
    }
}
