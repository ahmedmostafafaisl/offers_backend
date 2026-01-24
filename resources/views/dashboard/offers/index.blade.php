@php
    $title = 'Offers';
    $columns = ['ID', 'Name', 'Provider', 'Category', 'City(AR/EN)', 'Active', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Offers',
    'createUrl' => route('dashboard.offers.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.offers.filters',
    'editUrl' => fn($r) => route('dashboard.offers.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.offers.destroy', $r->id),
    'renderRow' => function ($r) {
        $active = $r->is_active ? '<span class="status-pill"><span class="status-dot"></span>Active</span>' : '<span class="badge text-bg-secondary">Inactive</span>';
        $provider = $r->user?->name ?? $r->user_id;
        $category = $r->category?->name ?? $r->category_id;
        $city = trim(($r->city_ar ?? '') . ' / ' . ($r->city_en ?? ''), ' /');
        return "
        <td>{$r->id}</td>
        <td>{$r->name}</td>
        <td>{$provider}</td>
        <td>{$category}</td>
        <td>{$city}</td>
        <td>{$active}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
