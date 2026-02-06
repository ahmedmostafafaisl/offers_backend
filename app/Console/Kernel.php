<?php

namespace App\Console;

use App\Jobs\NotifyExpiredSubscriptionsJob;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\NotifySubscriptionsExpiringSoonJob;
use App\Jobs\SendOfferExpiringSoonNotifications;
use App\Jobs\SendSubscriptionExpiredNotifications;
use App\Jobs\SendSubscriptionExpiringSoonNotifications;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new SendOfferExpiringSoonNotifications())
            ->everyThreeHours();
        $schedule->job(new SendSubscriptionExpiringSoonNotifications())
            ->everyThreeHours();

        $schedule->job(new SendSubscriptionExpiredNotifications())
            ->hourly();

        // قبل الانتهاء بـ 48 ساعة
        $schedule->job(new NotifySubscriptionsExpiringSoonJob())
            ->hourly();

        // بعد الانتهاء
        $schedule->job(new NotifyExpiredSubscriptionsJob())
            ->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
