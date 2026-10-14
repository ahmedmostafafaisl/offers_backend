{{-- resources/views/dashboard/analytics/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', __('dashboard.analytics'))
@section('page_title', __('dashboard.analytics'))

@section('content')
    @php
        $fmt = fn($n) => number_format((float) $n);

        $cards = [
            ['key' => 'employee', 'label' => __('dashboard.employees'),  'icon' => 'bi-person-badge', 'color' => '#6c757d'],
            ['key' => 'provider', 'label' => __('dashboard.providers'),  'icon' => 'bi-shop',         'color' => '#f4c430'],
            ['key' => 'customer', 'label' => __('dashboard.customers'),  'icon' => 'bi-people',       'color' => '#5b2cff'],
            ['key' => 'total',    'label' => __('dashboard.new_users'),  'icon' => 'bi-person-plus',  'color' => '#198754'],
        ];

        $today = now();
        $quick = [
            __('dashboard.this_month')    => ['month' => $today->format('Y-m')],
            __('dashboard.last_month')    => ['month' => $today->copy()->subMonthNoOverflow()->format('Y-m')],
            __('dashboard.last_3_months') => ['date_from' => $today->copy()->subMonthsNoOverflow(2)->startOfMonth()->toDateString(), 'date_to' => $today->toDateString()],
            __('dashboard.this_year')     => ['date_from' => $today->copy()->startOfYear()->toDateString(), 'date_to' => $today->toDateString()],
            __('dashboard.last_12_months') => [],
        ];
    @endphp

    {{-- Header + filters --}}
    <div class="card-soft p-3 mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="fw-semibold">{{ __('dashboard.analytics_subtitle') }}</div>
                <div class="text-muted small">
                    {{ __('dashboard.period') }}:
                    <span dir="ltr">{{ $from->format('Y-m-d') }} → {{ $to->format('Y-m-d') }}</span>
                </div>
            </div>

            <form method="GET" action="{{ route('dashboard.analytics') }}" class="row g-2 align-items-end" id="analyticsFilter">
                <div class="col-auto">
                    <label class="form-label small mb-1">{{ __('dashboard.month') }}</label>
                    <input type="month" name="month" id="fMonth" class="form-control form-control-sm"
                           value="{{ request('month') }}">
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">{{ __('dashboard.date_from') }}</label>
                    <input type="date" name="date_from" id="fFrom" class="form-control form-control-sm"
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">{{ __('dashboard.date_to') }}</label>
                    <input type="date" name="date_to" id="fTo" class="form-control form-control-sm"
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-auto d-flex gap-2">
                    <button class="btn btn-dark btn-sm"><i class="bi bi-funnel"></i> {{ __('dashboard.apply') }}</button>
                    <a href="{{ route('dashboard.analytics') }}" class="btn btn-light border btn-sm">{{ __('dashboard.reset') }}</a>
                </div>
            </form>
        </div>

        @if ($errors->any())
            <div class="text-danger small mt-2">{{ $errors->first() }}</div>
        @endif

        <div class="d-flex flex-wrap gap-2 mt-3">
            @foreach ($quick as $label => $params)
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('dashboard.analytics', $params) }}">{{ $label }}</a>
            @endforeach
            <span class="text-muted small align-self-center">{{ __('dashboard.filter_hint') }}</span>
        </div>
    </div>

    {{-- KPI cards: users by type --}}
    <div class="row g-3 mb-3">
        @foreach ($cards as $c)
            @php
                $k = $kpis[$c['key']];
                $g = $k['growth'];
            @endphp
            <div class="col-md-3 col-6">
                <div class="card-soft p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ $c['label'] }}</div>
                            <div class="h3 mb-1">{{ $fmt($k['count']) }}</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:{{ $c['color'] }}22;color:{{ $c['color'] }};">
                            <i class="bi {{ $c['icon'] }}"></i>
                        </div>
                    </div>

                    <div class="small">
                        @if ($g === null)
                            <span class="text-muted">{{ __('dashboard.no_previous_data') }}</span>
                        @else
                            <span class="{{ $g >= 0 ? 'text-success' : 'text-danger' }}" dir="ltr">
                                <i class="bi {{ $g >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i>
                                {{ $g > 0 ? '+' : '' }}{{ $g }}%
                            </span>
                            <span class="text-muted">{{ __('dashboard.vs_previous_period') }}</span>
                        @endif
                    </div>
                    <div class="text-muted small mt-1">{{ __('dashboard.all_time') }}: <strong>{{ $fmt($k['all_time']) }}</strong></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Management metrics --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="card-soft p-3 h-100">
                <div class="text-muted small">{{ __('dashboard.active_subscriptions') }}</div>
                <div class="h4 mb-0">{{ $fmt($extra['active_subscriptions']) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card-soft p-3 h-100">
                <div class="text-muted small">{{ __('dashboard.provider_subscription_rate') }}</div>
                <div class="h4 mb-0" dir="ltr">{{ $extra['provider_sub_rate'] === null ? '—' : $extra['provider_sub_rate'] . '%' }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card-soft p-3 h-100">
                <div class="text-muted small">{{ __('dashboard.revenue') }}</div>
                <div class="h4 mb-0">{{ number_format($extra['revenue'], 2) }} <small class="text-muted">{{ __('dashboard.currency') }}</small></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card-soft p-3 h-100">
                <div class="text-muted small">{{ __('dashboard.new_offers') }}</div>
                <div class="h4 mb-0">{{ $fmt($extra['offers']) }}</div>
            </div>
        </div>
    </div>

    @if ($chartLimited)
        <div class="alert alert-light border small py-2">{{ __('dashboard.chart_limited') }}</div>
    @endif

    {{-- Charts --}}
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card-soft p-3 h-100">
                <div class="fw-semibold mb-2">{{ __('dashboard.registrations_trend') }}</div>
                <div style="position:relative;height:320px;"><canvas id="chartRegistrations"></canvas></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-soft p-3 h-100">
                <div class="fw-semibold mb-2">{{ __('dashboard.users_by_type') }}</div>
                <div style="position:relative;height:320px;"><canvas id="chartDistribution"></canvas></div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-soft p-3 h-100">
                <div class="fw-semibold mb-2">{{ __('dashboard.cumulative_growth') }}</div>
                <div style="position:relative;height:300px;"><canvas id="chartCumulative"></canvas></div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-soft p-3 h-100">
                <div class="fw-semibold mb-2">{{ __('dashboard.growth_rate') }}</div>
                <div style="position:relative;height:300px;"><canvas id="chartGrowth"></canvas></div>
            </div>
        </div>

        <div class="col-12">
            <div class="card-soft p-3">
                <div class="fw-semibold mb-2">{{ __('dashboard.revenue_and_offers') }}</div>
                <div style="position:relative;height:300px;"><canvas id="chartRevenue"></canvas></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            // ---- filter UX: month and date range are mutually exclusive ----
            const fMonth = document.getElementById('fMonth');
            const fFrom = document.getElementById('fFrom');
            const fTo = document.getElementById('fTo');
            fMonth.addEventListener('change', () => { if (fMonth.value) { fFrom.value = ''; fTo.value = ''; } });
            [fFrom, fTo].forEach(el => el.addEventListener('change', () => { if (el.value) fMonth.value = ''; }));

            // ---- charts ----
            const labels = @json($charts['labels']);
            const reg = @json($charts['registrations']);
            const cum = @json($charts['cumulative']);
            const growth = @json($charts['growthRate']);
            const dist = @json($charts['distribution']);
            const revenue = @json($charts['revenue']);
            const offers = @json($charts['offers']);

            const T = {
                customer: @json(__('dashboard.customers')),
                provider: @json(__('dashboard.providers')),
                employee: @json(__('dashboard.employees')),
                revenue: @json(__('dashboard.revenue')),
                offers: @json(__('dashboard.new_offers')),
            };
            const C = { customer: '#5b2cff', provider: '#f4c430', employee: '#6c757d', revenue: '#198754', offers: '#0dcaf0' };

            Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
            Chart.defaults.maintainAspectRatio = false;
            const intAxis = { beginAtZero: true, ticks: { precision: 0 } };

            // 1) new registrations per day/month (stacked bars)
            new Chart(document.getElementById('chartRegistrations'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: T.customer, data: reg.customer, backgroundColor: C.customer, stack: 's' },
                        { label: T.provider, data: reg.provider, backgroundColor: C.provider, stack: 's' },
                        { label: T.employee, data: reg.employee, backgroundColor: C.employee, stack: 's' },
                    ],
                },
                options: { scales: { x: { stacked: true }, y: { ...intAxis, stacked: true } }, plugins: { legend: { position: 'bottom' } } },
            });

            // 2) distribution by type in the selected period
            new Chart(document.getElementById('chartDistribution'), {
                type: 'doughnut',
                data: {
                    labels: [T.customer, T.provider, T.employee],
                    datasets: [{ data: [dist.customer, dist.provider, dist.employee], backgroundColor: [C.customer, C.provider, C.employee] }],
                },
                options: { plugins: { legend: { position: 'bottom' } } },
            });

            // 3) cumulative growth (customers vs providers)
            new Chart(document.getElementById('chartCumulative'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        { label: T.customer, data: cum.customer, borderColor: C.customer, backgroundColor: C.customer + '22', fill: true, tension: .3 },
                        { label: T.provider, data: cum.provider, borderColor: C.provider, backgroundColor: C.provider + '22', fill: true, tension: .3 },
                    ],
                },
                options: { scales: { y: intAxis }, plugins: { legend: { position: 'bottom' } } },
            });

            // 4) growth rate vs previous bucket (%)
            new Chart(document.getElementById('chartGrowth'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        { label: T.customer, data: growth.customer, borderColor: C.customer, tension: .3, spanGaps: true },
                        { label: T.provider, data: growth.provider, borderColor: C.provider, tension: .3, spanGaps: true },
                    ],
                },
                options: {
                    scales: { y: { ticks: { callback: v => v + '%' } } },
                    plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: c => c.dataset.label + ': ' + c.formattedValue + '%' } } },
                },
            });

            // 5) revenue (bars, left axis) + new offers (line, right axis)
            new Chart(document.getElementById('chartRevenue'), {
                data: {
                    labels,
                    datasets: [
                        { type: 'bar', label: T.revenue, data: revenue, backgroundColor: C.revenue, yAxisID: 'y' },
                        { type: 'line', label: T.offers, data: offers, borderColor: C.offers, tension: .3, yAxisID: 'y1' },
                    ],
                },
                options: {
                    scales: {
                        y: { beginAtZero: true, position: 'left' },
                        y1: { ...intAxis, position: 'right', grid: { drawOnChartArea: false } },
                    },
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        })();
    </script>
@endpush
