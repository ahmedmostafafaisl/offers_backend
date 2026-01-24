<?php

namespace App\Repositories\Dashboard;

use App\Models\OfferReport;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\OfferReportsRepositoryInterface;

class OfferReportsRepository extends BaseRepository implements OfferReportsRepositoryInterface
{
    public function __construct(OfferReport $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($q, string $search): void
    {
        $q->where('reason', 'like', "%{$search}%");
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        if (!empty($filters['offer_id'])) $q->where('offer_id', $filters['offer_id']);
    }
}
