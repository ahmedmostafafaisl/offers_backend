<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserSocialMediaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ];
    }
}
