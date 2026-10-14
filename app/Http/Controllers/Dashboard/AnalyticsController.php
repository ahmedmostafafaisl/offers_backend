<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Management dashboard: users growth (employees / providers / customers),
 * monthly (or daily) influx, cumulative growth, growth rate, subscriptions, revenue and offers.
 */
class AnalyticsController extends Controller
{
    private const USER_TYPES = ['employee', 'provider', 'customer'];

    /** Max number of monthly buckets drawn in the charts. */
    private const MAX_MONTHS = 36;

    /** Ranges up to this many days are drawn per day, longer ranges per month. */
    private const DAILY_MAX_DAYS = 62;

    public function index(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        [$from, $to, $mode] = $this->resolvePeriod($request);

        // -------- granularity + chart range --------
        $days = (int) $from->startOfDay()->diffInDays($to->startOfDay()) + 1;
        $granularity = $days <= self::DAILY_MAX_DAYS ? 'day' : 'month';

        $chartFrom = $from;
        $chartLimited = false;
        if ($granularity === 'month') {
            $earliest = $to->startOfMonth()->subMonths(self::MAX_MONTHS - 1);
            if ($from->lt($earliest)) {
                $chartFrom = $earliest;
                $chartLimited = true;
            }
        }

        [$keys, $labels] = $this->buckets($chartFrom, $to, $granularity);

        // -------- KPI: counts in period vs previous period --------
        [$prevFrom, $prevTo] = $this->previousPeriod($from, $to, $mode);

        $current = $this->countByType($from, $to);
        $previous = $this->countByType($prevFrom, $prevTo);
        $allTime = $this->countByType();

        $kpis = [];
        foreach (self::USER_TYPES as $type) {
            $kpis[$type] = [
                'count' => $current[$type],
                'previous' => $previous[$type],
                'growth' => $this->growth($current[$type], $previous[$type]),
                'all_time' => $allTime[$type],
            ];
        }
        $kpis['total'] = [
            'count' => array_sum($current),
            'previous' => array_sum($previous),
            'growth' => $this->growth(array_sum($current), array_sum($previous)),
            'all_time' => array_sum($allTime),
        ];

        // -------- charts: registrations per bucket --------
        $expr = $this->bucketExpr('users.created_at', $granularity);

        $rows = User::query()
            ->whereBetween('created_at', [$chartFrom, $to])
            ->selectRaw("$expr as bucket, type, COUNT(*) as total")
            ->groupBy('bucket', 'type')
            ->get();

        $series = array_fill_keys(self::USER_TYPES, array_fill_keys($keys, 0));
        foreach ($rows as $r) {
            if (isset($series[$r->type][$r->bucket])) {
                $series[$r->type][$r->bucket] = (int) $r->total;
            }
        }

        // cumulative growth (customers / providers): users that existed before the chart range + running sum
        $before = $this->countByType(null, $chartFrom->subSecond());
        $cumulative = [];
        foreach (['customer', 'provider'] as $type) {
            $running = $before[$type];
            $cumulative[$type] = array_map(function ($v) use (&$running) {
                return $running += $v;
            }, array_values($series[$type]));
        }

        // growth rate vs previous bucket (%), null when the previous bucket is 0
        $growthRate = [];
        foreach (['customer', 'provider'] as $type) {
            $vals = array_values($series[$type]);
            $growthRate[$type] = [];
            foreach ($vals as $i => $v) {
                $growthRate[$type][] = ($i > 0 && $vals[$i - 1] > 0)
                    ? round((($v - $vals[$i - 1]) / $vals[$i - 1]) * 100, 1)
                    : null;
            }
        }

        // -------- revenue + offers per bucket --------
        $paymentExpr = $this->bucketExpr('payments.created_at', $granularity);
        $revenueRows = Payment::query()
            ->where('status', 'paid')
            ->whereBetween('created_at', [$chartFrom, $to])
            ->selectRaw("$paymentExpr as bucket, SUM(amount) as total")
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        $offerExpr = $this->bucketExpr('offers.created_at', $granularity);
        $offerRows = Offer::query()
            ->whereBetween('created_at', [$chartFrom, $to])
            ->selectRaw("$offerExpr as bucket, COUNT(*) as total")
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        $revenueSeries = array_map(fn($k) => round((float) ($revenueRows[$k] ?? 0), 2), $keys);
        $offersSeries = array_map(fn($k) => (int) ($offerRows[$k] ?? 0), $keys);

        // -------- extra management metrics --------
        $activeSubs = Subscription::query()
            ->where('is_active', true)
            ->whereDate('expiration_date', '>=', now()->toDateString());

        $activeSubscriptions = (clone $activeSubs)->count();
        $providersWithSub = (clone $activeSubs)->distinct()->count('user_id');
        $providerSubRate = $allTime['provider'] > 0
            ? round(($providersWithSub / $allTime['provider']) * 100, 1)
            : null;

        $revenueInPeriod = (float) Payment::query()
            ->where('status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');

        $offersInPeriod = Offer::query()->whereBetween('created_at', [$from, $to])->count();

        return view('dashboard.analytics.index', [
            'from' => $from,
            'to' => $to,
            'mode' => $mode,
            'granularity' => $granularity,
            'chartLimited' => $chartLimited,
            'kpis' => $kpis,
            'extra' => [
                'active_subscriptions' => $activeSubscriptions,
                'provider_sub_rate' => $providerSubRate,
                'revenue' => $revenueInPeriod,
                'offers' => $offersInPeriod,
            ],
            'charts' => [
                'labels' => $labels,
                'registrations' => [
                    'customer' => array_values($series['customer']),
                    'provider' => array_values($series['provider']),
                    'employee' => array_values($series['employee']),
                ],
                'cumulative' => $cumulative,
                'growthRate' => $growthRate,
                'revenue' => $revenueSeries,
                'offers' => $offersSeries,
                'distribution' => [
                    'customer' => $current['customer'],
                    'provider' => $current['provider'],
                    'employee' => $current['employee'],
                ],
            ],
        ]);
    }

