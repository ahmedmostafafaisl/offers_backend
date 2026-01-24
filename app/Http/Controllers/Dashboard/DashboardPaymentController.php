<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\PaymentsRepositoryInterface;
use App\Http\Requests\Dashboard\Payment\StorePaymentRequest;
use App\Http\Requests\Dashboard\Payment\UpdatePaymentRequest;
use App\Http\Requests\Dashboard\Payment\UpdatePaymentStatusRequest;

class DashboardPaymentController extends Controller
{
    public function __construct(private PaymentsRepositoryInterface $payments) {}

    public function index(Request $request)
    {
        $rows = $this->payments->paginate(
            $request->only(['status', 'payment_type', 'reference_id', 'phone']),
            (int) $request->get('per_page', 10)
        );

        return view('dashboard.payments.index', compact('rows'));
    }

    public function show(int $id)
    {
        $row = $this->payments->findOrFail($id);
        return view('dashboard.payments.show', compact('row'));
    }

    public function create()
    {
        return view('dashboard.payments.create');
    }

    public function store(StorePaymentRequest $request)
    {
        $this->payments->create($request->validated());

        return redirect()
            ->route('dashboard.payments.index')
            ->with('success', 'Payment created successfully');
    }

    public function edit(int $id)
    {
        $row = $this->payments->findOrFail($id);
        return view('dashboard.payments.edit', compact('row'));
    }

    public function update(UpdatePaymentRequest $request, int $id)
    {
        $this->payments->update($id, $request->validated());

        return redirect()
            ->route('dashboard.payments.index')
            ->with('success', 'Payment updated successfully');
    }

    public function updateStatus(UpdatePaymentStatusRequest $request, int $id)
    {
        $this->payments->updateStatus(
            $id,
            $request->status,
            $request->payment_id
        );

        return back()->with('success', 'Status updated successfully');
    }

    public function destroy(int $id)
    {
        $this->payments->delete($id);

        return back()->with('success', 'Payment deleted successfully');
    }
}
