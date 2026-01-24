<?php

namespace App\Http\Requests\Dashboard\Offers;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',

            'name' => 'required|string|max:255',
            'details' => 'nullable|string',

            'price' => 'nullable|numeric',
            'price_before' => 'nullable|numeric',
            'price_after' => 'nullable|numeric',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            'start_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:start_date',

            'phone' => 'nullable|string|max:30',
            'is_active' => 'nullable|boolean',

            'location_name' => 'nullable|string|max:255',
            'location_details' => 'nullable|string',

            // bilingual address
            'address_ar' => 'nullable|string|max:255',
            'city_ar' => 'nullable|string|max:255',
            'governorate_ar' => 'nullable|string|max:255',
            'country_ar' => 'nullable|string|max:255',

            'address_en' => 'nullable|string|max:255',
            'city_en' => 'nullable|string|max:255',
            'governorate_en' => 'nullable|string|max:255',
            'country_en' => 'nullable|string|max:255',

            // inline images/social
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:8048',

            'social_media' => 'nullable|array',
            'social_media.*.platform' => 'required_with:social_media|string|max:50',
            'social_media.*.url' => 'required_with:social_media|url|max:255',
        ];
    }
}
