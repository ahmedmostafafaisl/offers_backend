<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'user_id'        => $this->user_id,
            'linked_user_id' => $this->linked_user_id,
            'type'           => $this->type,

            'name'                  => $this->name,
            'phone'                 => $this->phone,
            'photo' => $this->photo ?  asset('storage/' . $this->photo) : null,
            'country'               => $this->country,
            'city'                  => $this->city,
            'whats_app_number'      => $this->whats_app_number,
            'store_number'          => $this->store_number,
            'store_establish_date'  => $this->store_establish_date,
            'tax_number'            => $this->tax_number,
            'commercial_registration' => $this->commercial_registration,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
