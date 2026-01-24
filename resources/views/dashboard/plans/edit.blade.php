{{-- resources/views/dashboard/plans/edit.blade.php --}}
@extends('layouts.dashboard')

@section('title', __('dashboard.plans'))
@section('page_title', __('dashboard.edit_plan'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">{{ __('dashboard.edit_plan') }}</div>
            <div class="text-muted small">#{{ $row->id }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.plans.show', $row->id) }}" class="btn btn-light border">
                <i class="bi bi-eye"></i> {{ __('dashboard.show') }}
            </a>
            <a href="{{ route('dashboard.plans.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left"></i> {{ __('dashboard.back') }}
            </a>
        </div>
    </div>

    <div class="card-soft p-4">
        <form method="POST" action="{{ route('dashboard.plans.update', $row->id) }}">
            @csrf
            @method('PUT')

            @include('dashboard.plans.form', ['row' => $row])

            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-dark">
                    <i class="bi bi-check2"></i> {{ __('dashboard.update') }}
                </button>
                <a class="btn btn-light border" href="{{ route('dashboard.plans.show', $row->id) }}">
                    {{ __('dashboard.cancel') }}
                </a>
            </div>
        </form>
    </div>
@endsection
