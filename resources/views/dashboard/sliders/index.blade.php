@php
    $title = 'Sliders';
    $columns = ['ID', 'Name', 'Image', 'Status', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Sliders',
    'createUrl' => route('dashboard.sliders.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.sliders.filters',
    'editUrl' => fn($r) => route('dashboard.sliders.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.sliders.destroy', $r->id),
    'renderRow' => function ($r) {
        $img = $r->image ? '<img src="' . asset('storage/' . $r->image) . '" style="width:52px;height:52px;border-radius:12px;object-fit:cover;border:1px solid #eee;">' : '-';
        $status = $r->status ? '<span class="status-pill"><span class="status-dot"></span>Active</span>' : '<span class="badge text-bg-secondary">Inactive</span>';
        return "
        <td>{$r->id}</td>
        <td>{$r->name}</td>
        <td>{$img}</td>
        <td>{$status}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
