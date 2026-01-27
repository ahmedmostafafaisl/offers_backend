<?php

namespace App\Http\Resources\Offer;

use App\Models\FavoriteOffer;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\CategoryResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        $user = auth('sanctum')->user();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category_id' => (int) $this->category_id,


            'name' => $this->name,
            'details' => $this->details,

            // pricing
            'price' => $this->price,
            'price_before' => $this->price_before,
            'price_after' => $this->price_after,

            // ✅ discount safe (avoid null issues)
            'discount' => ($this->price_before !== null && $this->price_after !== null)
                ? (float) $this->price_before - (float) $this->price_after
                : null,

            // geo + location
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'location_name' => $this->location_name,
            'location_details' => $this->location_details,

            // dates
            'start_date' => $this->start_date,
            'expiration_date' => $this->expiration_date,

            'phone' => $this->phone,
            'provider_phone' => $this->user->phone,
            'store_number' => $this->user->store_number,
            'is_active' => (bool) $this->is_active,

            // counters (read-only)
            'views' => (int) $this->views,
            'likes' => FavoriteOffer::where('offer_id', $this->id)->count(),

            // Arabic address
            'address_ar' => $this->address_ar,
            'city_ar' => $this->city_ar,
            'governorate_ar' => $this->governorate_ar,
            'country_ar' => $this->country_ar,

            // English address
            'address_en' => $this->address_en,
            'city_en' => $this->city_en,
            'governorate_en' => $this->governorate_en,
            'country_en' => $this->country_en,

            'is_favorite' => $user
                ? FavoriteOffer::where('user_id', $user->id)
                ->where('offer_id', $this->id)
                ->exists()
                : false,

            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),

            // images
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn($image) => [
                    'id' => $image->id,
                    // ✅ common correct storage path:
                    'image' => asset('storage/' . $image->image),
                ]);
            }, []),

            'social_media' => $this->whenLoaded('socialMedia', function () {
                return $this->user->socialMedia->map(fn($s) => [
                    'platform' => $s->platform,
                    'url' => $s->url,
                ]);
            }, []),
        ];
    }
}
