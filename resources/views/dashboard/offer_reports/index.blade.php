@php
    $title = 'Offer Reports';
    $columns = ['ID', 'User', 'Offer', 'Reason', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Offer Reports',
    'createUrl' => route('dashboard.offer-reports.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.offer_reports.filters',
    'editUrl' => fn($r) => route('dashboard.offer-reports.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.offer-reports.destroy', $r->id),
    'renderRow' => function ($r) {
        $reason = e(\Illuminate\Support\Str::limit($r->reason, 80));
        return "
        <td>{$r->id}</td>
        <td>#{$r->user_id}</td>
        <td>#{$r->offer_id}</td>
        <td>{$reason}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
