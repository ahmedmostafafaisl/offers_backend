<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'commercial_registration' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'password' => 'required|string|min:6',
            'whats_app_number' => 'nullable|string|max:20',
            'store_number' => 'nullable|string|max:50',
            'store_establish_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
            'fcm_token' => 'nullable|string',
        ];
    }
}
