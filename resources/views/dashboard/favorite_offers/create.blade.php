@php
    $title = 'Edit Favorite';
    $backUrl = route('dashboard.favorite-offers.index');
    $action = route('dashboard.favorite-offers.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.favorite_offers.fields', ['row' => $row, 'users' => $users, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
