<?php

namespace App\Repositories\Notification;

use App\Models\User;
use App\Models\Offer;
use Illuminate\Support\Carbon;
use App\Interfaces\Notification\UserNotificationRepositoryInterface;
use App\Models\Notification;

class UserNotificationRepository implements UserNotificationRepositoryInterface
{
    public function list(User $user, int $page = 1, int $pageSize = 10)
    {
        return Notification::where('user_id', $user->id)
            ->orderByDesc('sent_at')
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function unreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function markRead(User $user, int $notificationId): void
    {
        Notification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->update(['read_at' => Carbon::now()]);
    }

    public function markAllRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
    }

    public function createNewOfferForCustomers(Offer $offer): void
    {
        $now = Carbon::now();

        User::where('type', 'customer')->whereNotNull('fcm_token')->select('id')->chunkById(1000, function ($users) use ($offer, $now) {
            $rows = [];
            foreach ($users as $u) {
                $rows[] = [
                    'user_id' => $u->id,
                    'type' => 'new_offer',
                    'offer_id' => $offer->id,

                    'title_ar' => 'تم إضافة عرض جديد',
                    'title_en' => 'New offer added',
                    'body_ar'  => $offer->name ?? 'عرض جديد',
                    'body_en'  => $offer->name ?? 'New offer',

                    'data' => json_encode([
                        'type' => 'new_offer',
                        'offer_id' => (string)$offer->id,
                    ]),

                    'sent_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Notification::insert($rows);
        });
    }
}
