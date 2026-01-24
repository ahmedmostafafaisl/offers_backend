{{-- resources/views/dashboard/plans/create.blade.php --}}
@extends('layouts.dashboard')

@section('title', __('dashboard.plans'))
@section('page_title', __('dashboard.create_plan'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">{{ __('dashboard.create_plan') }}</div>
            <div class="text-muted small">{{ __('dashboard.manage_plans') }}</div>
        </div>

        <a href="{{ route('dashboard.plans.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i> {{ __('dashboard.back') }}
        </a>
    </div>

    <div class="card-soft p-4">
        <form method="POST" action="{{ route('dashboard.plans.store') }}">
            @csrf

            @include('dashboard.plans.form')

            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-dark">
                    <i class="bi bi-check2"></i> {{ __('dashboard.save') }}
                </button>
                <a class="btn btn-light border" href="{{ route('dashboard.plans.index') }}">
                    {{ __('dashboard.cancel') }}
                </a>
            </div>
        </form>
    </div>
@endsection
