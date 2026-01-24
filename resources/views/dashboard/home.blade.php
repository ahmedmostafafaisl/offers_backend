@extends('layouts.dashboard')

@section('title', __('dashboard.dashboard'))
@section('page_title', __('dashboard.overview'))

@section('content')

    @php
        $usersCount = \App\Models\User::count();
        $offersCount = \App\Models\Offer::count();
        $categoriesCount = \App\Models\Category::count();
        $slidersCount = \App\Models\Slider::count();

        $paymentsCount = \App\Models\Payment::count();
        $paidCount = \App\Models\Payment::where('status', 'paid')->count();
        $pendingCount = \App\Models\Payment::where('status', 'pending')->count();
        $failedCount = \App\Models\Payment::where('status', 'failed')->count();
    @endphp

    <div class="row g-3">

        {{-- Existing cards --}}
        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">{{ __('dashboard.users') }}</div>
                        <div class="h4 mb-0">{{ $usersCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#efeaff;color:#5b2cff;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">{{ __('dashboard.offers') }}</div>
                        <div class="h4 mb-0">{{ $offersCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#efeaff;color:#5b2cff;">
                        <i class="bi bi-bag"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">{{ __('dashboard.categories') }}</div>
                        <div class="h4 mb-0">{{ $categoriesCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#efeaff;color:#5b2cff;">
                        <i class="bi bi-tags"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">{{ __('dashboard.sliders') }}</div>
                        <div class="h4 mb-0">{{ $slidersCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#efeaff;color:#5b2cff;">
                        <i class="bi bi-images"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payments Cards --}}
        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">{{ __('dashboard.payments') }}</div>
                        <div class="h4 mb-0">{{ $paymentsCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#fff6db;color:#b88900;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Paid</div>
                        <div class="h4 mb-0">{{ $paidCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#e8fff0;color:#0f7b3d;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="h4 mb-0">{{ $pendingCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#fff4e5;color:#b25a00;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-soft p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Failed</div>
                        <div class="h4 mb-0">{{ $failedCount }}</div>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#ffe9e9;color:#b10000;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card-soft p-4 mt-3">
        <h5 class="mb-1">{{ __('dashboard.welcome') }}</h5>
        <div class="text-muted">{{ __('dashboard.welcome_hint') }}</div>
    </div>
@endsection
