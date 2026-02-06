<?php

namespace App\Listeners;

use App\Events\OfferCreated;
use App\Models\User;
use App\Notifications\NewOfferNotification;

class SendNewOfferToCustomers
{
    public function handle(OfferCreated $event): void
    {
        User::where('type', 'customer')
            ->select('id', 'fcm_token')
            ->chunkById(500, function ($customers) use ($event) {
                foreach ($customers as $customer) {
                    $customer->notify(new NewOfferNotification($event->offer));
                }
            });
    }
}
