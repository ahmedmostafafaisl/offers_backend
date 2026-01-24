<?php

namespace App\Repositories\Dashboard;

use App\Models\Slider;
use App\Interfaces\Dashboard\SlidersRepositoryInterface;

class SlidersRepository extends BaseRepository implements SlidersRepositoryInterface
{
    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($q, string $search): void
    {
        $q->where('name', 'like', "%{$search}%");
    }

    protected function applyFilters($q, array $filters): void
    {
        if (isset($filters['status']) && $filters['status'] !== '') {
            $q->where('status', (int)$filters['status']);
        }
    }
}
