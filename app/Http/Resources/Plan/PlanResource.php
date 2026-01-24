<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Request;
use App\Http\Resources\Plan\FeatureResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'monthly_price' => $this->monthly_price,
            'annually_price' => $this->annually_price,
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'subscription_type' => $this->when(isset($this->subscription_type), $this->subscription_type),
            'subscription_price' => $this->when(isset($this->subscription_price), $this->subscription_price),

        ];
    }
}
