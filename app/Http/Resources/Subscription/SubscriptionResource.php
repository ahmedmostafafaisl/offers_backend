<?php

namespace App\Http\Resources\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'plan' => [
                'id' => $this->plan->id,
                'name' => $this->plan->name,
            ],
            'start_date' => $this->start_date->toDateString(),
            'expiration_date' => $this->expiration_date->toDateString(),
            'type' => $this->type,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
