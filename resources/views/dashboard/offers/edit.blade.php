@php
    $title = 'Edit Offer';
    $backUrl = route('dashboard.offers.index');
    $action = route('dashboard.offers.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.offers.fields', ['row' => $row, 'categories' => $categories, 'providers' => $providers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
