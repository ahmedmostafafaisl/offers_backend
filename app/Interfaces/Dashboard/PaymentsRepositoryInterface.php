<?php

namespace App\Interfaces\Dashboard;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentsRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findOrFail(int $id): Payment;

    public function create(array $data): Payment;
    public function update(int $id, array $data): Payment;

    public function updateStatus(int $id, string $status, ?string $paymentId = null): Payment;

    public function delete(int $id): void;
}
