<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'nullable', 'exists:categories,id'],

            'name' => ['sometimes', 'required', 'string', 'max:255'],

            // pricing
            'price' => ['sometimes', 'nullable', 'numeric'],
            'price_before' => ['sometimes', 'nullable', 'numeric'],
            'price_after' => ['sometimes', 'nullable', 'numeric', 'lte:price_before'],

            'details' => ['sometimes', 'nullable', 'string'],

            // geo
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],

            // dates
            'start_date' => ['sometimes', 'nullable', 'date'],
            'expiration_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],

            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],

            'location_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'location_details' => ['sometimes', 'nullable', 'string'],

            // status
            'is_active' => ['sometimes', 'nullable', 'boolean'],

            // Arabic address fields
            'address_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'governorate_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country_ar' => ['sometimes', 'nullable', 'string', 'max:255'],

            // English address fields
            'address_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'governorate_en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country_en' => ['sometimes', 'nullable', 'string', 'max:255'],

            // For image operations
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:offer_images,id'],

            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:8048'],

            // social media
            'social_media' => ['sometimes', 'nullable', 'array'],
            'social_media.*.platform' => ['required_with:social_media', 'string', 'max:50'],
            'social_media.*.url' => ['required_with:social_media', 'url', 'max:2048'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
