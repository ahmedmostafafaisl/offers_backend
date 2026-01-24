<?php

namespace App\Interfaces\Payment;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function sendPayment(Request $request);

    public function callBack(Request $request);
    public function getPaymentDetails(string $payment_id);
}
