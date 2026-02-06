<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Helper\FirebaseHelper;
use App\Models\Plan;

class SubscriptionExpiredNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $planId,
        public ?string $expirationDate = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $locale = $notifiable->locale ?? 'ar';

        $plan = Plan::find($this->planId);

        $planName = $plan?->name ?? ("#{$this->planId}");

        $title = $locale === 'ar' ? 'انتهى الاشتراك' : 'Subscription expired';
        $message = $locale === 'ar'
            ? "انتهى اشتراكك في خطة: {$planName}"
            : "Your subscription for plan: {$planName} has expired";

        $fcmData = [
            "notificationType" => "subscription_expired",
            "plan_id" => (string) $this->planId,
        ];

        // Firebase requires string values
        $fcmData = array_map(fn($v) => (string) $v, $fcmData);

        if (!empty($notifiable->fcm_token)) {
            FirebaseHelper::sendNotification(
                $notifiable->fcm_token,
                $title,
                $message,
                $fcmData
            );
        }

        return [
            'title' => $title,
            'message' => $message,
            'notification_type' => 'subscription_expired',
            'plan_id' => $this->planId,
            'expiration_date' => $this->expirationDate,
            'data' => $fcmData,
        ];
    }
}
