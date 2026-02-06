@php
    $title = __('dashboard.payments');

    $columns = [
        '#',
        __('dashboard.user'),
        __('dashboard.reference_id'),
        __('dashboard.payment_type'),
        __('dashboard.amount'),
        __('dashboard.status'),
        __('dashboard.phone'),
        __('dashboard.created_at'),
    ];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => __('dashboard.overview') . ' / ' . __('dashboard.payments'),
    'createUrl' => route('dashboard.payments.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.payments.filters',
    // لو عندك show route
    'showUrl' => fn($r) => route('dashboard.plans.show', $r->id),
    'editUrl' => fn($r) => route('dashboard.payments.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.payments.destroy', $r->id),

    'renderRow' => function ($r) {
        $user = $r->user ? e($r->user->name ?? $r->user->email) : '<span class="text-muted">—</span>';

        $badge = $r->status === 'paid'
            ? 'success'
            : ($r->status === 'failed' ? 'danger' : 'warning');

        $status = '<span class="badge bg-' . $badge . '">' . e($r->status) . '</span>';

        return "
            <td>{$r->id}</td>
            <td>{$user}</td>
            <td class='fw-semibold'>" . e($r->reference_id) . "</td>
            <td>" . e($r->payment_type) . "</td>
            <td>" . number_format((float) $r->amount, 2) . "</td>
            <td>{$status}</td>
            <td>" . e($r->phone ?? '') . "</td>
            <td>" . optional($r->created_at)->format('Y-m-d') . "</td>
        ";
    }
])
