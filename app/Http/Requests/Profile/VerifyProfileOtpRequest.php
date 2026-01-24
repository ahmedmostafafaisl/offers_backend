<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class VerifyProfileOtpRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'type' => ['required', 'in:employee,provider,customer'],
            'phone' => ['required', 'string', 'max:50'],
            'otp' => ['required', 'string', 'max:10'],
        ];
    }
}
