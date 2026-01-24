<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required_without:phone|email|exists:users,email',
            'phone' => 'required_without:email|string|exists:users,phone',
            'new_password' => 'required|string|min:6|confirmed',
        ];
    }
}
