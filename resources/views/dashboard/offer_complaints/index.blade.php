@php
    $title = 'Offer Complaints';
    $columns = ['ID', 'User', 'Offer', 'Subject', 'Description', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Offer Complaints',
    'createUrl' => route('dashboard.offer-complaints.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.offer_complaints.filters',
    'editUrl' => fn($r) => route('dashboard.offer-complaints.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.offer-complaints.destroy', $r->id),
    'renderRow' => function ($r) {
        $sub = e(\Illuminate\Support\Str::limit((string) $r->subject, 40));
        $desc = e(\Illuminate\Support\Str::limit((string) $r->description, 70));
        return "
        <td>{$r->id}</td>
        <td>#{$r->user_id}</td>
        <td>#{$r->offer_id}</td>
        <td>{$sub}</td>
        <td>{$desc}</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
