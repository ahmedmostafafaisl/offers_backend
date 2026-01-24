<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSocialMediaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|url|max:255',
        ];
    }
}
