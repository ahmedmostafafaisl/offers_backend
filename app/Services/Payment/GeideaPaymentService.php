<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Payment\BasePaymentService;
use App\Interfaces\Payment\PaymentGatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;


class GeideaPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    private mixed $api_password;
    private mixed $api_key;

    protected $data;

    public function __construct()
    {
        $this->base_url = env("GEIDEA_BASE_URL");
        $this->api_key = env("GEIDEA_API_KEY");
        $this->api_password = env("GEIDEA_API_PASSWORD");
        $this->header = [
            'accept' => 'application/json',
            "Content-Type" => "application/json",
            "Authorization" => "Basic " . base64_encode("$this->api_key:$this->api_password"),
        ];
    }

    public function sendPayment(Request $request): array
    {
        $data = $request->all();
        // dd($data);
        $data["eInvoiceDetails"] = [
            "extraChargesType" => "Amount",
            "invoiceDiscountType" => "Amount"
        ];
        $response = $this->buildRequest('POST', '/payment-intent/api/v1/direct/eInvoice', $data);

        //handel payment response data and return it
        if ($response->getData(true)['success']) {

            return ['success' => true, 'url' => $response->getData(true)['data']['paymentIntent']['link'], 'item_id' => $response->getData(true)['data']['paymentIntent']['paymentIntentId']];
        }
        return ['success' => false, 'url' => route('payment.failed')];
    }

    public function callBack(Request $request): bool
    {
        $response = $request->all();
        Storage::put('geidea_response.json', json_encode($response));
        if (isset($response['order']['status']) && $response['order']['status'] === 'Success' && $response['order']['detailedStatus'] === 'Paid') {
            //save order and return true
            return true;
        }
        return false;
    }

    // get payment status
    public function getPaymentDetails(string $payment_id)
    {
        try {
            // ✅ headers (Basic Auth)
            $headers = [
                'accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->api_key . ':' . $this->api_password),
            ];

            // ✅ endpoint
            $url = rtrim($this->base_url, '/') . '/payment-intent/api/v1/direct/eInvoice/' . urlencode($payment_id);

            // ✅ request
            $response = Http::withHeaders($headers)->get($url);
            return $response->json();
            return response()->json([
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status'  => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
