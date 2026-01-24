<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Subscription\SubscriptionResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'payment_type' => $this->payment_type,
            'amount' => (float) $this->amount,
            'reference_id' => $this->reference_id,
            'payment_id' => $this->payment_id,
            'status' => $this->status,
            'phone' => $this->phone,
            'items' => PaymentItemResource::collection($this->whenLoaded('items')),
            'subscription' => new SubscriptionResource($this->whenLoaded('subscription')),
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
