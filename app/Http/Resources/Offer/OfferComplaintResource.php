<?php

namespace App\Http\Resources\Offer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferComplaintResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name ?? null,
            'offer_id' => $this->offer_id,
            'subject' => $this->subject,
            'description' => $this->description,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
