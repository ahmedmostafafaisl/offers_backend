@php
    $title = __('dashboard.plans');

    $columns = [
        '#',
        __('dashboard.name'),
        __('dashboard.quarterly_price'),
        __('dashboard.semi_annual_price'),
        __('dashboard.annual_price'),
        __('dashboard.features'),
        __('dashboard.created_at'),
    ];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => __('dashboard.overview') . ' / ' . __('dashboard.plans'),
    'createUrl' => route('dashboard.plans.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.plans.filters',

    // لو عندك show route
    'showUrl' => fn($r) => route('dashboard.plans.show', $r->id),
    'editUrl' => fn($r) => route('dashboard.plans.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.plans.destroy', $r->id),

    'renderRow' => function ($r) {
        $featuresCount = $r->features_count ?? ($r->features?->count() ?? 0);

        return "
            <td>{$r->id}</td>
            <td class='fw-semibold'>" . e($r->name) . "</td>
            <td>" . number_format((float) $r->quarterly_price, 2) . "</td>
            <td>" . number_format((float) $r->semi_annual_price, 2) . "</td>
            <td>" . number_format((float) $r->annual_price, 2) . "</td>
            <td><span class='badge bg-light text-dark border'>{$featuresCount}</span></td>
            <td>" . optional($r->created_at)->format('Y-m-d') . "</td>
        ";
    }
])
