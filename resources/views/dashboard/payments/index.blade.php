@extends('layouts.dashboard')

@section('title', __('dashboard.payments'))
@section('page_title', __('dashboard.payments'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ __('dashboard.payments') }}</h5>
        <a href="{{ route('dashboard.payments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> {{ __('dashboard.create') }}
        </a>
    </div>

    <div class="card-soft p-3 mb-3">
        <form class="row g-2">
            <div class="col-md-3">
                <input name="reference_id" value="{{ request('reference_id') }}" class="form-control"
                    placeholder="{{ __('dashboard.reference_id') }}">
            </div>
            <div class="col-md-2">
                <input name="phone" value="{{ request('phone') }}" class="form-control"
                    placeholder="{{ __('dashboard.phone') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">{{ __('dashboard.all') }}</option>
                    <option value="pending" @selected(request('status') === 'pending')>pending</option>
                    <option value="paid" @selected(request('status') === 'paid')>paid</option>
                    <option value="failed" @selected(request('status') === 'failed')>failed</option>
                </select>
            </div>
            <div class="col-md-2">
                <input name="payment_type" value="{{ request('payment_type') }}" class="form-control"
                    placeholder="{{ __('dashboard.payment_type') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-light border w-100">{{ __('dashboard.filter') }}</button>
                <a class="btn btn-outline-secondary"
                    href="{{ route('dashboard.payments.index') }}">{{ __('dashboard.reset') }}</a>
            </div>
        </form>
    </div>

    <div class="card-soft p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('dashboard.user') }}</th>
                        <th>{{ __('dashboard.reference_id') }}</th>
                        <th>{{ __('dashboard.payment_type') }}</th>
                        <th>{{ __('dashboard.amount') }}</th>
                        <th>{{ __('dashboard.status') }}</th>
                        <th>{{ __('dashboard.phone') }}</th>
                        <th>{{ __('dashboard.created_at') }}</th>
                        <th class="text-end">{{ __('dashboard.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                             <td>
                                 @if($row->user)
                                     {{ $row->user->name ?? $row->user->email }}
                                 @else
                                     <span class="text-muted">—</span>
                                 @endif
                             </td>
                            <td class="fw-semibold">{{ $row->reference_id }}</td>
                            <td>{{ $row->payment_type }}</td>
                            <td>{{ number_format($row->amount, 2) }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $row->status === 'paid' ? 'success' : ($row->status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->created_at?->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-light border" href="{{ route('dashboard.payments.show', $row->id) }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-light border" href="{{ route('dashboard.payments.edit', $row->id) }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form class="d-inline" method="POST"
                                    action="{{ route('dashboard.payments.destroy', $row->id) }}"
                                    onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            {{ $rows->withQueryString()->links() }}
        </div>
    </div>
@endsection
