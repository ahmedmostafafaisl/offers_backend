<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionExpiredNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSubscriptionExpiredNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = now();

        // ✅ expired (expiration_date <= now) and not notified yet
        $subscriptions = Subscription::query()
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '<=', $now)
            ->where(function ($q) {
                $q->whereNull('expired_notified')->orWhere('expired_notified', 0);
            })
            ->select('id', 'user_id', 'expiration_date', 'is_active')
            ->get();

        foreach ($subscriptions as $subscription) {

            // ✅ mark inactive if still active (optional but recommended)
            if ((int) $subscription->is_active === 1) {
                $subscription->is_active = 0;
            }

            // ✅ notify owner
            $user = User::select('id', 'fcm_token', 'locale')->find($subscription->user_id);
            if ($user) {
                $user->notify(new SubscriptionExpiredNotification($subscription));
            }

            // ✅ mark as notified
            $subscription->expired_notified = 1;
            $subscription->save();
        }
    }
}
