<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // $this->resource = ['active_profile_id' => ..., 'profiles' => Collection]
        return [
            'active_profile_id' => $this->resource['active_profile_id'],
            'profiles' => ProfileResource::collection($this->resource['profiles']),
        ];
    }
}
