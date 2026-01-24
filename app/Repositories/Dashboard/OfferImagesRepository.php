<?php

namespace App\Repositories\Dashboard;

use App\Models\OfferImage;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\OfferImagesRepositoryInterface;

class OfferImagesRepository extends BaseRepository implements OfferImagesRepositoryInterface
{
    public function __construct(OfferImage $model)
    {
        parent::__construct($model);
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['offer_id'])) $q->where('offer_id', $filters['offer_id']);
    }
}
