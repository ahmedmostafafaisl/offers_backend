@php
    $title = 'Create Offer Image';
    $backUrl = route('dashboard.offer-images.index');
    $action = route('dashboard.offer-images.store');
    $method = 'POST';
    $fields = view('dashboard.offer_images.fields', ['row' => null, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
