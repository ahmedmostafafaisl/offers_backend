<?php

namespace App\Repositories\Dashboard;

use App\Models\UserProfile;
use App\Interfaces\Dashboard\UserProfilesRepositoryInterface;

class UserProfilesRepository extends BaseRepository implements UserProfilesRepositoryInterface
{
    public function __construct(UserProfile $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($q, string $search): void
    {
        $q->where(function ($x) use ($search) {
            $x->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
        });
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['type'])) $q->where('type', $filters['type']);
        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        if (!empty($filters['linked_user_id'])) $q->where('linked_user_id', $filters['linked_user_id']);
    }
}
