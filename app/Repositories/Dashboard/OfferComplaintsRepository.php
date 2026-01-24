<?php

namespace App\Repositories\Dashboard;

use App\Models\OfferComplaint;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\OfferComplaintsRepositoryInterface;

class OfferComplaintsRepository extends BaseRepository implements OfferComplaintsRepositoryInterface
{
    public function __construct(OfferComplaint $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($q, string $search): void
    {
        $q->where(function ($x) use ($search) {
            $x->where('subject', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        if (!empty($filters['offer_id'])) $q->where('offer_id', $filters['offer_id']);
    }
}
