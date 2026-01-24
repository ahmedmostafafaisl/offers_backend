<?php

namespace App\Interfaces\Notification;

use App\Models\Offer;

interface PushNotificationRepositoryInterface
{
    public function notifyCustomersNewOffer(Offer $offer): void;
}
