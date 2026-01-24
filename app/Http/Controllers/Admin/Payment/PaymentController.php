<?php

namespace App\Http\Controllers\Admin\Payment;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Http\Requests\Payment\UpdatePaymentStatusRequest;

class PaymentController extends Controller
{
    public function __construct(private PaymentRepositoryInterface $payments) {}

    public function index(Request $request)
    {
        $rows = $this->payments->paginate($request->only(['status', 'payment_type', 'reference_id', 'phone']), $request->integer('per_page', 15));
        return PaymentResource::collection($rows);
    }

    public function show(int $id)
    {
        $row = $this->payments->findOrFail($id);
        return new PaymentResource($row);
    }

    public function store(StorePaymentRequest $request)
    {
        $row = $this->payments->create($request->validated());
        return response()->json([
            'status' => true,
            'data' => new PaymentResource($row),
        ], 201);
    }

    public function update(UpdatePaymentRequest $request, int $id)
    {
        $row = $this->payments->update($id, $request->validated());
        return response()->json([
            'status' => true,
            'data' => new PaymentResource($row),
        ]);
    }

    public function updateStatus(UpdatePaymentStatusRequest $request, int $id)
    {
        $row = $this->payments->updateStatus(
            $id,
            $request->validated()['status'],
            $request->validated()['payment_id'] ?? null
        );

        return response()->json([
            'status' => true,
            'data' => new PaymentResource($row),
        ]);
    }

    public function destroy(int $id)
    {
        $this->payments->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'Deleted successfully',
        ]);
    }

    public function providerPayments(Request $request)
    {
        $rows = $this->payments->providerPayments($request->only(['status', 'reference_id', 'phone']), $request->integer('per_page', 15));
        return PaymentResource::collection($rows);
    }
}
