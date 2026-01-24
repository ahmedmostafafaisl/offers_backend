<?php

namespace App\Repositories\Payment;

use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Interfaces\Payment\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Payment::query()->with('items')->latest();

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

        return $q->paginate($perPage);
    }

    public function findOrFail(int $id): Payment
    {
        return Payment::with('items')->findOrFail($id);
    }

    public function create(array $payload): Payment
    {
        return DB::transaction(function () use ($payload) {

            $items = $payload['items'] ?? [];
            unset($payload['items']);

            // ✅ generate unique reference_id if not provided

            $payment = Payment::create($payload);

            foreach ($items as $item) {
                $qty = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);

                $payment->items()->create([
                    'name' => $item['name'],
                    'item_number' => $item['item_number'] ?? null,
                    'price' => $price,
                    'quantity' => $qty,
                    'total_amount' => $price * $qty,
                ]);
            }

            // ✅ keep amount synced to sum(items) if needed
            if (!empty($items)) {
                $sum = $payment->items()->sum('total_amount');
                $payment->update(['amount' => $sum]);
            }

            return $payment->load('items');
        });
    }

    public function update(int $id, array $payload): Payment
    {
        return DB::transaction(function () use ($id, $payload) {
            $payment = Payment::with('items')->findOrFail($id);

            $items = $payload['items'] ?? null;
            unset($payload['items']);

            $payment->update($payload);

            // ✅ optional: replace items if sent
            if (is_array($items)) {
                $payment->items()->delete();

                foreach ($items as $item) {
                    $qty = (int) ($item['quantity'] ?? 1);
                    $price = (float) ($item['price'] ?? 0);

                    $payment->items()->create([
                        'name' => $item['name'],
                        'item_number' => $item['item_number'] ?? null,
                        'price' => $price,
                        'quantity' => $qty,
                        'total_amount' => $price * $qty,
                    ]);
                }

                $sum = $payment->items()->sum('total_amount');
                $payment->update(['amount' => $sum]);
            }

            return $payment->fresh()->load('items');
        });
    }

    public function updateStatus(int $id, string $status, ?string $paymentId = null): Payment
    {
        $payment = Payment::findOrFail($id);

        $data = ['status' => $status];
        if ($paymentId !== null) {
            $data['payment_id'] = $paymentId;
        }

        $payment->update($data);

        return $payment->fresh()->load('items');
    }

    public function delete(int $id): void
    {
        Payment::findOrFail($id)->delete();
    }

    public function providerPayments(array $filters = [], int $perPage = 15)
    {
        $user = auth()->user();
        $q = Payment::query()->with('items', 'subscription')->where('user_id', $user->id)->get();
        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (!empty($filters['reference_id'])) {
            $q->where('reference_id', $filters['reference_id']);
        }

        if (!empty($filters['phone'])) {
            $q->where('phone', $filters['phone']);
        }

        return $q;
    }
}
