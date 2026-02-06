<?php

namespace App\Notifications;

use App\Helper\FirebaseHelper;
use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OfferExpiringSoonNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Offer $offer,
        public string $for = 'customer' // customer | owner
    ) {}

    public function via(object $notifiable): array
    {
        return ['database']; // DB + FCM manually in toDatabase
    }

    public function toDatabase($notifiable): array
    {
        $locale = $notifiable->locale ?? app()->getLocale() ?? 'ar';

        // ✅ titles/messages per receiver type
        if ($this->for === 'owner') {
            $title = $locale === 'ar' ? 'عرضك سينتهي قريبًا' : 'Your offer is expiring soon';
            $message = $locale === 'ar'
                ? 'سيتم انتهاء عرضك خلال 24 ساعة'
                : 'Your offer will expire within 24 hours';
        } else {
            $title = $locale === 'ar' ? 'عرض سينتهي قريبًا' : 'Offer expiring soon';
            $message = $locale === 'ar'
                ? 'يوجد عرض سينتهي خلال 24 ساعة'
                : 'An offer will expire within 24 hours';
        }

        // ✅ include offer name if exists
        $offerName = $this->offer->name ?? ($locale === 'ar' ? 'عرض' : 'Offer');
        $message .= " - " . $offerName;

        // ✅ FCM data payload (strings only)
        $fcmData = [
            'notificationType' => 'offer_expiring_soon',
            'offer_id' => (string) $this->offer->id,
            'for' => (string) $this->for,
        ];

        if (!empty($notifiable->fcm_token)) {
            FirebaseHelper::sendNotification(
                $notifiable->fcm_token,
                $title,
                $message,
                $fcmData
            );
        }

        // ✅ store in notifications table
        return [
            'title' => $title,
            'message' => $message,
            'notification_type' => 'offer_expiring_soon',
            'offer_id' => $this->offer->id,
            'data' => $fcmData,
        ];
    }
}
