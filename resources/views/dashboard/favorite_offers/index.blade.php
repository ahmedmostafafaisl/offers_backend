@php
    $title = 'Favorite Offers';
    $columns = ['ID', 'User', 'Offer', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Favorite Offers',
    'createUrl' => route('dashboard.favorite-offers.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.favorite_offers.filters',
    'editUrl' => fn($r) => route('dashboard.favorite-offers.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.favorite-offers.destroy', $r->id),
    'renderRow' => function ($r) {
        return "
        <td>{$r->id}</td>
        <td>#{$r->user_id}</td>
        <td>#{$r->offer_id}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
