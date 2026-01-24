@php
    $title = 'Create Offer';
    $backUrl = route('dashboard.offers.index');
    $action = route('dashboard.offers.store');
    $method = 'POST';
    $fields = view('dashboard.offers.fields', ['row' => null, 'categories' => $categories, 'providers' => $providers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
