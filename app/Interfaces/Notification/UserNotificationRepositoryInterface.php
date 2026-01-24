<?php

namespace App\Interfaces\Notification;

use App\Models\User;
use App\Models\Offer;

interface UserNotificationRepositoryInterface
{
    public function list(User $user, int $page = 1, int $pageSize = 10);
    public function unreadCount(User $user): int;
    public function markRead(User $user, int $notificationId): void;
    public function markAllRead(User $user): int;

    public function createNewOfferForCustomers(Offer $offer): void;
}
