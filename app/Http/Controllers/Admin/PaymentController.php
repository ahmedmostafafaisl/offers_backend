<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Payment\PaymentGatewayInterface;

class PaymentController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {

        $this->paymentGateway = $paymentGateway;
    }


    public function paymentProcess(Request $request)
    {
        // dd($request);
        return $this->paymentGateway->sendPayment($request);
    }

    public function details(string $payment_id)
    {
        return $this->paymentGateway->getPaymentDetails($payment_id);
    }


    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        return $this->paymentGateway->callBack($request);
    }



    public function success(Request $request)
    {

        return view('dashboard.payments.payment-success');
    }
    public function failed(Request $request)
    {

        return view('dashboard.payments.payment-failed');
    }
}
