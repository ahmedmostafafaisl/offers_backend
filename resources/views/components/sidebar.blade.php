<aside class="dash-sidebar p-3">
    <div class="mb-3">
        <div class="brand-pill d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-grid-fill"></i>
                <strong>{{ __('dashboard.dashboard') }}</strong>
            </div>
            <i class="bi bi-list"></i>
        </div>
    </div>

    <div class="text-uppercase small fw-semibold text-muted mb-2">
        {{ __('dashboard.main_menu') }}
    </div>

    <nav class="nav flex-column gap-1">
        <a class="nav-link {{ request()->routeIs('dashboard.home') ? 'active' : '' }}"
            href="{{ route('dashboard.home') }}">
            <i class="bi bi-house me-2"></i> {{ __('dashboard.overview') }}
        </a>

        <a class="nav-link {{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}"
            href="{{ route('dashboard.users.index') }}">
            <i class="bi bi-people me-2"></i> {{ __('dashboard.users') }}
        </a>

        <a class="nav-link {{ request()->routeIs('dashboard.roles.*') ? 'active' : '' }}"
            href="{{ route('dashboard.roles.index') }}">
            <i class="bi bi-shield-lock me-2"></i> {{ __('dashboard.roles') }}
        </a>

        <a class="nav-link {{ request()->routeIs('dashboard.permissions.*') ? 'active' : '' }}"
            href="{{ route('dashboard.permissions.index') }}">
            <i class="bi bi-key me-2"></i> {{ __('dashboard.permissions') }}
        </a>

        <hr class="my-3">

        <a class="nav-link {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}"
            href="{{ route('dashboard.categories.index') }}">
            <i class="bi bi-tags me-2"></i> {{ __('dashboard.categories') }}
        </a>

        <a class="nav-link {{ request()->routeIs('dashboard.offers.*') ? 'active' : '' }}"
            href="{{ route('dashboard.offers.index') }}">
            <i class="bi bi-bag me-2"></i> {{ __('dashboard.offers') }}
        </a>

        <a class="nav-link {{ request()->routeIs('dashboard.sliders.*') ? 'active' : '' }}"
            href="{{ route('dashboard.sliders.index') }}">
            <i class="bi bi-images me-2"></i> {{ __('dashboard.sliders') }}
        </a>

        <hr class="my-3">

        <a class="nav-link {{ request()->routeIs('dashboard.plans.*') ? 'active' : '' }}"
            href="{{ route('dashboard.plans.index') }}">
            <i class="bi bi-box me-2"></i> {{ __('dashboard.plans') }}
        </a>


        <a class="nav-link {{ request()->routeIs('dashboard.subscriptions.*') ? 'active' : '' }}"
            href="{{ route('dashboard.subscriptions.index') }}">
            <i class="bi bi-credit-card-2-front me-2"></i> {{ __('dashboard.subscriptions') }}
        </a>

        <hr class="my-3">

        <a class="nav-link {{ request()->routeIs('dashboard.offer-reports.*') ? 'active' : '' }}"
            href="{{ route('dashboard.offer-reports.index') }}">
            <i class="bi bi-flag me-2"></i> {{ __('dashboard.offer_reports') }}
        </a>

        <a class="nav-link {{ request()->routeIs('dashboard.offer-complaints.*') ? 'active' : '' }}"
            href="{{ route('dashboard.offer-complaints.index') }}">
            <i class="bi bi-exclamation-circle me-2"></i> {{ __('dashboard.offer_complaints') }}
        </a>

        {{-- <hr class="my-3"> --}}

        {{-- <a class="nav-link {{ request()->routeIs('dashboard.pending-profile-verifications.*') ? 'active' : '' }}"
            href="{{ route('dashboard.pending-profile-verifications.index') }}">
            <i class="bi bi-patch-question me-2"></i> {{ __('dashboard.pending_verifications') }}
        </a> --}}

        <hr class="my-3">

        <a class="nav-link {{ request()->routeIs('dashboard.payments.*') ? 'active' : '' }}"
            href="{{ route('dashboard.payments.index') }}">
            <i class="bi bi-cash-stack me-2"></i> {{ __('dashboard.payments') }}
        </a>

    </nav>
</aside>
