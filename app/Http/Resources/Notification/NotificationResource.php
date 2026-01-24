<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{

    public function toArray($request)
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'type' => $this->data['notification_type'] ?? null,
            'title' => $this->data['title'] ?? null,
            'message' => $this->data['message'] ?? null,
            'offer_id' => $this->data['offer_id'] ?? null,
            'data' => $this->data['data'] ?? [],
            'read_at' => $this->read_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'is_read' => $this->read_at ? true : false,
        ];
    }
}
