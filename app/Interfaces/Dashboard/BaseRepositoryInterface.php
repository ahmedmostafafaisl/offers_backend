<?php

namespace App\Interfaces\Dashboard;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function findOrFail(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
}
