<?php

namespace App\Repositories\Dashboard;

use App\Models\Category;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\CategoriesRepositoryInterface;

class CategoriesRepository extends BaseRepository implements CategoriesRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($q, string $search): void
    {
        $q->where('name', 'like', "%{$search}%");
    }
}
