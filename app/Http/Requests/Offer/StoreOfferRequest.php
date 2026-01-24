<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],

            'name' => ['required', 'string', 'max:255'],

            // pricing
            'price' => ['nullable', 'numeric'],
            'price_before' => ['nullable', 'numeric'],
            'price_after' => ['nullable', 'numeric', 'lte:price_before'], // ✅ منطقي

            'details' => ['nullable', 'string'],

            // geo
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            // dates
            'start_date' => ['nullable', 'date'],
            'expiration_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            'phone' => ['nullable', 'string', 'max:20'],

            'location_name' => ['nullable', 'string', 'max:255'],
            'location_details' => ['nullable', 'string'],

            // status (لو مسموح للمستخدم يحددها عند الإنشاء)
            'is_active' => ['nullable', 'boolean'],

            // Arabic address fields
            'address_ar' => ['nullable', 'string', 'max:255'],
            'city_ar' => ['nullable', 'string', 'max:255'],
            'governorate_ar' => ['nullable', 'string', 'max:255'],
            'country_ar' => ['nullable', 'string', 'max:255'],

            // English address fields
            'address_en' => ['nullable', 'string', 'max:255'],
            'city_en' => ['nullable', 'string', 'max:255'],
            'governorate_en' => ['nullable', 'string', 'max:255'],
            'country_en' => ['nullable', 'string', 'max:255'],

            // images
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:8048'],

            // social media
            'social_media' => ['nullable', 'array'],
            'social_media.*.platform' => ['required_with:social_media', 'string', 'max:50'],
            'social_media.*.url' => ['required_with:social_media', 'url', 'max:2048'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
