@php $title = 'Plan Features';
$columns = ['ID', 'Plan', 'Name', 'Created']; @endphp
@include('dashboard.crud.index', [
        'title' => $title,
        'breadcrumb' => 'Overview / Plan Features',
        'createUrl' => route('dashboard.plan-features.create'),
        'columns' => $columns,
        'rows' => $rows,
        'filtersView' => 'dashboard.plan_features.filters',
        'editUrl' => fn($r) => route('dashboard.plan-features.edit', $r->id),
        'deleteUrl' => fn($r) => route('dashboard.plan-features.destroy', $r->id),
        'renderRow' => function ($r) {
            return "
        <td>{$r->id}</td>
        <td>#{$r->plan_id}</td>
        <td>{$r->name}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
       ";
        }
])
