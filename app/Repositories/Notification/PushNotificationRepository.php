<?php

namespace App\Repositories\Api;

use App\Models\Offer;
use App\Services\Firebase\FcmService;
use App\Interfaces\Notification\PushNotificationRepositoryInterface;

class PushNotificationRepository implements PushNotificationRepositoryInterface
{
    public function __construct(private FcmService $fcm) {}

    public function notifyCustomersNewOffer(Offer $offer): void
    {
        $data = [
            'type' => 'new_offer',
            'offer_id' => (string)$offer->id,
        ];

        $this->fcm->sendToTopic(
            env('FCM_CUSTOMERS_TOPIC_AR', 'customers_ar'),
            ['title' => 'تم إضافة عرض جديد', 'body' => $offer->name ?? 'عرض جديد'],
            $data
        );

        $this->fcm->sendToTopic(
            env('FCM_CUSTOMERS_TOPIC_EN', 'customers_en'),
            ['title' => 'New offer added', 'body' => $offer->name ?? 'New offer'],
            $data
        );
    }
}
