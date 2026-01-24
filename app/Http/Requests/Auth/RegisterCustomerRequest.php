<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'country' => 'nullable|string|max:100',
            'password' => 'required|string|min:6',
            'city' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
            'fcm_token' => 'nullable|string',
        ];
    }
}
