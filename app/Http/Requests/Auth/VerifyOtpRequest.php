<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'otp' => 'required|numeric|digits:6',
        ];
    }
}
