<?php

namespace App\Repositories\Dashboard;

use App\Models\OfferSocialMedia;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\OfferSocialMediaRepositoryInterface;

class OfferSocialMediaRepository extends BaseRepository implements OfferSocialMediaRepositoryInterface
{
    public function __construct(OfferSocialMedia $model)
    {
        parent::__construct($model);
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['offer_id'])) $q->where('offer_id', $filters['offer_id']);
        if (!empty($filters['platform'])) $q->where('platform', $filters['platform']);
    }
}
