@php
    $title = 'Offer Images';
    $columns = ['ID', 'Offer', 'Image', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Offer Images',
    'createUrl' => route('dashboard.offer-images.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.offer_images.filters',
    'editUrl' => fn($r) => route('dashboard.offer-images.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.offer-images.destroy', $r->id),
    'renderRow' => function ($r) {
        $img = $r->image ? '<img src="' . asset('storage/' . $r->image) . '" style="width:52px;height:52px;border-radius:12px;object-fit:cover;border:1px solid #eee;">' : '-';
        return "
        <td>{$r->id}</td>
        <td>#{$r->offer_id}</td>
        <td>{$img}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
