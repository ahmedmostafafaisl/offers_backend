<?php

namespace App\Notifications;

use App\Helper\FirebaseHelper;
use App\Models\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringSoonNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $planId,
        public ?string $expirationDate = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database']; // DB + FCM manually
    }

    public function toDatabase($notifiable): array
    {
        $locale = $notifiable->locale ?? app()->getLocale() ?? 'ar';

        $plan = Plan::select('id', 'name')->find($this->planId);
        $planName = $plan?->name ?? ("#{$this->planId}");

        $title = $locale === 'ar'
            ? 'اشتراكك سينتهي قريبًا'
            : 'Your subscription is expiring soon';

        $message = $locale === 'ar'
            ? "سينتهي اشتراكك في خطة: {$planName} خلال 48 ساعة"
            : "Your subscription for plan: {$planName} will expire within 48 hours";

        $fcmData = [
            'notificationType' => 'subscription_expiring_soon',
            'plan_id' => (string) $this->planId,
        ];

        // optional: send expiration date too
        if (!empty($this->expirationDate)) {
            $fcmData['expiration_date'] = (string) $this->expirationDate;
        }

        // ✅ Firebase requires all data values be strings
        $fcmData = array_map(fn($v) => (string) $v, $fcmData);

        // ✅ Send FCM
        if (!empty($notifiable->fcm_token)) {
            FirebaseHelper::sendNotification(
                $notifiable->fcm_token,
                $title,
                $message,
                $fcmData
            );
        }

        // ✅ Save in DB
        return [
            'title' => $title,
            'message' => $message,
            'notification_type' => 'subscription_expiring_soon',
            'plan_id' => $this->planId,
            'expiration_date' => $this->expirationDate,
            'data' => $fcmData,
        ];
    }
}