    /**
     * Priority: month > date range > default (last 12 months).
     *
     * @return array{0: CarbonImmutable, 1: CarbonImmutable, 2: string}
     */
    private function resolvePeriod(Request $request): array
    {
        if ($request->filled('month')) {
            $start = CarbonImmutable::createFromFormat('Y-m', $request->input('month'))->startOfMonth();

            return [$start, $start->endOfMonth(), 'month'];
        }

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $to = $request->filled('date_to')
                ? CarbonImmutable::parse($request->input('date_to'))->endOfDay()
                : CarbonImmutable::now()->endOfDay();

            $from = $request->filled('date_from')
                ? CarbonImmutable::parse($request->input('date_from'))->startOfDay()
                : $to->subMonths(11)->startOfMonth();

            return [$from, $to, 'range'];
        }

        return [
            CarbonImmutable::now()->subMonths(11)->startOfMonth(),
            CarbonImmutable::now()->endOfDay(),
            'default',
        ];
    }

    private function previousPeriod(CarbonImmutable $from, CarbonImmutable $to, string $mode): array
    {
        if ($mode === 'month') {
            $prevFrom = $from->subMonth()->startOfMonth();

            return [$prevFrom, $prevFrom->endOfMonth()];
        }

        $days = (int) $from->startOfDay()->diffInDays($to->startOfDay()) + 1;
        $prevTo = $from->subDay()->endOfDay();
        $prevFrom = $prevTo->subDays($days - 1)->startOfDay();

        return [$prevFrom, $prevTo];
    }

    /** @return array<string,int> employee/provider/customer => count (optionally between two dates) */
    private function countByType(?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $q = User::query()->selectRaw('type, COUNT(*) as total')->groupBy('type');

        if ($from) {
            $q->where('created_at', '>=', $from);
        }
        if ($to) {
            $q->where('created_at', '<=', $to);
        }

        $counts = $q->pluck('total', 'type');

        $out = [];
        foreach (self::USER_TYPES as $type) {
            $out[$type] = (int) ($counts[$type] ?? 0);
        }

        return $out;
    }

    /** % change, or null when there is nothing to compare with. */
    private function growth(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /** @return array{0: string[], 1: string[]} bucket keys + human labels */
    private function buckets(CarbonImmutable $from, CarbonImmutable $to, string $granularity): array
    {
        $keys = [];
        $labels = [];

        if ($granularity === 'day') {
            for ($d = $from->startOfDay(); $d->lte($to); $d = $d->addDay()) {
                $keys[] = $d->format('Y-m-d');
                $labels[] = $d->translatedFormat('d M');
            }
        } else {
            for ($m = $from->startOfMonth(); $m->lte($to); $m = $m->addMonth()) {
                $keys[] = $m->format('Y-m');
                $labels[] = $m->translatedFormat('M Y');
            }
        }

        return [$keys, $labels];
    }

    /** SQL expression that turns a datetime column into a 'Y-m' / 'Y-m-d' bucket key (MySQL, MariaDB, PostgreSQL, SQLite). */
    private function bucketExpr(string $column, string $granularity): string
    {
        $driver = DB::connection()->getDriverName();
        $day = $granularity === 'day';

        return match ($driver) {
            'sqlite' => "strftime('" . ($day ? '%Y-%m-%d' : '%Y-%m') . "', $column)",
            'pgsql' => "to_char($column, '" . ($day ? 'YYYY-MM-DD' : 'YYYY-MM') . "')",
            default => "DATE_FORMAT($column, '" . ($day ? '%Y-%m-%d' : '%Y-%m') . "')",
        };
    }
}
