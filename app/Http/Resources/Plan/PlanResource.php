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
            'quarterly_price' => $this->quarterly_price,
            'semi_annual_price' => $this->semi_annual_price,
            'annual_price' => $this->annual_price,
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'subscription_type' => $this->when(isset($this->subscription_type), $this->subscription_type),
            'subscription_price' => $this->when(isset($this->subscription_price), $this->subscription_price),

        ];
    }
}
