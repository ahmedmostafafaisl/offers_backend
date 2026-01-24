<?php

namespace App\Interfaces\Payment;

use Illuminate\Http\Request;

interface MoyasarPaymentGatewayInterface
{
    public function sendPayment(array $data);

    public function callBack(Request $request);
    public function getSinglePayment(string $payment_id);
}
