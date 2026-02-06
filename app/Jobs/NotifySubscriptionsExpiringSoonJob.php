<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Notifications\SubscriptionExpiringSoonNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifySubscriptionsExpiringSoonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): int
    {
        $count = 0;

        $now = now();
        $limit = now()->addHours(48);


        $subs = Subscription::query()
            ->with(['user:id,fcm_token', 'plan:id,name'])
            ->where('is_active', 1)
            ->where('expiring_notified', 0)
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '>', $now->toDateString())            // لسه ما انتهتش
            ->whereDate('expiration_date', '<=', $limit->toDateString())        // خلال 48 ساعة
            ->get();

        Log::info('NotifySubscriptionsExpiringSoonJob subs found', [
            'count' => $subs->count(),
            'range' => [$now->toDateString(), $limit->toDateString()],
        ]);

        foreach ($subs as $sub) {
            if (!$sub->user) continue;

            $sub->user->notify(
                new \App\Notifications\SubscriptionExpiringSoonNotification(
                    (int) $sub->plan_id,
                    optional($sub->expiration_date)->toDateTimeString()
                )
            );


            // ✅ mark as notified
            $sub->update([
                'expiring_notified' => 1,
            ]);

            $count++;
        }

        Log::info('NotifySubscriptionsExpiringSoonJob sent', ['sent' => $count]);

        return $count;
    }
}
