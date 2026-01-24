<?php

namespace App\Repositories\Dashboard;

use App\Interfaces\Dashboard\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            if (method_exists($this, 'applySearch')) {
                $this->applySearch($q, $search);
            }
        }

        if (method_exists($this, 'applyFilters')) {
            $this->applyFilters($q, $filters);
        }

        return $q->latest()->paginate($perPage)->withQueryString();
    }

    public function findOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $row = $this->findOrFail($id);
        $row->update($data);
        return $row;
    }

    public function delete(int $id): bool
    {
        $row = $this->findOrFail($id);
        return (bool) $row->delete();
    }
}
