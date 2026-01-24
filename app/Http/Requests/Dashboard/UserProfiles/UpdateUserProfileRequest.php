<?php

namespace App\Http\Requests\Dashboard\UserProfiles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user_profile');

        return [
            'user_id' => 'required|exists:users,id|unique:user_profiles,user_id,' . $id,
            'linked_user_id' => 'nullable|exists:users,id|unique:user_profiles,linked_user_id,' . $id,
            'type' => 'required|in:employee,provider,customer',

            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8048',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',

            'whats_app_number' => 'nullable|string|max:30',
            'store_number' => 'nullable|string|max:50',
            'store_establish_date' => 'nullable|date',
            'tax_number' => 'nullable|string|max:100',
            'commercial_registration' => 'nullable|string|max:100',
        ];
    }
}
