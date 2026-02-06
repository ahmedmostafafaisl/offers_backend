<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionExpiringSoonNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSubscriptionExpiringSoonNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = now();

        // ✅ window: من 47 لـ 48 ساعة (buffer)
        $from = $now->copy()->addHours(47);
        $to   = $now->copy()->addHours(48);

        $subscriptions = Subscription::query()
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '>', $now)
            ->whereBetween('expiration_date', [$from, $to])
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('expiring_notified')
                    ->orWhere('expiring_notified', 0);
            })
            ->select('id', 'user_id', 'expiration_date')
            ->get();

        foreach ($subscriptions as $subscription) {

            // ✅ notify subscription owner
            $user = User::select('id', 'fcm_token', 'locale')
                ->find($subscription->user_id);

            if ($user) {
                $user->notify(
                    new SubscriptionExpiringSoonNotification($subscription)
                );
            }

            // ✅ mark as notified
            $subscription->update([
                'expiring_notified' => 1
            ]);
        }
    }
}
