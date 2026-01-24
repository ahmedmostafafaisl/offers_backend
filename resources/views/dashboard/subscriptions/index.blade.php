@php $title = 'Subscriptions';
$columns = ['ID', 'User', 'Plan', 'Type', 'Active', 'Start', 'Expire']; @endphp
@include('dashboard.crud.index', [
        'title' => $title,
        'breadcrumb' => 'Overview / Subscriptions',
        'createUrl' => route('dashboard.subscriptions.create'),
        'columns' => $columns,
        'rows' => $rows,
        'filtersView' => 'dashboard.subscriptions.filters',
        'editUrl' => fn($r) => route('dashboard.subscriptions.edit', $r->id),
        'deleteUrl' => fn($r) => route('dashboard.subscriptions.destroy', $r->id),
        'renderRow' => function ($r) {
            $active = $r->is_active ? '<span class="status-pill"><span class="status-dot"></span>Active</span>' : '<span class="badge text-bg-secondary">Inactive</span>';
            $user = $r->user?->name ?? '#' . $r->user_id;
            $plan = $r->plan?->name ?? '#' . $r->plan_id;
            return "
        <td>{$r->id}</td>
        <td>{$user}</td>
        <td>{$plan}</td>
        <td>{$r->type}</td>
        <td>{$active}</td>
        <td>{$r->start_date}</td>
        <td>{$r->expiration_date}</td>
       ";
        }
])
