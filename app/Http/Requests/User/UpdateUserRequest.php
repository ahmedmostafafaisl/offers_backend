<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => "sometimes|email|unique:users,email,{$userId}",
            'password' => 'sometimes|string|min:6',
            'phone' => 'nullable|string|max:20',
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

            // geo
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],

            // Arabic address fields
            'address_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'governorate_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'location_name_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'location_details_ar' => ['sometimes', 'nullable', 'string'],

            // English address fields
            'address_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'governorate_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'location_name_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'location_details_en' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
