@php
    $title = 'Permissions';
    $columns = ['ID', 'Name', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Permissions',
    'createUrl' => route('dashboard.permissions.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => null,
    'editUrl' => fn($r) => route('dashboard.permissions.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.permissions.destroy', $r->id),
    'renderRow' => function ($r) {
        return "
        <td>{$r->id}</td>
        <td>{$r->name}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
