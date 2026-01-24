<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPinCodeRequest extends FormRequest
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
            'pin_code' => 'required|numeric|digits:4',
        ];
    }
}
