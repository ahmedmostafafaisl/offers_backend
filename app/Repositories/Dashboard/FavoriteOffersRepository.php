<?php

namespace App\Repositories\Dashboard;


use App\Models\FavoriteOffer;
use App\Interfaces\Dashboard\FavoriteOffersRepositoryInterface;

class FavoriteOffersRepository extends BaseRepository implements FavoriteOffersRepositoryInterface
{
    public function __construct(FavoriteOffer $model)
    {
        parent::__construct($model);
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        if (!empty($filters['offer_id'])) $q->where('offer_id', $filters['offer_id']);
    }
}
