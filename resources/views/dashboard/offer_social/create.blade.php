@php
    $title = 'Create Offer Social Media';
    $backUrl = route('dashboard.offer-social-media.index');
    $action = route('dashboard.offer-social-media.store');
    $method = 'POST';
    $fields = view('dashboard.offer_social.fields', ['row' => null, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
