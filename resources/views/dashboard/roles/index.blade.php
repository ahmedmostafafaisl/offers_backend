@php
    $title = 'Roles';
    $columns = ['ID', 'Name', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Roles',
    'createUrl' => route('dashboard.roles.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => null,
    'editUrl' => fn($r) => route('dashboard.roles.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.roles.destroy', $r->id),
    'renderRow' => function ($r) {
        return "
        <td>{$r->id}</td>
        <td>{$r->name}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
