<?php

namespace App\Repositories\Dashboard;

use App\Models\PlanFeature;
use App\Interfaces\Dashboard\PlanFeaturesRepositoryInterface;

class PlanFeaturesRepository extends BaseRepository implements PlanFeaturesRepositoryInterface
{
    public function __construct(PlanFeature $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($q, string $search): void
    {
        $q->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['plan_id'])) $q->where('plan_id', $filters['plan_id']);
    }
}
