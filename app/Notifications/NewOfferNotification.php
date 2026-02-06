<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Helper\FirebaseHelper;
use App\Models\Offer;

class NewOfferNotification extends Notification
{
    use Queueable;

    public function __construct(public Offer $offer)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database']; // ✅ DB + FCM will be sent manually in toDatabase
    }

    public function toDatabase($notifiable): array
    {
        // ✅ language text
        $locale = $notifiable->locale ?? 'ar';

        $title = $locale === 'ar' ? 'تم إضافة عرض جديد' : 'New offer added';
        $message = $this->offer->name ?? ($locale === 'ar' ? 'عرض جديد' : 'New offer');

        // ✅ FCM data payload
        $fcmData = [
            'notificationType' => 'new_offer',
            'offer_id' => (string) $this->offer->id,
        ];
        $fcmData = array_map(fn($v) => (string)$v, $fcmData);


        // ✅ Send to Firebase
        if (!empty($notifiable->fcm_token)) {
            FirebaseHelper::sendNotification(
                $notifiable->fcm_token,
                $title,
                $message,
                $fcmData
            );
        }

        // ✅ Store in DB (Laravel notifications table)
        return [
            'title' => $title,
            'message' => $message,
            'notification_type' => 'new_offer',
            'offer_id' => $this->offer->id,
            'data' => $fcmData,
        ];
    }
}
