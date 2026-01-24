@php
    $title = 'Categories';
    $columns = ['ID', 'Name', 'Image', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Categories',
    'createUrl' => route('dashboard.categories.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => null,
    'editUrl' => fn($r) => route('dashboard.categories.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.categories.destroy', $r->id),
    'renderRow' => function ($r) {
        $img = $r->image ? '<img src="' . asset('storage/' . $r->image) . '" style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid #eee;">' : '-';
        return "
        <td>{$r->id}</td>
        <td>{$r->name}</td>
        <td>{$img}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
