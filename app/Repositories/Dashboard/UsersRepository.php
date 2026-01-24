<?php

namespace App\Repositories\Dashboard;

use App\Models\User;
use App\Repositories\Dashboard\BaseRepository;
use App\Interfaces\Dashboard\UsersRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UsersRepository extends BaseRepository implements UsersRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->model->newQuery()->with('roles');

        if (!empty($filters['search'])) {
            $this->applySearch($q, $filters['search']);
        }

        if (!empty($filters['type'])) {
            $q->where('type', $filters['type']);
        }

        return $q->latest()->paginate($perPage)->withQueryString();
    }

    protected function applySearch($q, string $search): void
    {
        $q->where(function ($x) use ($search) {
            $x->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
