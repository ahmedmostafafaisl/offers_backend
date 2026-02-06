<?php

namespace App\Jobs;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\SubscriptionExpiredNotification;

class NotifyExpiredSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): int
    {
        $count = 0;

        $subs = Subscription::query()
            ->with('user:id,fcm_token')
            ->where('is_active', 1)
            ->where('expired_notified', 0)
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '<', now())
            ->get();

        Log::info('NotifyExpiredSubscriptionsJob subs found', [
            'count' => $subs->count()
        ]);

        foreach ($subs as $sub) {
            if (!$sub->user) {
                continue;
            }

            // 🔔 send notification
            $sub->user->notify(
                new SubscriptionExpiredNotification(
                    (int) $sub->plan_id,
                    optional($sub->expiration_date)->toDateTimeString()
                )
            );

            // ✅ mark as notified
            $sub->update([
                'expired_notified' => 1,
            ]);

            $count++;
        }

        Log::info('NotifyExpiredSubscriptionsJob sent', ['sent' => $count]);

        return $count;
    }
}
