<?php

namespace App\Listeners;

use App\Events\OfferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Interfaces\Notification\PushNotificationRepositoryInterface;
use App\Interfaces\Notification\UserNotificationRepositoryInterface;

class HandleOfferCreated implements ShouldQueue
{
    public function __construct(
        private UserNotificationRepositoryInterface $dbNotifications,
        private PushNotificationRepositoryInterface $pushNotifications
    ) {}

    public function handle(OfferCreated $event): void
    {
        // 1) save notifications per customer in DB
        $this->dbNotifications->createNewOfferForCustomers($event->offer);

        // 2) send firebase push
        $this->pushNotifications->notifyCustomersNewOffer($event->offer);
    }
}
