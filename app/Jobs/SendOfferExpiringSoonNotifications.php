<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Models\User;
use App\Notifications\OfferExpiringSoonNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOfferExpiringSoonNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = now();

        // ✅ Window: offers expiring within next 24h (and not expired)
        $from = $now->copy()->addHours(23); // buffer to avoid missing
        $to   = $now->copy()->addHours(24);

        // ✅ IMPORTANT:
        // avoid sending multiple times => use a flag column OR cache OR a log table
        // Best: add a boolean column "expiring_notified" on offers
        $offers = Offer::query()
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '>', $now)
            ->whereBetween('expiration_date', [$from, $to])
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('expiring_notified')->orWhere('expiring_notified', 0);
            })
            ->select('id', 'user_id', 'name', 'expiration_date')
            ->get();

        foreach ($offers as $offer) {

            // ✅ 1) notify owner (provider)
            if (!empty($offer->user_id)) {
                $owner = User::select('id', 'fcm_token', 'locale')->find($offer->user_id);
                if ($owner) {
                    $owner->notify(new OfferExpiringSoonNotification($offer, 'owner'));
                }
            }

            // ✅ 2) notify customers (chunked)
            User::where('type', 'customer')
                ->select('id', 'fcm_token', 'locale')
                ->chunkById(500, function ($customers) use ($offer) {
                    foreach ($customers as $customer) {
                        $customer->notify(new OfferExpiringSoonNotification($offer, 'customer'));
                    }
                });

            // ✅ mark offer as notified to prevent duplicates
            $offer->update(['expiring_notified' => 1]);
        }
    }
}
