@php
    $title = 'Offer Social Media';
    $columns = ['ID', 'Offer', 'Platform', 'URL', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Offer Social Media',
    'createUrl' => route('dashboard.offer-social-media.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.offer_social.filters',
    'editUrl' => fn($r) => route('dashboard.offer-social-media.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.offer-social-media.destroy', $r->id),
    'renderRow' => function ($r) {
        $url = '<a href="' . $r->url . '" target="_blank">' . $r->url . '</a>';
        return "
        <td>{$r->id}</td>
        <td>#{$r->offer_id}</td>
        <td>{$r->platform}</td>
        <td>{$url}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
