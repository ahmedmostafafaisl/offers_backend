<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'payment_id' => $this->payment_id,
            'name' => $this->name,
            'item_number' => $this->item_number,
            'price' => (float) $this->price,
            'quantity' => (int) $this->quantity,
            'total_amount' => (float) $this->total_amount,
        ];
    }
}
