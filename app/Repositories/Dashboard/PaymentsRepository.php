<?php

namespace App\Repositories\Dashboard;

use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Interfaces\Dashboard\PaymentsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentsRepository implements PaymentsRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $q = Payment::query()->with(['items', 'user'])->latest();

        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }
        if (!empty($filters['payment_type'])) {
            $q->where('payment_type', $filters['payment_type']);
        }
        if (!empty($filters['reference_id'])) {
            $q->where('reference_id', $filters['reference_id']);
        }
        if (!empty($filters['phone'])) {
            $q->where('phone', $filters['phone']);
        }
        if (!empty($filters['user_id'])) {
            $q->where('user_id', $filters['user_id']);
        }

        return $q->paginate($perPage);
    }

    public function findOrFail(int $id): Payment
    {
        return Payment::with('items')->findOrFail($id);
    }

    public function create(array $data): Payment
    {
        return DB::transaction(function () use ($data) {

            $items = $data['items'] ?? [];
            unset($data['items']);

            $data['reference_id'] = $data['reference_id'] ?? $this->generateReferenceId();

            // amount لو مش مبعوت احسبه من items
            if (!isset($data['amount']) && is_array($items) && count($items)) {
                $data['amount'] = collect($items)->sum(function ($i) {
                    $price = (float) ($i['price'] ?? 0);
                    $qty   = (int)   ($i['quantity'] ?? 1);
                    return $price * $qty;
                });
            }

            $payment = Payment::create($data);

            foreach ($items as $item) {
                $price = (float) ($item['price'] ?? 0);
                $qty   = (int) ($item['quantity'] ?? 1);

                $payment->items()->create([
                    'name'         => $item['name'],
                    'item_number'  => $item['item_number'] ?? null,
                    'price'        => $price,
                    'quantity'     => $qty,
                    'total_amount' => $price * $qty,
                ]);
            }

            // sync amount
            if ($payment->items()->count()) {
                $payment->update(['amount' => $payment->items()->sum('total_amount')]);
            }

            return $payment->load('items');
        });
    }

    public function update(int $id, array $data): Payment
    {
        return DB::transaction(function () use ($id, $data) {
            $payment = Payment::with('items')->findOrFail($id);

            $items = $data['items'] ?? null;
            unset($data['items']);

            $payment->update($data);

            // لو items اتبعتت، هنستبدلها بالكامل
            if (is_array($items)) {
                $payment->items()->delete();

                foreach ($items as $item) {
                    $price = (float) ($item['price'] ?? 0);
                    $qty   = (int) ($item['quantity'] ?? 1);

                    $payment->items()->create([
                        'name'         => $item['name'],
                        'item_number'  => $item['item_number'] ?? null,
                        'price'        => $price,
                        'quantity'     => $qty,
                        'total_amount' => $price * $qty,
                    ]);
                }

                $payment->update(['amount' => $payment->items()->sum('total_amount')]);
            }

            return $payment->fresh()->load('items');
        });
    }

    public function updateStatus(int $id, string $status, ?string $paymentId = null): Payment
    {
        $payment = Payment::findOrFail($id);

        $payload = ['status' => $status];
        if ($paymentId !== null) {
            $payload['payment_id'] = $paymentId;
        }

        $payment->update($payload);

        return $payment->fresh()->load('items');
    }

    public function delete(int $id): void
    {
        Payment::findOrFail($id)->delete();
    }

    private function generateReferenceId(): string
    {
        return 'PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8));
    }
}
