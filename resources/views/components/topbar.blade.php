@php
    $locale = app()->getLocale();
@endphp

<header class="topbar px-4 py-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <div class="fw-semibold">@yield('page_title', __('dashboard.dashboard'))</div>
    </div>

    <div class="d-flex align-items-center gap-3">

        {{-- ✅ Language Switch Dropdown --}}
        <div class="dropdown">
            <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-translate"></i>
                <span class="small">{{ strtoupper($locale) }}</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ $locale === 'en' ? 'active' : '' }}"
                        href="{{ route('lang.switch', 'en') }}">
                        <span>English</span>
                        @if($locale === 'en') <i class="bi bi-check2"></i> @endif
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ $locale === 'ar' ? 'active' : '' }}"
                        href="{{ route('lang.switch', 'ar') }}">
                        <span>العربية</span>
                        @if($locale === 'ar') <i class="bi bi-check2"></i> @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- Refresh --}}
        <a class="btn btn-light border" href="{{ url()->current() }}">
            <i class="bi bi-arrow-clockwise"></i>
        </a>

        {{-- User Box --}}
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                style="width:36px;height:36px;">
                <i class="bi bi-person"></i>
            </div>
            <div class="small lh-1 text-start">
                <div class="fw-semibold">{{ auth()->user()->name ?? 'admin' }}</div>
                <div class="text-muted">{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</div>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger" type="submit" title="{{ __('dashboard.logout') }}">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</header>
