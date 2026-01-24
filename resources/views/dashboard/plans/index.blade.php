{{-- resources/views/dashboard/plans/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', __('dashboard.plans'))
@section('page_title', __('dashboard.plans'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">{{ __('dashboard.plans') }}</div>
            <div class="text-muted small">{{ __('dashboard.manage_plans') }}</div>
        </div>

        <a href="{{ route('dashboard.plans.create') }}" class="btn btn-dark">
            <i class="bi bi-plus"></i> {{ __('dashboard.create') }}
        </a>
    </div>

    {{-- filters --}}
    <div class="card-soft p-3 mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">{{ __('dashboard.search') }}</label>
                <input name="q" class="form-control" value="{{ request('q') }}" placeholder="{{ __('dashboard.search') }}">
            </div>

            <div class="col-md-3">
                <button class="btn btn-light border w-100">
                    <i class="bi bi-search"></i> {{ __('dashboard.filter') }}
                </button>
            </div>

            <div class="col-md-3">
                <a href="{{ route('dashboard.plans.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-clockwise"></i> {{ __('dashboard.reset') }}
                </a>
            </div>
        </form>
    </div>

    <div class="card-soft p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:80px;">#</th>
                        <th>{{ __('dashboard.name') }}</th>
                        <th style="width:180px;">{{ __('dashboard.monthly_price') }}</th>
                        <th style="width:180px;">{{ __('dashboard.annually_price') }}</th>
                        <th style="width:140px;">{{ __('dashboard.features') }}</th>
                        <th style="width:220px;" class="text-end">{{ __('dashboard.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="text-muted">#{{ $row->id }}</td>
                            <td class="fw-semibold">{{ $row->name }}</td>

                            <td>{{ number_format((float) $row->monthly_price, 2) }}</td>
                            <td>{{ number_format((float) $row->annually_price, 2) }}</td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $row->features_count ?? ($row->features?->count() ?? 0) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('dashboard.plans.show', $row->id) }}" class="btn btn-light border btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.plans.edit', $row->id) }}" class="btn btn-light border btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form method="POST" action="{{ route('dashboard.plans.destroy', $row->id) }}"
                                        onsubmit="return confirm('{{ __('dashboard.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                {{ __('dashboard.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($rows, 'links'))
            <div class="p-3">
                {{ $rows->links() }}
            </div>
        @endif
    </div>
@endsection
