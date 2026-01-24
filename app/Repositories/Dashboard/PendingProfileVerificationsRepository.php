<?php

namespace App\Repositories\Dashboard;

use App\Models\PendingProfileVerification;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\PendingProfileVerificationsRepositoryInterface;

class PendingProfileVerificationsRepository extends BaseRepository implements PendingProfileVerificationsRepositoryInterface
{
    public function __construct(PendingProfileVerification $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($q, string $search): void
    {
        $q->where(function ($x) use ($search) {
            $x->where('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['type'])) $q->where('type', $filters['type']);
        if (!empty($filters['requester_user_id'])) $q->where('requester_user_id', $filters['requester_user_id']);
        if (!empty($filters['target_user_id'])) $q->where('target_user_id', $filters['target_user_id']);
        if (isset($filters['verified']) && $filters['verified'] !== '') {
            $filters['verified'] ? $q->whereNotNull('verified_at') : $q->whereNull('verified_at');
        }
    }
}
