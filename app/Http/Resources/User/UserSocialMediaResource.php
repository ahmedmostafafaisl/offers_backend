<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSocialMediaResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'user_name' => $this->user->name ?? null,
            'platform'  => $this->platform,
            'url'       => $this->url,
            'created_at' => $this->created_at,
        ];
    }
}
