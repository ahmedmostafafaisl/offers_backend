<?php

namespace App\Repositories\Subscription;

use App\Models\Plan;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Subscription;
use App\Services\Payment\Moyasar\MoyasarPaymentService;
use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function all()
    {
        return Subscription::with(['user', 'plan'])->get();
    }

    public function find($id)
    {
        return Subscription::with(['user', 'plan'])->findOrFail($id);
    }

    public function create(array $data)
    {
        // ✅ default pending
        $data['is_active'] = 0;
        return Subscription::create($data);
    }

    public function createWithPayment(array $data)
    {
        return DB::transaction(function () use ($data) {

            // ✅ required inputs (example)
            // user_id, plan_id, type (monthly/annually)
            $plan = Plan::findOrFail($data['plan_id']);

            $type = $data['type']; // monthly | annually
            $amount = $type === 'annually' ? (float)$plan->annually_price : (float)$plan->monthly_price;

            // ✅ 1) create subscription as pending (inactive)
            $subscription = Subscription::create([
                'user_id' => $data['user_id'],
                'plan_id' => $plan->id,
                'type' => $type,
                'start_date' => $data['start_date'] ?? now()->toDateString(),
                'expiration_date' => $data['expiration_date'] ?? now()->addMonth()->toDateString(), // عدل حسب type
                'is_active' => 0,
            ]);

            // ✅ 2) create pending payment
            $payment = Payment::create([
                'user_id' => $data['user_id'],
                'subscription_id' => $subscription->id,   // ✅ الربط
                'payment_type' => 'moyasar',
                'amount' => $amount,
                'status' => 'pending',
                'phone' => $data['phone'] ?? null,
                // payment_id سيتم تحديثه بعد ما يرجع من moyasar
            ]);

            // ✅ 3) create one payment item linked to subscription
            PaymentItem::create([
                'payment_id' => $payment->id,
                'name' => "Subscription: {$plan->name}",
                'item_number' => "SUB-{$subscription->id}",
                'price' => $amount,
                'quantity' => 1,
                'total_amount' => $amount,
            ]);

            $successUrl = $data['success_url'] ?? route('moyasar.payment.callback', [
                'subscription_id' => $subscription->id,
                'reference_id'    => $payment->reference_id,
            ]);

            $callbackUrl = $data['callback_url'] ?? route('moyasar.payment.callback', [
                'subscription_id' => $subscription->id,
                'reference_id'    => $payment->reference_id,
            ]);


            // ✅ 4) call Moyasar checkout
            $payload = [
                'amount' => (int) round($amount * 100), // moyasar غالبًا بالهللة/القرش (Minor units)
                'currency' => 'SAR',
                'description' => "Subscription for {$plan->name}",
                'success_url' => $successUrl,
                'callback_url' => $callbackUrl,

                // ✅ metadata to identify later in callback
                'metadata' => [
                    'payment_id' => (string)$payment->id,
                    'subscription_id' => (string)$subscription->id,
                    'user_id' => (string)$data['user_id'],
                    'plan_id' => (string)$plan->id,
                    'type' => (string)$type,
                ],
            ];
            $moyasar = app(MoyasarPaymentService::class);
            $res = $moyasar->sendPayment($payload);
            if (!$res['success']) {
                // rollback transaction
                throw new \RuntimeException('Moyasar payment creation failed');
            }

            // ✅ 5) update payment gateway id if returned
            // انت قلت sendPayment بيرجع ['url' => response->data] .. غالبًا ده object فيه invoice/id/url
            $gatewayData = $res['url'];

            // حاول تلتقط الحقول المعروفة (عدل حسب رد moyasar الحقيقي)
            $payment->update([
                'payment_id' => $gatewayData['id'] ?? ($gatewayData['invoice_id'] ?? null),
            ]);
            $payment->save();
            // return  $gatewayData['url'] ?? $gatewayData['invoice_url'];
            return [
                'subscription' => $subscription,
                'payment' => $payment,
                'checkout_url' => $gatewayData['url'] ?? $gatewayData['invoice_url'] ?? null,
                // 'gateway' => $gatewayData,
            ];
        });
    }

    public function update($id, array $data)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update($data);
        return $subscription;
    }

    public function delete($id)
    {
        return Subscription::destroy($id);
    }

    public function userSubscriptions()
    {
        $userId = auth()->id();
        return Subscription::with(['plan'])
            ->where('user_id', $userId)
            ->get();
    }
}
