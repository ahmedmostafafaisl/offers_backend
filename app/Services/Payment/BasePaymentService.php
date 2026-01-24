<?php

namespace App\Services\Payment;

use Exception;
use Illuminate\Support\Facades\Http;

class BasePaymentService
{
    /**
     * Create a new class instance.
     */
    protected  $base_url;
    protected array $header;
    protected function buildRequest($method, $url, $data = null, $type = 'json'): \Illuminate\Http\JsonResponse
    {
        try {
            $data = is_array($data) ? $data : (is_null($data) ? [] : (array)$data);

            // ✅ ثابتة حسب طلبك (لكن انتبه للعملة حسب حساب التاجر)
            $data['currency'] = $data['currency'] ?? 'EGP';

            $data['customer'] = isset($data['customer']) && is_array($data['customer']) ? $data['customer'] : [];
            $data['customer']['phoneCountryCode'] = '+20';

            // ✅ eInvoiceDetails required fields
            $amount = (float) ($data['amount'] ?? 0);
            $qty    = (int) ($data['quantity'] ?? 1);
            $price  = (float) ($data['price'] ?? $amount);
            $total  = $price * $qty;

            // ✅ force required eInvoiceDetails
            $data['eInvoiceDetails'] = [
                'subtotal'            => $amount,
                'grandTotal'          => $amount,
                'extraChargesType'    => 'Amount',
                'invoiceDiscountType' => 'Amount',
                'eInvoiceItems'       => [
                    [
                        'price'       => $price,
                        'quantity'    => $qty,
                        'total'       => $total, // ✅ REQUIRED by Geidea
                        'description' => $data['description'] ?? 'Offer',
                    ]
                ],
            ];


            $response = \Illuminate\Support\Facades\Http::withHeaders($this->header)
                ->send($method, $this->base_url . $url, [
                    $type => $data
                ]);
            // dd($response->json());
            return response()->json([
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
