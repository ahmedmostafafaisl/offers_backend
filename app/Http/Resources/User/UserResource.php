<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'active_profile_id' => $this->active_profile_id,
            'type' => $this->type,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'otp' => $this->otp,
            'pin_code' => $this->pin_code,
            'fcm_token' => $this->fcm_token,
            'photo' => $this->photo ?  asset('storage/' . $this->photo) : null,
            'country' => $this->country,
            'city' => $this->city,
            'whats_app_number' => $this->whats_app_number,
            'store_number' => $this->store_number,
            'store_establish_date' => $this->store_establish_date?->format('Y-m-d'),
            'tax_number' => $this->tax_number,
            'commercial_registration' => $this->commercial_registration,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'address_ar' => $this->address_ar,
            'city_ar' => $this->city_ar,
            'governorate_ar' => $this->governorate_ar,
            'country_ar' => $this->country_ar,
            'address_en' => $this->address_en,
            'city_en' => $this->city_en,
            'governorate_en' => $this->governorate_en,
            'country_en' => $this->country_en,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'location_name_ar' => $this->location_name_ar,
            'location_details_ar' => $this->location_details_ar,
            'location_name_en' => $this->location_name_en,
            'location_details_en' => $this->location_details_en,
            'social_media' => $this->whenLoaded('socialMedia', function () {
                return $this->socialMedia->map(function ($item) {
                    return [
                        'platform' => $item->platform,
                        'url' => $item->url,
                    ];
                });
            }),
        ];
    }
}
