<?php

namespace App\Repositories\Dashboard;

use App\Models\Subscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Dashboard\SubscriptionsRepositoryInterface;

class SubscriptionsRepository extends BaseRepository implements SubscriptionsRepositoryInterface
{
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->model->newQuery()->with(['user', 'plan']);
        if (!empty($filters['search'])) $this->applySearch($q, $filters['search']);
        $this->applyFilters($q, $filters);
        return $q->latest()->paginate($perPage)->withQueryString();
    }

    protected function applySearch($q, string $search): void
    {
        $q->where('type', 'like', "%{$search}%");
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        if (!empty($filters['plan_id'])) $q->where('plan_id', $filters['plan_id']);
        if (isset($filters['is_active']) && $filters['is_active'] !== '') $q->where('is_active', (int)$filters['is_active']);
        if (!empty($filters['type'])) $q->where('type', $filters['type']);
    }
}
