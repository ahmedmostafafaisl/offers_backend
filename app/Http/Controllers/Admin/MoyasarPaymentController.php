<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Payment\MoyasarPaymentGatewayInterface;

class MoyasarPaymentController extends Controller
{
    protected MoyasarPaymentGatewayInterface $paymentGateway;

    public function __construct(MoyasarPaymentGatewayInterface $paymentGateway)
    {

        $this->paymentGateway = $paymentGateway;
    }


    public function paymentProcess(Request $request)
    {
        $data = $request->all();
        return $this->paymentGateway->sendPayment($data);
    }

    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        $ok = $this->paymentGateway->callBack($request);

        $subscriptionId = $request->get('subscription_id');
        $referenceId    = $request->get('reference_id');

        if ($ok) {
            return redirect()->route('moyasar.payment.success', [
                'subscription_id' => $subscriptionId,
                'reference_id'    => $referenceId,
            ]);
        }

        return redirect()->route('moyasar.payment.failed', [
            'subscription_id' => $subscriptionId,
            'reference_id'    => $referenceId,
            'status'          => $request->get('status'),
        ]);
    }


    public function show(string $payment_id)
    {
        $res = $this->paymentGateway->getSinglePayment($payment_id);

        return response()->json($res, $res['status'] ?? 200);
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
