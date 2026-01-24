@extends('layouts.dashboard')

@section('title', __('dashboard.payments'))
@section('page_title', __('dashboard.details') . ' - ' . __('dashboard.payments'))

@section('content')
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card-soft p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-semibold">{{ $row->reference_id }}</div>
                    <span
                        class="badge bg-{{ $row->status === 'paid' ? 'success' : ($row->status === 'failed' ? 'danger' : 'warning') }}">
                        {{ $row->status }}
                    </span>
                </div>

                <div class="small text-muted mb-3">#{{ $row->id }}</div>
                 <div class="mb-2"><b>{{ __('dashboard.user') }}:</b> {{ $row->user->name ?? $row->user->email }}</div>
                <div class="mb-2"><b>{{ __('dashboard.payment_type') }}:</b> {{ $row->payment_type }}</div>
                <div class="mb-2"><b>{{ __('dashboard.amount') }}:</b> {{ number_format($row->amount, 2) }}</div>
                <div class="mb-2"><b>{{ __('dashboard.phone') }}:</b> {{ $row->phone }}</div>
                <div class="mb-2"><b>{{ __('dashboard.payment_id') }}:</b> {{ $row->payment_id }}</div>

                <hr>

                {{-- quick status update --}}
                <form method="POST" action="{{ route('dashboard.payments.status', $row->id) }}" class="row g-2">
                    @csrf
                    @method('PATCH')

                    <div class="col-6">
                        <select name="status" class="form-select">
                            <option value="pending" @selected($row->status === 'pending')>pending</option>
                            <option value="paid" @selected($row->status === 'paid')>paid</option>
                            <option value="failed" @selected($row->status === 'failed')>failed</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <input name="payment_id" class="form-control" value="{{ $row->payment_id }}"
                            placeholder="payment_id">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary w-100">{{ __('dashboard.update') }}</button>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-soft p-4">
                <h6 class="mb-3">{{ __('dashboard.payment_items') }}</h6>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                 <th>{{ __('dashboard.name') }}</th>
                                <th>{{ __('dashboard.item_number') }}</th>
                                <th>{{ __('dashboard.price') }}</th>
                                <th>{{ __('dashboard.quantity') }}</th>
                                <th>{{ __('dashboard.total_amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($row->items as $it)
                                <tr>
                                    <td>{{ $it->id }}</td>
                                     <td>{{ $it->name }}</td>
                                    <td>{{ $it->item_number }}</td>
                                    <td>{{ number_format($it->price, 2) }}</td>
                                    <td>{{ $it->quantity }}</td>
                                    <td class="fw-semibold">{{ number_format($it->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end fw-semibold">
                {{ __('dashboard.total_price') }} {{ number_format($row->items->sum('total_amount'), 2) }}
                </div>
            </div>
        </div>
    </div>
@endsection
