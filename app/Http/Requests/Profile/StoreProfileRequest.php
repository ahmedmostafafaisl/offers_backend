<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:employee,provider,customer'],

            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],

            'whats_app_number' => ['nullable', 'string', 'max:50'],
            'store_number' => ['nullable', 'string', 'max:50'],
            'store_establish_date' => ['nullable', 'date'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'commercial_registration' => ['nullable', 'string', 'max:100'],
        ];
    }
}
