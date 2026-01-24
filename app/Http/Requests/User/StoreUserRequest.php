<?php

namespace App\Http\Requests\User;

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
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'otp' => 'nullable|string|max:10',
            'pin_code' => 'nullable|string|max:10',
            'fcm_token' => 'nullable|string',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:8048',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'whats_app_number' => 'nullable|string|max:20',
            'store_number' => 'nullable|string|max:50',
            'store_establish_date' => 'nullable|date',
            'tax_number' => 'nullable|string|max:50',
            'commercial_registration' => 'nullable|string|max:100',
            // ✅ Add this to validate the nested social media array
            'social_media' => 'nullable|array',
            'social_media.*.platform' => 'required_with:social_media|string|max:50',
            'social_media.*.url' => 'required_with:social_media|url|max:255',
        ];
    }
}
