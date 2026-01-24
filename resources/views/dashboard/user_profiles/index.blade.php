@php $title = 'User Profiles';
$columns = ['ID', 'User', 'Linked User', 'Type', 'Name', 'Phone', 'City']; @endphp
@include('dashboard.crud.index', [
        'title' => $title,
        'breadcrumb' => 'Overview / User Profiles',
        'createUrl' => route('dashboard.user-profiles.create'),
        'columns' => $columns,
        'rows' => $rows,
        'filtersView' => 'dashboard.user_profiles.filters',
        'editUrl' => fn($r) => route('dashboard.user-profiles.edit', $r->id),
        'deleteUrl' => fn($r) => route('dashboard.user-profiles.destroy', $r->id),
        'renderRow' => function ($r) {
            $linked = $r->linked_user_id ? '#' . $r->linked_user_id : '-';
            return "
        <td>{$r->id}</td>
        <td>#{$r->user_id}</td>
        <td>{$linked}</td>
        <td>{$r->type}</td>
        <td>{$r->name}</td>
        <td>{$r->phone}</td>
        <td>{$r->city}</td>
       ";
        }
])
