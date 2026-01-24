<?php

namespace App\Services\Payment\Moyasar;

use Exception;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\Payment\BasePaymentService;
use App\Interfaces\Payment\MoyasarPaymentGatewayInterface;

class MoyasarPaymentService extends BasePaymentService implements MoyasarPaymentGatewayInterface
{
    protected  $api_secret;
    public function __construct()
    {
        $this->base_url = env("MOYASAR_BASE_URL");
        $this->api_secret = env("MOYASAR_SECRET_KEY");
        $this->header = [
            'accept' => 'application/json',
            "Content-Type" => "application/json",
            "Authorization" => "Basic " . base64_encode("$this->api_secret:''"),
        ];
    }

    public function sendPayment($data)
    {
        //validate data before sending it
        $data['success_url'] = $data['success_url'] ??  route('moyasar.payment.callback');
        $response = $this->buildRequest('POST', '/v1/invoices', $data);
        //handel payment response data and return it
        if ($response->getData(true)['success']) {

            return ['success' => true, 'url' => $response->getData(true)['data']];
        }
        return ['success' => false, 'url' => $response];
    }


    public function callBack(Request $request): JsonResponse
    {
        // ✅ log raw callback
        Storage::put('moyasar_response.json', json_encode($request->all(), JSON_PRETTY_PRINT));

        $status         = $request->get('status');          // paid / failed / ...
        $invoiceId      = $request->get('invoice_id');      // invoice id
        $transactionId  = $request->get('id');              // transaction/payment id
        $subscriptionId = $request->get('subscription_id'); // your subscription id
        $referenceId    = $request->get('reference_id');    // your payments.reference_id

        // ✅ basic validation
        if (!$subscriptionId || !$referenceId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing subscription_id or reference_id',
            ], 422);
        }

        $payment = Payment::where('reference_id', $referenceId)->firstOrFail();
        $subscription = $payment->subscription; // ✅

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'subscription_id' => $subscriptionId,
            ], 404);
        }

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
                'reference_id' => $referenceId,
            ], 404);
        }

        // ✅ إذا payment لا يخص نفس user (اختياري لكنه مهم)
        if ($payment->user_id && $subscription->user_id && $payment->user_id != $subscription->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Payment does not match subscription user',
            ], 409);
        }

        // ✅ update only if paid
        if ($status === 'paid') {
            DB::transaction(function () use ($payment, $subscription, $invoiceId, $transactionId) {

                // idempotent: لو اتدفعت قبل كده خلاص
                if ($payment->status !== 'paid') {
                    $payment->update([
                        'status' => 'paid',
                        // خزن أي معرف مهم من moyasar
                        'payment_id' => $invoiceId ?: $transactionId,
                    ]);
                }

                if ((int)$subscription->is_active !== 1) {
                    $subscription->update([
                        'is_active' => 1,
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment paid, subscription activated',
                'payment' => [
                    'id' => $payment->id,
                    'reference_id' => $payment->reference_id,
                    'status' => $payment->status,
                    'payment_id' => $payment->payment_id,
                ],
                'subscription' => [
                    'id' => $subscription->id,
                    'is_active' => (int)$subscription->is_active,
                ],
            ]);
        }

        // ✅ if not paid -> mark failed (اختياري)
        if (in_array($status, ['failed', 'canceled', 'expired'])) {
            if ($payment->status !== 'paid') {
                $payment->update([
                    'status' => 'failed',
                    'payment_id' => $invoiceId ?: $transactionId,
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment not paid',
            'status' => $status,
        ], 200);
    }

    public function getSinglePayment(string $paymentId): array
    {
        try {
            $response = Http::withHeaders($this->header)
                ->get($this->base_url . "/v1/payments/{$paymentId}");
            // dd($response->json());
            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'status'  => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}
