<?php

namespace App\Http\Requests\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:employee,provider,customer',
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8048',
            'role' => 'nullable|string|exists:roles,name',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ];
    }
}
