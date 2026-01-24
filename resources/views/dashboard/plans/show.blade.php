{{-- resources/views/dashboard/plans/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', __('dashboard.plans'))
@section('page_title', __('dashboard.plan_details'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">{{ __('dashboard.plan_details') }}</div>
            <div class="text-muted small">#{{ $row->id }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.plans.edit', $row->id) }}" class="btn btn-light border">
                <i class="bi bi-pencil"></i> {{ __('dashboard.edit') }}
            </a>

            <a href="{{ route('dashboard.plans.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left"></i> {{ __('dashboard.back') }}
            </a>

            <form method="POST" action="{{ route('dashboard.plans.destroy', $row->id) }}"
                onsubmit="return confirm('{{ __('dashboard.confirm_delete') }}')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> {{ __('dashboard.delete') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Plan info --}}
    <div class="card-soft p-4 mb-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">{{ __('dashboard.name') }}</div>
                <div class="fw-semibold">{{ $row->name }}</div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">{{ __('dashboard.monthly_price') }}</div>
                <div class="fw-semibold">{{ number_format((float) $row->monthly_price, 2) }}</div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">{{ __('dashboard.annually_price') }}</div>
                <div class="fw-semibold">{{ number_format((float) $row->annually_price, 2) }}</div>
            </div>

            <div class="col-md-6">
                <div class="text-muted small">{{ __('dashboard.created_at') }}</div>
                <div class="fw-semibold">{{ optional($row->created_at)->format('Y-m-d H:i') }}</div>
            </div>

            <div class="col-md-6">
                <div class="text-muted small">{{ __('dashboard.updated_at') }}</div>
                <div class="fw-semibold">{{ optional($row->updated_at)->format('Y-m-d H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Features --}}
    <div class="card-soft p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">{{ __('dashboard.plan_features') }}</h6>
            <span class="badge bg-light text-dark border">
                {{ $row->features?->count() ?? 0 }}
            </span>
        </div>

        @if(($row->features?->count() ?? 0) > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px;">#</th>
                            <th>{{ __('dashboard.feature_name') }}</th>
                            <th>{{ __('dashboard.feature_description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($row->features as $i => $f)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $f->name }}</td>
                                <td class="text-muted">{{ $f->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-muted">{{ __('dashboard.no_data') }}</div>
        @endif
    </div>
@endsection
