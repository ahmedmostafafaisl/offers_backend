<?php

namespace App\Repositories\Dashboard;

use App\Models\Offer;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\OffersRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OffersRepository extends BaseRepository implements OffersRepositoryInterface
{
    public function __construct(Offer $model)
    {
        parent::__construct($model);
    }

    // override paginate to eager load
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->model->newQuery()->with(['category', 'user']);

        if (!empty($filters['search'])) $this->applySearch($q, $filters['search']);
        $this->applyFilters($q, $filters);

        return $q->latest()->paginate($perPage)->withQueryString();
    }

    protected function applySearch($q, string $search): void
    {
        $q->where(function ($x) use ($search) {
            $x->where('name', 'like', "%{$search}%")
                ->orWhere('details', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    protected function applyFilters($q, array $filters): void
    {
        if (!empty($filters['category_id'])) $q->where('category_id', $filters['category_id']);
        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        if (isset($filters['is_active']) && $filters['is_active'] !== '') $q->where('is_active', (int)$filters['is_active']);

        // city filter matches city_ar OR city_en
        if (!empty($filters['city'])) {
            $city = $filters['city'];
            $q->where(function ($x) use ($city) {
                $x->where('city_ar', $city)->orWhere('city_en', $city);
            });
        }
    }
}
