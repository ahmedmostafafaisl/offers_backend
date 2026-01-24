@php
    $title = 'Users';
    $columns = ['ID', 'Name', 'Email', 'Type', 'City', 'Role', 'Status', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Users',
    'createUrl' => route('dashboard.users.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.users.filters',
    'editUrl' => fn($r) => route('dashboard.users.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.users.destroy', $r->id),
    'renderRow' => function ($r) {
        $role = '-';
        if ($r->type === 'employee') {
            $role = $r->roles?->pluck('name')->implode(', ') ?: '-';
        }
        $status = '<span class="status-pill"><span class="status-dot"></span>Active</span>';
        return "
        <td>{$r->id}</td>
        <td>{$r->name}</td>
        <td>{$r->email}</td>
        <td>{$r->type}</td>
        <td>{$r->city}</td>
        <td>{$role}</td>
        <td>{$status}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
